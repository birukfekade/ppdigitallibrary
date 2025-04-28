<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AccessLevelController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\SubCityController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/home', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }
    
    return $user->isAdmin() 
        ? redirect()->route('dashboard')
        : redirect()->route('userdashboard');
});



Auth::routes();

// API routes
Route::get('/api/cities/{city}/subcities', [CityController::class, 'getSubcities'])->name('api.cities.subcities');

Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('userdashboard');
    Route::get('/mydocuments', [ProfileController::class, 'mydocuments'])->name('mydocuments');

    Route::get('/mydocuments/{document}', [ProfileController::class, 'show'])->name('mydocuments.show');
    Route::get('/mydocuments/{document}/accesscode', [ProfileController::class, 'code'])->name('mydocuments.code');
    Route::post('/mydocuments/{document}/accesscode/verify', [ProfileController::class, 'verifyCode'])->name('mydocuments.verifyCode');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});



Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/upload', [DocumentController::class, 'create'])->name('documents.upload');
    Route::post('/documents/upload', [DocumentController::class, 'store'])->name('documents.upload.post');
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/verify', [DocumentController::class, 'verifyAccess'])->name('documents.verify');
    Route::post('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::delete('/documents/{id}/download', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::resource('document-categories', DocumentCategoryController::class);

    Route::resource('users', UserController::class);
    // Route::resource('documents', DocumentController::class);

    Route::resource('access-levels', AccessLevelController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('cities', CityController::class);
    Route::resource('sub-cities', SubCityController::class);
});
