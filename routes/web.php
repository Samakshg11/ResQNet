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

// Common authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Pending agency admin route
    Route::get('/agency/pending', function () {
        return view('auth.pending');
    })->name('agency.pending');
});

// Victim routes
Route::middleware(['auth', 'role:victim'])->group(function () {
    Route::get('/sos/my', [SOSController::class, 'my'])->name('sos.my');
    Route::post('/sos', [SOSController::class, 'store'])->name('sos.store');
    Route::patch('/sos/{id}/cancel', [SOSController::class, 'cancel'])->name('sos.cancel');
});

// Volunteer routes
Route::middleware(['auth', 'role:volunteer'])->group(function () {
    Route::get('/volunteer/dashboard', [DashboardController::class, 'volunteer'])->name('volunteer.dashboard');
    Route::get('/volunteer/profile', [\App\Http\Controllers\ProfileController::class, 'volunteerEdit'])->name('volunteer.profile.edit');
    Route::patch('/volunteer/profile', [\App\Http\Controllers\ProfileController::class, 'volunteerUpdate'])->name('volunteer.profile.update');
    Route::patch('/volunteer/sos/{id}/status', [SOSController::class, 'updateStatus'])->name('volunteer.sos.updateStatus');
});

// Agency Admin routes
Route::middleware(['auth', 'role:agency_admin', 'agency'])->group(function () {
    Route::get('/agency/dashboard', [DashboardController::class, 'agency'])->name('agency.dashboard');
    Route::get('/sos/feed', [SOSController::class, 'feed'])->name('sos.feed');
    Route::get('/agency/sos/{id}', [SOSController::class, 'show'])->name('agency.sos.show');
    Route::post('/agency/sos/{id}/assign', [SOSController::class, 'assignToAgency'])->name('agency.sos.assign');
    Route::patch('/agency/sos/{id}/status', [SOSController::class, 'updateStatus'])->name('agency.sos.updateStatus');
    
    Route::get('/agency/my', [AgencyController::class, 'my'])->name('agency.my');
    Route::patch('/agency/my', [AgencyController::class, 'updateMy'])->name('agency.updateMy');
    
    Route::get('/agency/resources', [ResourceController::class, 'agencyIndex'])->name('agency.resources.index');
    Route::post('/agency/resources', [ResourceController::class, 'store'])->name('agency.resources.store');
    Route::post('/agency/resources/{id}/deploy', [ResourceController::class, 'deploy'])->name('agency.resources.deploy');
    
    Route::get('/volunteers/my', [VolunteerController::class, 'my'])->name('volunteers.my');
    Route::post('/volunteers/my', [VolunteerController::class, 'storeMy'])->name('volunteers.storeMy');
    
    Route::get('/agency/alerts', [AlertController::class, 'index'])->name('agency.alerts.index');
    Route::get('/agency/analytics', [AnalyticsController::class, 'index'])->name('agency.analytics.index');
    Route::get('/agency/disasters', [DisasterController::class, 'index'])->name('agency.disasters.index');
});

// Admin routes (Gov & Super)
Route::middleware(['auth', 'role:gov_admin,super_admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/agencies', [AgencyController::class, 'index'])->name('agencies.index');
    Route::get('/agencies/create', [AgencyController::class, 'create'])->name('agencies.create');
    Route::get('/agencies/{id}', [AgencyController::class, 'show'])->name('agencies.show');
    Route::post('/agencies/{id}/verify', [AgencyController::class, 'verify'])->name('agencies.verify');
    Route::post('/agencies/{id}/suspend', [AgencyController::class, 'suspend'])->name('agencies.suspend');
    Route::post('/agencies/{id}/reject', [AgencyController::class, 'reject'])->name('agencies.reject');
    
    Route::get('/disasters', [DisasterController::class, 'index'])->name('disasters.index');
    Route::get('/disasters/create', [DisasterController::class, 'create'])->name('disasters.create');
    Route::post('/disasters', [DisasterController::class, 'store'])->name('disasters.store');
    Route::get('/disasters/{id}', [DisasterController::class, 'show'])->name('disasters.show');
    Route::patch('/disasters/{id}', [DisasterController::class, 'update'])->name('disasters.update');
    
    Route::get('/sos', [SOSController::class, 'index'])->name('sos.index');
    Route::get('/sos/{id}', [SOSController::class, 'show'])->name('sos.show');
    Route::post('/sos/{id}/assign', [SOSController::class, 'assign'])->name('sos.assign');
    
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
    
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');
    
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
});

// API Routes for pending counts
Route::middleware('auth')->group(function () {
    Route::get('/api/sos/pending-count', function () {
        return response()->json(['count' => \App\Models\SOSRequest::where('status', 'pending')->count()]);
    });
});

