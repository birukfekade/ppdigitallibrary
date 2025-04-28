<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($request->only(['name', 'email', 'username', 'phone']));

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password changed successfully.');
    }

    public function code($document)
    {
        if (!$document->isAccessibleToUser(auth()->user())) {
            abort(403, 'You do not have permission to see this document.');
        }
        return view('front.code', compact('document'));
    }
    public function verifyCode(Request $request, $document)
    {
        $request->validate([
            'document_code' => ['required', 'string'],
        ]);
        
        $document = Document::where('id', $document)->firstOrFail();
        $user = auth()->user();
        $category = DocumentCategory::where('id', $document->document_category_id)->firstOrFail();
        $accessCode = $category->access_code;
        
        if ($accessCode !== $request->document_code) {
            return redirect()->back()->with('error', 'Invalid access code. Please try again.');
        } else {
            if (!$document->isAccessibleToUser(auth()->user())) {
                abort(403, 'You do not have permission to see this document.');
            }

            return view('front.show', compact('document'));
        }
    }

    public function show(Document $document)
    {
        return $this->code($document);
    }

    public function mydocuments()
    {
        try {
            $user = auth()->user();



            $accessLevel = $user->accessLevel;
            $documentCategories = DocumentCategory::whereHas('accessLevels', function ($query) use ($accessLevel) {
                $query->where('access_level_id', $accessLevel->id);
            })->get();


            $documents = Document::whereIn('document_category_id', $documentCategories->pluck('id'))->paginate(10);
            // Filter documents based on user access level and department
            $documents = $documents->filter(function ($document) use ($user) {
                return $document->isAccessibleToUser($user);
            });



            return view('front.documents', compact('documents'));
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while fetching documents: ' . $e->getMessage()
            ], 500);
        }
    }
}
