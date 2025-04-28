<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCity;
use App\Models\City;
use Illuminate\Database\QueryException;
use Exception;

class SubCityController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isAdmin()) {
                abort(403, 'Only administrators can manage sub-cities.');
            }
            return $next($request);
        });
    }
    public function index()
    {
        try {
            $subCities = SubCity::with('city')->get();
            return view('sub-cities.index', compact('subCities'));
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function getSubcities($cityId)
    {
        try {
            $subcities = Subcity::where('CityID', $cityId)->get();
            return response()->json($subcities);
        } catch (QueryException $e) {
            return response()->json(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new sub city.
     */
    public function create()
    {
        try {
            $cities = City::all();
            return view('sub-cities.create', compact('cities'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created sub city in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'SubCityName' => 'required|string|max:255',
                'CityID' => 'required|exists:cities,id'
            ]);

            SubCity::create($validated);

            return redirect()->route('sub-cities.index')
                ->with('success', 'Sub City created successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified sub city.
     */
    public function show(SubCity $subCity)
    {
        try {
            return view('sub-cities.show', compact('subCity'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified sub city.
     */
    public function edit(SubCity $subCity)
    {
        try {
            $cities = City::all();
            return view('sub-cities.edit', compact('subCity', 'cities'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified sub city in storage.
     */
    public function update(Request $request, SubCity $subCity)
    {
        try {
            $validated = $request->validate([
                'SubCityName' => 'required|string|max:255',
                'CityID' => 'required|exists:cities,id'
            ]);

            $subCity->update($validated);

            return redirect()->route('sub-cities.index')
                ->with('success', 'Sub City updated successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified sub city from storage.
     */
    public function destroy(SubCity $subCity)
    {
        try {
            $subCity->delete();

            return redirect()->route('sub-cities.index')
                ->with('success', 'Sub City deleted successfully.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }
}