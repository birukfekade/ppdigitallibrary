<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Department;
use App\Models\User;
use Exception;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            return redirect()->route('userdashboard');
        }
        $data = [
            'totalDocuments' => Document::count(),
            'totalDepartments' => Department::count(),
            'totalUsers' => User::count(),
            'recentDocuments' => Document::count()
        ];

        // Get documents by access level
        $documentCategoryData = Document::selectRaw('document_category_id, COUNT(*) as count')
            ->groupBy('document_category_id')
            ->with('category')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->category->name => $item->count];
            });


        // Get documents by city
        $cityData = Document::selectRaw('city_id, COUNT(*) as count')
            ->groupBy('city_id')
            ->with('city')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->city->CityName => $item->count];
            });

        // Get documents by subcity using the many-to-many relationship
        $subcityData = [];
        $documents = Document::with('subCities')->get();
        foreach ($documents as $document) {
            $subCities = $document->subCities;
            if ($subCities->isEmpty()) {
                $subcityData['Unassigned'] = ($subcityData['Unassigned'] ?? 0) + 1;
            } else {
                foreach ($subCities as $subCity) {
                    $subcityData[$subCity->SubCityName] = ($subcityData[$subCity->SubCityName] ?? 0) + 1;
                }
            }
        }

        // Get documents by department using the many-to-many relationship
        $departmentData = [];
        $documents = Document::with('departments')->get();
        foreach ($documents as $document) {
            $departments = $document->departments;
            if ($departments->isEmpty()) {
                $departmentData['Unassigned'] = ($departmentData['Unassigned'] ?? 0) + 1;
            } else {
                foreach ($departments as $department) {
                    $departmentData[$department->DepartmentName] = ($departmentData[$department->DepartmentName] ?? 0) + 1;
                }
            }
        }
        // Documents by day (last 30 days)
        $dailyDocuments = Document::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $dailyLabels = [];
        $dailyData = [];
        $startDate = now()->subDays(30);
        for ($i = 0; $i <= 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $dailyLabels[] = $date;
            $dailyData[] = $dailyDocuments[$date] ?? 0;
        }
        $data['dailyLabels'] = $dailyLabels;
        $data['dailyData'] = $dailyData;
        $accessLevelData = Document::selectRaw('access_level_id, COUNT(*) as count')
            ->groupBy('access_level_id')
            ->with('accessLevel')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->accessLevel?->LevelName ?? 'Unassigned',
                    'y' => $item->count
                ];
            })
            ->toArray();
        // Documents by city
        $cityData = Document::selectRaw('city_id, COUNT(*) as count')
            ->groupBy('city_id')
            ->with('city')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->city?->CityName ?? 'Unassigned',
                    'y' => $item->count
                ];
            })
            ->toArray();
            // Documents by department
        $departmentData = [];
        $documents = Document::with('departments')->get();
        foreach ($documents as $document) {
            $departments = $document->departments;
            if ($departments->isEmpty()) {
                $departmentData['Unassigned'] = ($departmentData['Unassigned'] ?? 0) + 1;
            } else {
                foreach ($departments as $department) {
                    $departmentData[$department->DepartmentName] = ($departmentData[$department->DepartmentName] ?? 0) + 1;
                }
            }
        }
        $departmentChartData = array_map(function ($key, $value) {
            return ['name' => $key, 'y' => $value];
        }, array_keys($departmentData), $departmentData);

        // Documents by month (current year, January to December)
        $monthlyDocuments = Document::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $monthlyLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyData[] = $monthlyDocuments[$month] ?? 0;
        }
        $data['monthlyLabels'] = $monthlyLabels;
        $data['monthlyData'] = $monthlyData;
        $data['departmentChartData'] = $departmentChartData;
        $data['cityData'] = $cityData;
        $data['accessLevelData'] = $accessLevelData;
        $data['documentCategoryData'] = $documentCategoryData;


        return view('dashboard', $data);
    }

    public function dashboard()
    {
        try {
            $user = auth()->user();

            $documents = Document::where(function ($query) use ($user) {
                $query->where('access_level_id', $user->AccessLevelID)
                    ->orWhereHas('departments', function ($q) use ($user) {
                        $q->where('departments.id', $user->DepartmentID);
                    })
                    ->orWhere(function ($q) use ($user) {
                        $q->where('city_id', $user->CityID)
                            ->orWhere('city_id', 'all');
                    })
                    ->orWhereHas('subCities', function ($q) use ($user) {
                        $q->where('sub_cities.id', $user->SubCityID);
                    });
            })
                ->with(['accessLevel', 'departments', 'city', 'subCities'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('front.dashboard', compact('documents'));
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while fetching documents: ' . $e->getMessage()
            ], 500);
        }
    }
}
