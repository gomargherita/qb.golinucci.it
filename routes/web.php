<?php

use App\Http\Controllers\BordereauxController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ClientDetailsController;
use App\Http\Controllers\DoprintCounterController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCounterController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteBuyProductController;
use App\Http\Controllers\YousignController;
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

    Route::get('carts/{product_type}', [CartController::class, 'index'])
        ->name('carts');

    Route::get('carts/{product_type}/create', [CartController::class, 'create'])
        ->name('carts.create');
    
    Route::get('carts/{product_type}/create/{user_id}', [CartController::class, 'create'])
        ->name('carts.create.userid');

    Route::get('carts/{product_type}/create/{user_id}/{product_id}', [CartController::class, 'create'])
        ->name('carts.create.userid.productid');

    Route::post('carts/{product}/store_step1/{cart:cart_ref}', [CartController::class, 'store_step1'])
        ->name('carts.store_step1');

    Route::post('carts/{product}/store_step2/{cart:cart_ref}', [CartController::class, 'store_step2'])
        ->name('carts.store_step2');
    
    Route::post('carts/{product}/store_step3/{cart:cart_ref}', [CartController::class, 'store_step3'])
        ->name('carts.store_step3');
    
    Route::post('carts/{product}/store_step4/{cart:cart_ref}', [CartController::class, 'store_step4'])
        ->name('carts.store_step4');

    Route::post('carts', [CartController::class, 'store'])
        ->name('carts.store');

    Route::get('carts/{cart}/edit', [CartController::class, 'edit'])
        ->name('carts.edit');
    
        Route::get('carts/{cart}/updatestep6', [CartController::class, 'updateToStep6'])
        ->name('carts.updatetostep6');

    Route::get('carts/{cart}/sendemail', [CartController::class, 'sendEmail'])
        ->name('carts.sendemail');

    Route::get('carts/{cart}/{product_id}/sendfirstemail', [CartController::class, 'sendFirstEmail'])
        ->name('carts.sendfirstemail');

    Route::put('carts/{cart}/updatedecorrenza', [CartController::class, 'updateDecorrenza'])
        ->name('carts.updatedecorrenza');

    Route::get('carts/{cart}/generatelloyds', [CartController::class, 'generateLloyds'])
        ->name('carts.generatelloyds');

    Route::post('carts/{cart}/storeLloyds', [CartController::class, 'storeLloyds'])
        ->name('carts.storeLloyds');

    Route::put('carts/{cart}', [CartController::class, 'update'])
        ->name('carts.update');

    Route::delete('carts/{cart}', [CartController::class, 'destroy'])
        ->name('carts.destroy');

    Route::put('carts/{cart}/restore', [CartController::class, 'restore'])
        ->name('carts.restore');

    Route::get('carts/{product}/{cart:cart_ref?}', [CartController::class, 'continue'])
        ->name('carts.continue');

    Route::put('payments/{payment}', [PaymentController::class, 'update'])
        ->name('payments.update');

    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])
        ->name('payments.destroy');

    Route::get('productCounters/{productCounter}/edit', [ProductCounterController::class, 'edit'])
        ->name('productCounters.edit');

    Route::put('productCounters/{productCounter}', [ProductCounterController::class, 'update'])
        ->name('productCounters.update');

    Route::get('doprintCounters/{doprintCounter}/edit', [DoprintCounterController::class, 'edit'])
        ->name('doprintCounters.edit');

    Route::put('doprintCounters/{doprintCounter}', [DoprintCounterController::class, 'update'])
        ->name('doprintCounters.update');

    Route::get('bordereauxs', [BordereauxController::class, 'index'])->name('bordereauxs');
    Route::post('bordereauxs', [BordereauxController::class, 'download'])->name('bordereauxs.download');

    Route::get('private-files/{file?}', [FileController::class, 'get'])->where('file', '(.*)')->name('privateFiles');

    Route::put('carts/{cart}/certificateupdate', [CartController::class, 'certificateUpdate'])
    ->name('carts.certificateupdate');
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
    Route::get('/importo_libero/{id?}', 'importo_libero')->name('front.importoLibero');
    Route::post('/importo_liberoStep2', 'importo_libero_store_step1')->name('front.importoLiberoStep2');
    Route::post('/importo_libero/pay/{id}', 'importoLiberoPay')->name('front.importoLiberoPay');
    Route::get('/importo_libero/check_payment/{id}', 'importoLiberoCheckPayment')->name('front.importoLiberoCheckPayment');
    Route::get('/.well-known/apple-developer-merchantid-domain-association', 'applePayAssociationFile')->name('front.applePayAssociationFile');

    Route::get('/ehealth/{id?}', 'ehealth')->name('front.ehealth');
    Route::post('/ehealthStep2', 'ehealth_store_step1')->name('front.ehealthStep2');
    Route::post('/ehealth/pay/{id}', 'ehealthPay')->name('front.ehealthPay');
    Route::get('/ehealth/send_email/{id}', 'ehealthSendEmail')->name('front.ehealthComplete');
});
Route::get('private-files/{file?}', [FileController::class, 'getUser'])->where('file', '(.*)')->name('privateFilesUser')->middleware(['auth']);


