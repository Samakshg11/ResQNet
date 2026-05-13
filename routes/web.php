<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisasterController;
use App\Http\Controllers\SOSController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('landing');
})->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // SOS
    Route::get('/sos', [SOSController::class, 'index'])->name('sos.index');
    Route::get('/sos/create', [SOSController::class, 'create'])->name('sos.create');
    Route::post('/sos', [SOSController::class, 'store'])->name('sos.store');
    Route::get('/sos/{id}', [SOSController::class, 'show'])->name('sos.show');
    Route::post('/sos/{id}/assign', [SOSController::class, 'assign'])->name('sos.assign');
    Route::patch('/sos/{id}/status', [SOSController::class, 'updateStatus'])->name('sos.updateStatus');

    // Disasters
    Route::get('/disasters', [DisasterController::class, 'index'])->name('disasters.index');
    Route::get('/disasters/create', [DisasterController::class, 'create'])->name('disasters.create');
    Route::post('/disasters', [DisasterController::class, 'store'])->name('disasters.store');
    Route::get('/disasters/{id}', [DisasterController::class, 'show'])->name('disasters.show');
    Route::patch('/disasters/{id}', [DisasterController::class, 'update'])->name('disasters.update');

    // Agencies
    Route::get('/agencies', [AgencyController::class, 'index'])->name('agencies.index');
    Route::get('/agencies/create', [AgencyController::class, 'create'])->name('agencies.create');
    Route::post('/agencies', [AgencyController::class, 'store'])->name('agencies.store');
    Route::get('/agencies/{id}', [AgencyController::class, 'show'])->name('agencies.show');
    Route::post('/agencies/{id}/verify', [AgencyController::class, 'verify'])->name('agencies.verify');
    Route::post('/agencies/{id}/suspend', [AgencyController::class, 'suspend'])->name('agencies.suspend');

    // Resources
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    Route::post('/resources/{id}/deploy', [ResourceController::class, 'deploy'])->name('resources.deploy');

    // Volunteers
    Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
    Route::get('/volunteers/{id}', [VolunteerController::class, 'show'])->name('volunteers.show');

    // Alerts
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
