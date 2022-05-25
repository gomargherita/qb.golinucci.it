<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ClientDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteBuyProductController;
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

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'check_role']], function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('admin');

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('products', ProductController::class)->names([
        'index' => 'products'
    ]);

    Route::group(['middleware' => ['role:Superadmin']], function () {
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

    Route::get('/private-files/{file?}', 'FileController@get')->where('file', '(.*)');
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
    Route::get('/', 'home')->name('front.home');
    Route::get('/user-account', 'userAccount')->name('front.useraccount')->middleware(['auth']);
    Route::get('/about-us', 'aboutUs')->name('front.aboutus');
    Route::get('/products', 'products')->name('front.products');
    Route::get('/products/{product}', 'product')->name('front.product');
});


Route::group(['middleware' => ['auth']], function () {
    Route::controller(SiteBuyProductController::class)->group(function () {
        Route::get('/paypal_redirect', 'paypal_redirect')->name('front.paypal_redirect');
        Route::scopeBindings()->group(function () {
            Route::get('/products/{product}/buy/{cart:cart_ref?}', 'buy')->name('front.product.buy'); // pagina per il flusso di acquisto
            Route::post('/products/{product}/store_step1/{cart:cart_ref}', 'store_step1')->name('front.product.store_step1');
            Route::post('/products/{product}/store_step2/{cart:cart_ref}', 'store_step2')->name('front.product.store_step2');
            Route::post('/products/{product}/store_step3/{cart:cart_ref}', 'store_step3')->name('front.product.store_step3');
            Route::post('/products/{product}/store_step4/{cart:cart_ref}', 'store_step4')->name('front.product.store_step4');
            Route::get('/products/{product}/store_step4_paypal/{cart:cart_ref}/{payment_id}/r', 'store_step4_paypal_return')->name('front.product.store_step4_paypal_return');
            Route::get('/products/{product}/store_step4_paypal/{cart:cart_ref}/{payment_id}/c', 'store_step4_paypal_cancel')->name('front.product.store_step4_paypal_cancel');
            Route::post('/products/{product}/store_step5/{cart:cart_ref}', 'store_step5')->name('front.product.store_step5');
            Route::post('/products/{product}/store_step6/{cart:cart_ref}', 'store_step6')->name('front.product.store_step6');
        });

        Route::get('/test-import', 'testImport')->name('front.testimport');
    });
});

Route::get('/yousign/{cart:cart_ref}/member_finished', [YousignController::class, 'yousign_member_finished'])->name('front.yousign.member_finished');
Route::get('/yousign/{cart:cart_ref}/procedure_finished', [YousignController::class, 'yousign_procedure_finished'])->name('front.yousign.procedure_finished');
Route::get('/yousign/{cart:cart_ref}/procedure_refused', [YousignController::class, 'yousign_procedure_refused'])->name('front.yousign.procedure_refused');

require __DIR__ . '/auth.php';
