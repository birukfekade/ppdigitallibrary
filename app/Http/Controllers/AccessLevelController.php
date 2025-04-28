<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessLevel;
use Illuminate\Database\QueryException;
use Exception;

class AccessLevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isAdmin()) {
                abort(403, 'You are not authorized to access this page.');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try {
            $accessLevels = AccessLevel::all();
            return view('access-levels.index', compact('accessLevels'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new access level.
     */
    public function create()
    {
        try {
            return view('access-levels.create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created access level in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'LevelName' => 'required|string|max:255|unique:access_levels',
            ]);

            AccessLevel::create($validated);

            return redirect()->route('access-levels.index')
                ->with('success', 'Access Level created successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified access level.
     */
    public function show(AccessLevel $accessLevel)
    {
        try {
            return view('access-levels.show', compact('accessLevel'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified access level.
     */
    public function edit(AccessLevel $accessLevel)
    {
        try {
            return view('access-levels.edit', compact('accessLevel'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified access level in storage.
     */
    public function update(Request $request, AccessLevel $accessLevel)
    {
        try {
            $validated = $request->validate([
                'LevelName' => 'required|string|max:255|unique:access_levels,LevelName,' . $accessLevel->id,
            ]);

            $accessLevel->update($validated);

            return redirect()->route('access-levels.index')
                ->with('success', 'Access Level updated successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified access level from storage.
     */
    public function destroy(AccessLevel $accessLevel)
    {
        try {
            $accessLevel->delete();

            return redirect()->route('access-levels.index')
                ->with('success', 'Access Level deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }
}