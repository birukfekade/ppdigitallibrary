<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Database\QueryException;
use Exception;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // Allow getSubcities for all authenticated users
            if ($request->route()->getName() === 'api.cities.subcities') {
                return $next($request);
            }
            
            // Require admin for all other actions
            if (!$request->user()->isAdmin()) {
                abort(403, 'Only administrators can manage cities.');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try {
            $cities = City::all();
            return view('cities.index', compact('cities'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new city.
     */
    public function create()
    {
        try {
            return view('cities.create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created city in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'CityName' => 'required|string|max:255|unique:cities',
                'description' => 'nullable|string'
            ]);

            City::create($validated);

            return redirect()->route('cities.index')
                ->with('success', 'City created successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified city.
     */
    public function show(City $city)
    {
        try {
            return view('cities.show', compact('city'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified city.
     */
    public function edit(City $city)
    {
        try {
            return view('cities.edit', compact('city'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified city in storage.
     */
    public function update(Request $request, City $city)
    {
        try {
            $validated = $request->validate([
                'CityName' => 'required|string|max:255|unique:cities,name,' . $city->id,
                'description' => 'nullable|string'
            ]);

            $city->update($validated);

            return redirect()->route('cities.index')
                ->with('success', 'City updated successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified city from storage.
     */
    public function destroy(City $city)
    {
        try {
            $city->delete();

            return redirect()->route('cities.index')
                ->with('success', 'City deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Get subcities for a specific city.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubcities(City $city)
    {
        try {
            $subcities = $city->subCities()
                ->select('id', 'SubCityName')
                ->orderBy('SubCityName')
                ->get();

            return response()->json($subcities);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }
}