<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Jobseeker\JobController as JobseekerJobController;
use App\Http\Controllers\Employer\ApplicationController;

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/admin', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin', [AdminController::class, 'login'])->name('admin.login.post');

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| AUTH USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| EMPLOYER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('employer')->group(function () {

    Route::get('/dashboard', fn() => view('employer.dashboard'))->name('employer.dashboard');

    Route::get('/jobs/suggestions', [EmployerJobController::class, 'suggest'])->name('jobs.suggest');

    Route::resource('jobs', EmployerJobController::class);

    Route::get('/applications', [ApplicationController::class, 'index'])
        ->name('applications.index');

    Route::get('/applications/{id}', [ApplicationController::class, 'show'])
        ->name('applications.show');

    Route::put('/applications/{id}', [ApplicationController::class, 'update'])
        ->name('applications.update');

    Route::get('/interviews', fn() => view('employer.interviews.index'))->name('interviews.index');

    Route::get('/profile', fn() => view('employer.profile'))->name('employer.profile');
});

/*
|--------------------------------------------------------------------------
| JOBSEEKER ROUTES (FIXED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('jobseeker')->group(function () {

    Route::get('/dashboard', fn() => view('jobseeker.dashboard'))->name('jobseeker.dashboard');

    // ✅ USE CONTROLLER (NO MORE STATIC VIEW)
    Route::get('/jobs', [JobseekerJobController::class, 'index'])->name('jobseeker.jobs.index');

    Route::get('/jobs/{id}', [JobseekerJobController::class, 'show'])->name('jobseeker.jobs.show');

    Route::post('/jobs/{id}/apply', [JobseekerJobController::class, 'apply'])
        ->name('jobseeker.apply');

    Route::get('/jobs/suggest', [JobseekerJobController::class, 'suggest'])
        ->name('jobseeker.jobs.suggest');

    Route::get('/applications', fn() => view('jobseeker.applications.index'))->name('jobseeker.applications.index');

    Route::get('/saved-jobs', fn() => view('jobseeker.saved.index'))->name('jobseeker.saved.index');

    Route::get('/profile', fn() => view('jobseeker.profile'))->name('jobseeker.profile');
});
