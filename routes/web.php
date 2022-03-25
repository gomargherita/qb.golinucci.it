<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ClientDetailsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('admin');

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::group(['middleware' => ['role:superadmin']], function () {
        Route::get('roles', [RolesController::class, 'index'])
            ->name('roles');

        Route::get('roles/create', [RolesController::class, 'create'])
            ->name('roles.create');

        Route::post('roles', [RolesController::class, 'store'])
            ->name('roles.store');

        Route::get('roles/{role}/edit', [RolesController::class, 'edit'])
            ->name('roles.edit');

        Route::put('roles/{role}', [RolesController::class, 'update'])
            ->name('roles.update');

        Route::delete('roles/{role}', [RolesController::class, 'destroy'])
            ->name('roles.destroy');
    });

    Route::get('users', [UsersController::class, 'index'])
        ->name('users');

    Route::get('users/create', [UsersController::class, 'create'])
        ->name('users.create');

    Route::post('users', [UsersController::class, 'store'])
        ->name('users.store');

    Route::get('users/{user}/edit', [UsersController::class, 'edit'])
        ->name('users.edit');

    Route::put('users/{user}', [UsersController::class, 'update'])
        ->name('users.update');

    Route::delete('users/{user}', [UsersController::class, 'destroy'])
        ->name('users.destroy');

    Route::put('users/{user}/restore', [UsersController::class, 'restore'])
        ->name('users.restore');

    Route::get('clientDetails', [ClientDetailsController::class, 'index'])
        ->name('clientDetails');

    Route::get('clientDetails/{user}/create', [ClientDetailsController::class, 'create'])
        ->name('clientDetails.create');

    Route::post('clientDetails', [ClientDetailsController::class, 'store'])
        ->name('clientDetails.store');

    Route::get('clientDetails/{client_detail}/edit', [ClientDetailsController::class, 'edit'])
        ->name('clientDetails.edit');

    Route::put('clientDetails/{client_detail}', [ClientDetailsController::class, 'update'])
        ->name('clientDetails.update');

    Route::delete('clientDetails/{client_detail}', [ClientDetailsController::class, 'destroy'])
        ->name('clientDetails.destroy');

    Route::put('clientDetails/{client_detail}/restore', [ClientDetailsController::class, 'restore'])
        ->name('clientDetails.restore');

    Route::get('reports', [ReportsController::class, 'index'])
        ->name('reports');
});



// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');

// Language selector
// https://devonmather.dev/posts/localizing-a-laravel-app-using-vuejs-and-inertiajs-without-any-dependencies
Route::get('language/{language}', function ($language) {
    Session()->put('locale', $language);

    return redirect()->back();
})->name('language');

// public frontend

Route::controller(SiteController::class)->group(function () {
    Route::get('/', 'home');
    Route::get('/about-us', 'aboutUs');
    Route::get('/products', 'products');
    Route::get('/products/{id}', 'product');
    Route::post('/products/{id}/funnel', 'buy');
});

require __DIR__ . '/auth.php';
