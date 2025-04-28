<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\AccessLevel;
use App\Models\Department;
use App\Models\DocumentCategory;
use App\Models\City;
use App\Models\SubCity;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use TCPDF_STATIC;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $documents = Document::with(['departments', 'city', 'subCities', 'accessLevel', 'user'])->latest();
        $documents = $documents->paginate(10);
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $departments = Department::all();
        $documentCategories = DocumentCategory::all();
        $cities = City::all();
        $subCities = SubCity::all();
        $accessLevels = AccessLevel::all();

        return view('documents.upload', compact('departments', 'cities', 'subCities', 'accessLevels', 'documentCategories'));
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return back()->with('error', 'User authentication required.');
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'department_ids' => 'required|array',
                'department_ids.*' => 'exists:departments,id',
                'document_category_id' => 'required|exists:document_categories,id',
                'city_ids' => 'required|array',
                'city_ids.*' => 'exists:cities,id',
                'subcity_ids' => 'required|array',
                'subcity_ids.*' => 'exists:sub_cities,id',
                'access_code' => 'required|string|min:6',
                'is_public' => 'boolean',
                'file_name' => 'required|file|max:10240' // 10MB max file size
            ]);

            // Get access level from document category
            $category = DocumentCategory::with('accessLevels')->findOrFail($request->document_category_id);
            if ($category->accessLevels->isEmpty()) {
                return back()->with('error', 'Selected category has no access levels assigned.');
            }

            $file = $request->file('file_name');
            if (!$file->isValid()) {
                return back()->with('error', 'Invalid file upload.');
            }

            $slug = Str::slug($request->title);
            $currentDate = Carbon::now()->toDateString();
            $filename = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Ensure upload directory exists
            $uploadPath = public_path('uploads/Documents');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Generate encryption key and IV
            $encryptionKey = random_bytes(32); // 32 bytes for AES-256
            $iv = openssl_random_pseudo_bytes(16); // 16 bytes for AES-256-CBC

            // Read and encrypt file content
            $fileContent = file_get_contents($file->getRealPath());
            if ($fileContent === false) {
                return back()->with('error', 'Failed to read uploaded file.');
            }

            $encryptedContent = openssl_encrypt(
                $fileContent,
                'AES-256-CBC',
                $encryptionKey,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($encryptedContent === false) {
                return back()->with('error', 'File encryption failed.');
            }

            // Combine IV and encrypted content, then base64 encode
            $encryptedContent = base64_encode($iv . $encryptedContent);

            // Save encrypted content
            $filePath = $uploadPath . DIRECTORY_SEPARATOR . $filename;
            if (file_put_contents($filePath, $encryptedContent) === false) {
                return back()->with('error', 'Failed to save encrypted file.');
            }

            // Create document record
            $document = Document::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filename,
                'file_type' => $file->getClientOriginalExtension(),
                'user_id' => $user->id,
                'document_category_id' => $request->document_category_id,
                'access_level_id' => implode(',', $category->accessLevels->pluck('id')->toArray()),
                'upload_date' => now(),
                'status' => 'active',
                'city_id' => implode(',', $request->city_ids),
                'is_public' => $request->boolean('is_public', false),
                'access_code' => $request->access_code,
                'encryption_key' => encrypt($encryptionKey)
            ]);

            // Sync relationships
            $document->departments()->sync($request->department_ids);
            $document->subCities()->sync($request->subcity_ids);

            return redirect()->route('documents.index')
                ->with('success', 'Document uploaded successfully.');

        } catch (\Exception $e) {
            Log::error('Document upload failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload document. Please try again.');
        }
    }

    public function edit(Document $document)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        if (!$document->canBeEditedByUser($user)) {
            abort(403, 'You do not have permission to edit this document.');
        }

        $departments = Department::all();
        $cities = City::all();
        $subCities = SubCity::all();
        $accessLevels = AccessLevel::all();

        return view('documents.edit', compact('document', 'departments', 'cities', 'subCities', 'accessLevels'));
    }

    public function update(Request $request, Document $document)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        if (!$document->canBeEditedByUser($user)) {
            abort(403, 'You do not have permission to edit this document.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_ids' => 'required|array',
            'department_ids.*' => 'exists:departments,id',
            'city_ids' => 'required|array',
            'city_ids.*' => 'exists:cities,id',
            'subcity_ids' => 'required|array',
            'subcity_ids.*' => 'exists:sub_cities,id',
            'access_level_id' => 'required|exists:access_levels,id',
            'is_public' => 'boolean',
            'file' => 'nullable|file|max:10240'
        ]);

        // Check if user has required access level
        if (!$user->hasAccessLevelOrHigher($request->access_level_id)) {
            return back()->with('error', 'You do not have permission to assign this access level.');
        }

        $data = $request->except(['file', 'department_ids', 'subcity_ids']);

        if ($request->hasFile('file')) {
            // Delete old file
            if (file_exists('uploads/Documents/' . $document->file_path)) {
                unlink('uploads/Documents/' . $document->file_path);
            }

            $file = $request->file('file');
            $slug = Str::slug($request->title);
            $currentDate = Carbon::now()->toDateString();
            $filename = $slug . '-' . $currentDate . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Generate new encryption key
            $encryptionKey = random_bytes(32);
            $iv = openssl_random_pseudo_bytes(16);

            // Read file content
            $fileContent = file_get_contents($file->getPathname());
            if ($fileContent === false) {
                return back()->with('error', 'Failed to read uploaded file.');
            }

            // Encrypt file content
            $encryptedContent = openssl_encrypt(
                $fileContent,
                'AES-256-CBC',
                $encryptionKey,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($encryptedContent === false) {
                return back()->with('error', 'File encryption failed.');
            }

            // Combine IV and encrypted content, then base64 encode
            $encryptedContent = base64_encode($iv . $encryptedContent);

            // Save encrypted content to file
            if (file_put_contents('uploads/Documents/' . $filename, $encryptedContent) === false) {
                return back()->with('error', 'Failed to save encrypted file.');
            }

            $data['file_path'] = $filename;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['encryption_key'] = encrypt($encryptionKey);
        }

        $document->update($data);

        // Sync departments and subcities
        $document->departments()->sync($request->department_ids);
        $document->subCities()->sync($request->subcity_ids);

        return redirect()->route('documents.index')
            ->with('success', 'Document updated successfully.');
    }

    public function verifyAccess(Document $document)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        if (!$document->isAccessibleToUser($user)) {
            abort(403, 'You do not have permission to view this document.');
        }

        return view('documents.access', compact('document'));
    }

    public function show(Request $request, Document $document)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                abort(401, 'Unauthorized');
            }

            if (!$document->isAccessibleToUser($user)) {
                abort(403, 'You do not have permission to view this document.');
            }

            // Validate access code
            $request->validate([
                'access_code' => 'required|string|min:6',
            ]);

            // Verify access code
            if ($request->access_code !== $document->access_code) {
                return back()->with('error', 'Invalid access code.');
            }

            // Read encrypted file content
            $filePath = public_path('uploads/Documents/' . $document->file_path);
            if (!file_exists($filePath)) {
                return back()->with('error', 'File not found.');
            }

            $encryptedContent = file_get_contents($filePath);
            if ($encryptedContent === false) {
                return back()->with('error', 'Failed to read encrypted file.');
            }

            // Decode base64 content
            $decodedContent = base64_decode($encryptedContent);
            if ($decodedContent === false) {
                return back()->with('error', 'Failed to decode encrypted file.');
            }

            // Extract IV (first 16 bytes) and encrypted data
            $iv = substr($decodedContent, 0, 16);
            $encryptedData = substr($decodedContent, 16);

            // Decrypt encryption key
            $encryptionKey = decrypt($document->encryption_key);
            if ($encryptionKey === false) {
                return back()->with('error', 'Failed to decrypt encryption key.');
            }

            // Decrypt file content
            $decryptedContent = openssl_decrypt(
                $encryptedData,
                'AES-256-CBC',
                $encryptionKey,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($decryptedContent === false) {
                return back()->with('error', 'File decryption failed.');
            }

            // Get MIME type
            $mimeType = $this->getMimeType($document->file_type);

            // Apply watermark based on file type
            $watermarkedContent = $this->addWatermark($decryptedContent, $mimeType, $user->name, $document->file_type);
            if ($watermarkedContent === false) {
                // Fallback to original content if watermarking fails
                $watermarkedContent = $decryptedContent;
            }

            // Return watermarked file (or original if watermarking failed) for inline display
            return response($watermarkedContent)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $document->file_name . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            Log::error('Document display failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to display document: ' . $e->getMessage());
        }
    }

    public function getByAccessCode($accessCode)
    {
        $document = Document::where('access_code', $accessCode)->firstOrFail();

        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        if (!$document->isAccessibleToUser($user)) {
            abort(403, 'You do not have permission to view this document.');
        }

        return $this->show($document);
    }

    /**
     * Add watermark to file content based on file type
     *
     * @param string $content File content
     * @param string $mimeType MIME type of the file
     * @param string $watermarkText Watermark text (user's name)
     * @param string $fileExtension File extension
     * @return string|bool Watermarked content or false on failure
     */
    private function addWatermark($content, $mimeType, $watermarkText, $fileExtension)
    {
        try {
            if ($mimeType === 'application/pdf') {
                // Handle PDF watermark
                $pdf = new Fpdi();
                try {
                    $pageCount = $pdf->setSourceFile(StreamReader::createByString($content));
                } catch (\Exception $e) {
                    Log::warning('PDF watermarking failed due to unsupported compression: ' . $e->getMessage());
                    return false; // Return false to use original content
                }

                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $templateId = $pdf->importPage($pageNo);
                    $size = $pdf->getTemplateSize($templateId);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($templateId);

                    // Set watermark properties
                    $pdf->SetFont('Helvetica', '', 40);
                    $pdf->SetTextColor(255, 0, 0);
                    $pdf->SetAlpha(0.3);
                    $pdf->Rotate(45);
                    $pdf->Text($size['width'] / 4, $size['height'] / 2, $watermarkText);
                    $pdf->Rotate(-45);
                }

                return $pdf->Output('', 'S');
            } elseif (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
                // Handle image watermark
                $image = imagecreatefromstring($content);
                if ($image === false) {
                    Log::error('Failed to create image from content');
                    return false;
                }

                // Get image dimensions
                $width = imagesx($image);
                $height = imagesy($image);

                // Create watermark text
                $fontSize = 20;
                $font = public_path('fonts/arial.ttf'); // Ensure you have a TTF font file in public/fonts/
                if (!file_exists($font)) {
                    Log::error('Font file not found: ' . $font);
                    return false;
                }

                // Calculate text size
                $bbox = imagettfbbox($fontSize, 45, $font, $watermarkText);
                $textWidth = abs($bbox[4] - $bbox[0]);
                $textHeight = abs($bbox[5] - $bbox[1]);

                // Set watermark position (center, rotated 45 degrees)
                $x = ($width - $textWidth) / 2;
                $y = ($height + $textHeight) / 2;

                // Allocate colors
                $red = imagecolorallocatealpha($image, 255, 0, 0, 50); // Semi-transparent red

                // Add rotated text
                imagettftext($image, $fontSize, 45, $x, $y, $red, $font, $watermarkText);

                // Output image to string
                ob_start();
                if ($mimeType === 'image/jpeg') {
                    imagejpeg($image, null, 90);
                } elseif ($mimeType === 'image/png') {
                    imagepng($image);
                } elseif ($mimeType === 'image/gif') {
                    imagegif($image);
                }
                $watermarkedContent = ob_get_clean();

                imagedestroy($image);
                return $watermarkedContent;
            }

            // Return original content for unsupported file types
            return $content;

        } catch (\Exception $e) {
            Log::error('Watermark application failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get MIME type for a file extension
     *
     * @param string $extension File extension
     * @return string MIME type
     */
    private function getMimeType($extension)
    {
        $mimes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt' => 'text/plain',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
        ];

        return $mimes[strtolower($extension)] ?? 'application/octet-stream';
    }
}