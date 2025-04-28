<?php

namespace App\Http\Controllers;

use App\Models\AccessLevel;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\DocumentCategory;
use Illuminate\Database\QueryException;
use Exception;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isAdmin()) {
                abort(403, 'Only administrators can manage departments.');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try {
            $departments = Department::all();
            return view('departments.index', compact('departments'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        try {
            $documentCategories = DocumentCategory::all();
            $accessLevels = AccessLevel::all();
            $departments = Department::all();
            $cities = City::all();
            
            return view('departments.create', compact('documentCategories', 'accessLevels', 'departments', 'cities'));
        
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'DepartmentName' => 'required|string|max:255|unique:departments',
                'description' => 'nullable|string'
            ]);

            Department::create($validated);

            return redirect()->route('departments.index')
                ->with('success', 'Department created successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department)
    {
        try {
            return view('departments.show', compact('department'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        try {
            return view('departments.edit', compact('department'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        try {
            $validated = $request->validate([
                'DepartmentName' => 'required|string|max:255|unique:departments,DepartmentName,' . $department->id,
            ]);

            $department->update($validated);

            return redirect()->route('departments.index')
                ->with('success', 'Department updated successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department)
    {
        try {
            $department->delete();

            return redirect()->route('departments.index')
                ->with('success', 'Department deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }
}