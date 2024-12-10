<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\TestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home_page');

Route::get('/test', [TestController::class, 'index']);
Route::get('/vue_test', [TestController::class, 'vueTest']);
Route::get('/package_json', [TestController::class, 'packageJson'])->name('package_json');


// Route::get('/dashboard', function () {
//     // return Inertia::render('Dashboard');
//     return Inertia::render('HomeView');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::any('/state/{state}/cities', [StateController::class, 'loadCities'])->name('get_cities_for_state');



Route::scopeBindings()->middleware(['auth', 'verified'])->group(function () {
    Route::any('in_app/state/{state}/cities', [StateController::class, 'loadCitiesInApp'])->name('get_cities_for_state_in_app');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notification.index');
    Route::get('/notification/{notification}', [NotificationController::class, 'show'])->name('notification.show');
});

Route::scopeBindings()->name('merchant.')->middleware(['auth','verified', 'merchant'])->group(function () {
    Route::get('/business/create', [BusinessController::class, 'create'])->name('businesses.create');

    Route::post('/business', [BusinessController::class, 'store'])->name('businesses.store');
});

Route::scopeBindings()->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::post('/businesses/load_all_admin', [BusinessController::class, 'loadAllBusinessesForAdmin'])->name('businesses.load_all');
});

Route::scopeBindings()->name('admin_or_merchant.')->middleware(['auth', 'verified', 'admin_or_merchant'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');
    Route::get('/business/{business}', [BusinessController::class, 'show'])->name('businesses.show');
    Route::get('/business/{business}/edit', [BusinessController::class, 'edit'])->name('businesses.edit');

    Route::post('/business/{business}', [BusinessController::class, 'update'])->name('businesses.update');
});

Route::get('/tables', function () {
    return Inertia::render('TablesView');
})->name('tables');

Route::get('/forms', function () {
    return Inertia::render('FormsView');
})->name('forms');

Route::get('/ui', function () {
    return Inertia::render('UiView');
})->name('ui');

Route::get('/responsive', function () {
    return Inertia::render('ResponsiveView');
})->name('responsive');

Route::get('/profile', function () {
    return Inertia::render('ProfileView');
})->name('profile');

Route::get('/sign_in', function () {
    return Inertia::render('Auth/Login');
})->name('sign_in');

Route::get('/error', function () {
    return Inertia::render('ErrorView');
})->name('error');

require __DIR__.'/auth.php';
