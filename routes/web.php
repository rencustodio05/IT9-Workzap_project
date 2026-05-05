<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminJobController;
use App\Http\Controllers\Admin\AdminEmployerController;
use App\Http\Controllers\Admin\AdminApplicantController;
use App\Http\Controllers\Admin\AdminArchiveController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Jobseeker\JobController as JobseekerJobController;
use App\Http\Controllers\Employer\ApplicationController;
use App\Http\Controllers\Employer\InterviewController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\Employer\ProfileController as EmployerProfileController;
use App\Http\Controllers\Employer\SubscriptionController as EmployerSubscriptionController;
use App\Http\Controllers\SubscriptionPaymentController;
use App\Http\Controllers\Jobseeker\ApplicationController as JobseekerApplicationController;
use App\Http\Controllers\Jobseeker\DashboardController as JobseekerDashboardController;
use App\Http\Controllers\Jobseeker\ProfileController as JobseekerProfileController;
use App\Http\Controllers\Jobseeker\SavedJobController;

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

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

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    Route::resource('jobs', AdminJobController::class)->except(['destroy']);
    Route::delete('/jobs/{job}', [AdminJobController::class, 'destroy'])->name('jobs.destroy');

    Route::get('/employers', [AdminEmployerController::class, 'index'])->name('employers.index');
    Route::get('/employers/{employer}', [AdminEmployerController::class, 'show'])->name('employers.show');
    Route::patch('/employers/{employer}/toggle-status', [AdminEmployerController::class, 'toggleStatus'])->name('employers.toggle-status');

    Route::get('/applicants', [AdminApplicantController::class, 'index'])->name('applicants.index');
    Route::get('/applicants/{applicant}', [AdminApplicantController::class, 'show'])->name('applicants.show');
    Route::patch('/applicants/{applicant}/toggle-status', [AdminApplicantController::class, 'toggleStatus'])->name('applicants.toggle-status');

    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');

    Route::get('/subscription-payments', [SubscriptionPaymentController::class, 'index'])->name('subscription-payments.index');
    Route::patch('/subscription-payments/{subscriptionPayment}/status', [SubscriptionPaymentController::class, 'updateStatus'])->name('subscription-payments.update-status');

    Route::get('/archive', [AdminArchiveController::class, 'index'])->name('archive.index');
    Route::post('/archive/users/{id}/restore', [AdminArchiveController::class, 'restoreUser'])->name('archive.users.restore');
    Route::post('/archive/jobs/{id}/restore', [AdminArchiveController::class, 'restoreJob'])->name('archive.jobs.restore');
    Route::delete('/archive/users/{id}/force-delete', [AdminArchiveController::class, 'forceDeleteUser'])->name('archive.users.force-delete');
    Route::delete('/archive/jobs/{id}/force-delete', [AdminArchiveController::class, 'forceDeleteJob'])->name('archive.jobs.force-delete');

    Route::get('/profile', [AdminSettingsController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminSettingsController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AdminSettingsController::class, 'updatePassword'])->name('profile.password.update');

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| AUTH USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/applications/history', [JobseekerApplicationController::class, 'history'])->name('jobseeker.applications.history');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| EMPLOYER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('employer')->name('employer.')->group(function () {

    Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/subscription', [EmployerSubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/request', [EmployerSubscriptionController::class, 'store'])->name('subscription.store');
    Route::post('/subscription-payments', [SubscriptionPaymentController::class, 'store'])->name('subscription-payments.store');

    Route::get('/jobs/create', [EmployerJobController::class, 'create'])
        ->middleware('employer.subscription.active')
        ->name('jobs.create');

    Route::post('/jobs', [EmployerJobController::class, 'store'])
        ->middleware('employer.subscription.active')
        ->name('jobs.store');

    Route::resource('jobs', EmployerJobController::class)->except(['create', 'store']);

    // Applications resource (index, show, update)
    Route::resource('applications', ApplicationController::class)->only(['index', 'show', 'update']);
    Route::get('/applications/{application}/decision', [ApplicationController::class, 'decision'])->name('applications.decision');
    Route::post('/applications/{application}/hire', [ApplicationController::class, 'hire'])->name('applications.hire');
    Route::post('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
    Route::post('/applications/{application}/fire', [ApplicationController::class, 'fire'])->name('applications.fire');

    // Interviews (index, show) + explicit store route
    Route::resource('interviews', InterviewController::class)->only(['index', 'show']);
    Route::post('interviews', [InterviewController::class, 'store'])->name('interviews.store');

    Route::get('/profile', [EmployerProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [EmployerProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [EmployerProfileController::class, 'updatePassword'])->name('profile.password');

    Route::put('/interviews/{interview}', [InterviewController::class, 'update'])->name('interviews.update');
});

/*
|--------------------------------------------------------------------------
| JOBSEEKER ROUTES (FIXED)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('jobseeker')->group(function () {

    Route::get('/dashboard', [JobseekerDashboardController::class, 'index'])->name('jobseeker.dashboard');

    // ✅ USE CONTROLLER (NO MORE STATIC VIEW)
    Route::get('/jobs', [JobseekerJobController::class, 'index'])->name('jobseeker.jobs.index');

    Route::get('/jobs/{id}', [JobseekerJobController::class, 'show'])->name('jobseeker.jobs.show');

    Route::post('/jobs/{id}/apply', [JobseekerJobController::class, 'apply'])
        ->name('jobseeker.apply');

    Route::get('/applications', [JobseekerApplicationController::class, 'index'])->name('jobseeker.applications.index');
    Route::get('/applications/{id}', [JobseekerApplicationController::class, 'show'])->name('jobseeker.applications.show');
    Route::put('/applications/{id}', [JobseekerApplicationController::class, 'update'])->name('jobseeker.applications.update');
    Route::delete('/applications/{id}', [JobseekerApplicationController::class, 'destroy'])->name('jobseeker.applications.destroy');

    Route::get('/saved-jobs', [SavedJobController::class, 'index'])->name('jobseeker.saved.index');
    Route::post('/saved-jobs/{job}', [SavedJobController::class, 'store'])->name('jobseeker.saved.store');
    Route::delete('/saved-jobs/{job}', [SavedJobController::class, 'destroy'])->name('jobseeker.saved.destroy');

    Route::get('/profile', [JobseekerProfileController::class, 'showProfile'])->name('jobseeker.profile');
    Route::get('/profile/edit', [JobseekerProfileController::class, 'editProfile'])->name('jobseeker.profile.edit');
    Route::get('/account-security', [JobseekerProfileController::class, 'accountSecurity'])->name('jobseeker.account.security');
    Route::put('/profile', [JobseekerProfileController::class, 'updateProfile'])->name('jobseeker.profile.update');
    Route::put('/profile/password', [JobseekerProfileController::class, 'updatePassword'])->name('jobseeker.profile.password');
});
