<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function filterQueryByUserAccess($query)
    {
        $user = auth()->user();

        if (!$user->hasAccessLevelOrHigher(3)) { // Not an admin
            if ($user->DepartmentID) {
                $query->whereHas('departments', function($q) use ($user) {
                    $q->where('departments.id', $user->DepartmentID);
                });
            }
            
            if ($user->CityID) {
                $query->where('city_id', $user->CityID);
            }
            
            if ($user->SubCityID) {
                $query->where('subcity_id', $user->SubCityID);
            }
        }

        return $query;
    }

    protected function getAccessibleDepartments()
    {
        $user = auth()->user();
        $query = \App\Models\Department::query();

        if (!$user->hasAccessLevelOrHigher(3)) {
            $query->where('id', $user->DepartmentID);
        }

        return $query->get();
    }

    protected function getAccessibleCities()
    {
        $user = auth()->user();
        $query = \App\Models\City::query();

        if (!$user->hasAccessLevelOrHigher(3)) {
            $query->where('id', $user->CityID);
        }

        return $query->get();
    }

    protected function getAccessibleSubCities()
    {
        $user = auth()->user();
        $query = \App\Models\SubCity::query();

        if (!$user->hasAccessLevelOrHigher(3)) {
            if ($user->CityID) {
                $query->where('CityID', $user->CityID);
            }
            if ($user->SubCityID) {
                $query->where('id', $user->SubCityID);
            }
        }

        return $query->get();
    }
}
