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

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Roles
Route::group(['middleware' => ['role:superadmin']], function () {
    Route::get('roles', [RolesController::class, 'index'])
        ->name('roles')
        ->middleware('auth');

    Route::get('roles/create', [RolesController::class, 'create'])
        ->name('roles.create')
        ->middleware('auth');

    Route::post('roles', [RolesController::class, 'store'])
        ->name('roles.store')
        ->middleware('auth');

    Route::get('roles/{role}/edit', [RolesController::class, 'edit'])
        ->name('roles.edit')
        ->middleware('auth');

    Route::put('roles/{role}', [RolesController::class, 'update'])
        ->name('roles.update')
        ->middleware('auth');

    Route::delete('roles/{role}', [RolesController::class, 'destroy'])
        ->name('roles.destroy')
        ->middleware('auth');
});

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// ClientDetails

Route::get('clientDetails', [ClientDetailsController::class, 'index'])
    ->name('clientDetails')
    ->middleware('auth');

Route::get('clientDetails/{user}/create', [ClientDetailsController::class, 'create'])
    ->name('clientDetails.create')
    ->middleware('auth');

Route::post('clientDetails', [ClientDetailsController::class, 'store'])
    ->name('clientDetails.store')
    ->middleware('auth');

Route::get('clientDetails/{client_detail}/edit', [ClientDetailsController::class, 'edit'])
    ->name('clientDetails.edit')
    ->middleware('auth');

Route::put('clientDetails/{client_detail}', [ClientDetailsController::class, 'update'])
    ->name('clientDetails.update')
    ->middleware('auth');

Route::delete('clientDetails/{client_detail}', [ClientDetailsController::class, 'destroy'])
    ->name('clientDetails.destroy')
    ->middleware('auth');

Route::put('clientDetails/{client_detail}/restore', [ClientDetailsController::class, 'restore'])
    ->name('clientDetails.restore')
    ->middleware('auth');

// Reports

Route::get('reports', [ReportsController::class, 'index'])
    ->name('reports')
    ->middleware('auth');

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
    Route::get('/sites', 'home');
    Route::get('/sites/about-us', 'aboutUs');
    Route::get('/sites/products', 'products');
    Route::get('/sites/products/{id}', 'product');
    Route::post('/sites/products/{id}/funnel', 'buy');
});

require __DIR__ . '/auth.php';
