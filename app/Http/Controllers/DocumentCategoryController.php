<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use App\Models\AccessLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isAdmin()) {
                abort(403, 'Only administrators can manage categories.');
            }
            return $next($request);
        });
    }
    public function index()
    {
        $categories = DocumentCategory::with('accessLevels')->get();
        return view('document-categories.index', compact('categories'));
    }

    public function create()
    {
        $accessLevels = AccessLevel::all();
        return view('document-categories.create', compact('accessLevels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'access_levels' => 'required|array',
            'access_levels.*' => 'exists:access_levels,id'
        ]);

        $category = DocumentCategory::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        $category->accessLevels()->attach($request->access_levels);

        return redirect()->route('document-categories.index')
            ->with('success', 'Document category created successfully.');
    }

    public function edit(DocumentCategory $documentCategory)
    {
        $accessLevels = AccessLevel::all();
        $selectedLevels = $documentCategory->accessLevels->pluck('id')->toArray();
        return view('document-categories.edit', compact('documentCategory', 'accessLevels', 'selectedLevels'));
    }

    public function update(Request $request, DocumentCategory $documentCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'access_levels' => 'required|array',
            'access_levels.*' => 'exists:access_levels,id'
        ]);

        $documentCategory->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        $documentCategory->accessLevels()->sync($request->access_levels);

        return redirect()->route('document-categories.index')
            ->with('success', 'Document category updated successfully.');
    }

    public function destroy(DocumentCategory $documentCategory)
    {
        if ($documentCategory->documents()->count() > 0) {
            return redirect()->route('document-categories.index')
                ->with('error', 'Cannot delete category. It has associated documents.');
        }

        $documentCategory->accessLevels()->detach();
        $documentCategory->delete();

        return redirect()->route('document-categories.index')
            ->with('success', 'Document category deleted successfully.');
    }
}