Route::group(['middleware' => ['auth']], function () {
    Route::controller(SiteBuyProductController::class)->group(function () {
        Route::get('/paypal_redirect', 'paypal_redirect')->name('front.paypal_redirect');
        Route::scopeBindings()->group(function () {
            Route::get('/products/{product}/buy/{cart:cart_ref?}', 'buy')->name('front.product.buy'); // pagina per il flusso di acquisto
            Route::get('/products/{product}/renew/{cart:cart_ref}', 'renew')->name('front.product.renew'); // pagina per il flusso di RINNOVO
            Route::post('/products/{product}/store_step1/{cart:cart_ref}', 'store_step1')->name('front.product.store_step1');
            Route::post('/products/{product}/store_step2/{cart:cart_ref}', 'store_step2')->name('front.product.store_step2');
            Route::post('/products/{product}/store_step3/{cart:cart_ref}', 'store_step3')->name('front.product.store_step3');
            Route::post('/products/{product}/store_step4/{cart:cart_ref}/{gateway}/', 'store_step4')->name('front.product.store_step4');
            Route::get('/products/{product}/store_step4_paypal/{cart:cart_ref}/{payment_id}/', 'store_step4_paypal_return')->name('front.product.store_step4_paypal_return');
            Route::get('/products/{product}/store_step4_paypal/{cart:cart_ref}/{payment_id}/c', 'store_step4_paypal_cancel')->name('front.product.store_step4_paypal_cancel');
            Route::post('/products/{product}/store_step5/{cart:cart_ref}', 'store_step5')->name('front.product.store_step5');
        });
    });
});

Route::get('/yousign/member_finished', [YousignController::class, 'yousign_member_finished'])->name('front.yousign.member_finished');
Route::get('/yousign/procedure_finished', [YousignController::class, 'yousign_procedure_finished'])->name('front.yousign.procedure_finished');
Route::get('/yousign/procedure_refused', [YousignController::class, 'yousign_procedure_refused'])->name('front.yousign.procedure_refused');
Route::get('/yousign/procedure_error', [YousignController::class, 'yousign_procedure_error'])->name('front.yousign.procedure_error');
Route::get('/yousign/procedure_expired', [YousignController::class, 'yousign_procedure_expired'])->name('front.yousign.procedure_expired');
Route::get('/yousign/server_stamps_finished', [YousignController::class, 'yousign_server_stamps_finished'])->name('front.yousign.server_stamps_finished');
Route::get('/yousign/server_stamps_error', [YousignController::class, 'yousign_server_stamps_error'])->name('front.yousign.server_stamps_error');

require __DIR__ . '/auth.php';
