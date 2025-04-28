<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccessLevel;
use App\Models\Department;
use App\Models\City;
use App\Models\SubCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!$request->user()->isAdmin()) {
                abort(403, 'Only administrators can manage users.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $query = User::with(['accessLevel', 'department', 'city', 'subCity']);
        $query = $this->filterQueryByUserAccess($query);
        $users = $query->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::all();
        $cities = City::all();
        $subCities = SubCity::all();
        $accessLevels = AccessLevel::all();
        $roles = ['admin' => 'Administrator', 'manager' => 'Manager', 'user' => 'User'];

        return view('users.create', compact('departments', 'cities', 'subCities', 'accessLevels', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:admin,manager,user'],
            'AccessLevelID' => ['required', 'exists:access_levels,id'],
            'DepartmentID' => ['required', 'exists:departments,id'],
            'CityID' => ['required', 'exists:cities,id'],
            'SubCityID' => ['required', 'exists:sub_cities,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => 'active',
            'AccessLevelID' => $request->AccessLevelID,
            'DepartmentID' => $request->DepartmentID,
            'CityID' => $request->CityID,
            'SubCityID' => $request->SubCityID,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $departments = $this->getAccessibleDepartments();
        $cities = $this->getAccessibleCities();
        $subCities = $this->getAccessibleSubCities();
        $accessLevels = AccessLevel::all();
        $roles = ['admin' => 'Administrator', 'manager' => 'Manager', 'user' => 'User'];

        return view('users.edit', compact('user', 'departments', 'cities', 'subCities', 'accessLevels', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:admin,manager,user'],
            'AccessLevelID' => ['required', 'exists:access_levels,id'],
            'DepartmentID' => ['required', 'exists:departments,id'],
            'CityID' => ['required', 'exists:cities,id'],
            'SubCityID' => ['required', 'exists:sub_cities,id'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', Password::defaults()];
        }

        $request->validate($rules);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
