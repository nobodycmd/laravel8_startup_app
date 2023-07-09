<?php

use App\Http\Controllers\Merchant\AutoDownPointController;
use App\Http\Controllers\Merchant\AutoUpPointController;
use App\Http\Controllers\Merchant\HomePageController;
use App\Http\Controllers\Merchant\IndexController;
use App\Http\Controllers\Merchant\LogoController;
use App\Http\Controllers\Merchant\MerchantController;
use App\Http\Controllers\Merchant\PayinOrderController;
use App\Http\Controllers\Merchant\PayoutOrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('merchant')->name('merchant.')->middleware([
    \App\Http\Middleware\TrafficCounter::class
])->group(function () {
    Route::prefix('index')->name('index.')->group(function () {
        Route::match(['get', 'post'], 'login', [IndexController::class, 'login'])->name('login');
    });

    Route::middleware(\App\Http\Middleware\MerchantAuth::class)->group(function () {
        Route::prefix('index')->name('index.')->group(function () {
            Route::get('system', [IndexController::class, 'system'])->name('system');
            Route::match(['get', 'post'], 'password', [IndexController::class, 'password'])->name('password');
            Route::get('logout', [IndexController::class, 'logout'])->name('logout');
        });
        Route::prefix('homepage')->name('homepage.')->group(function () {
            Route::get('console', [HomePageController::class, 'console'])->name('console');
        });
        Route::prefix('merchant')->name('merchant.')->group(function () {
            Route::get('info', [MerchantController::class, 'info'])->name('info');
            Route::get('code', [MerchantController::class, 'code'])->name('code');
            Route::post('resetkey', [MerchantController::class, 'resetkey'])->name('resetkey');
            Route::post('seekey', [MerchantController::class, 'seekey'])->name('seekey');
            Route::post('seegc', [MerchantController::class, 'seegc'])->name('seegc');
            Route::post('resetgc', [MerchantController::class, 'resetgc'])->name('resetgc');

            Route::match(['get', 'post'], 'accountlog', [MerchantController::class, 'accountlog'])->name('accountlog');

            Route::match(['get', 'post'], "merchantorderstatistic", [MerchantController::class, "merchantorderstatistic"])->name("merchantorderstatistic");
        });
        Route::prefix('payinorder')->name('payinorder.')->group(function () {
            Route::match(['get', 'post'], 'index', [PayinOrderController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'export', [PayinOrderController::class, 'export'])->name('export');
            Route::match(['get', 'post'], 'credentials', [PayinOrderController::class, 'credentials'])->name('credentials');
            Route::get('query', [PayinOrderController::class, 'query'])->name('query');
            Route::match(['get','post'],'budan', [PayinOrderController::class, 'budan'])->name('budan');
            Route::match(['get', 'post'], 'notify', [PayinOrderController::class, 'notify'])->name('notify');

        });

        Route::prefix('payoutorder')->name('payoutorder.')->group(function () {
            Route::match(['get', 'post'], 'index', [PayoutOrderController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'export', [PayoutOrderController::class, 'export'])->name('export');
            Route::match(['get', 'post'], 'exportimps', [PayoutOrderController::class, 'exportimps'])->name('exportimps');
            Route::match(['get', 'post'], 'credentials', [PayoutOrderController::class, 'credentials'])
                 ->name('credentials');
            Route::get('query', [PayoutOrderController::class, 'query'])->name('query');
            Route::match(['get', 'post'], 'notify', [PayoutOrderController::class, 'notify'])->name('notify');
        });

        Route::prefix('autouppoint')->name('autouppoint.')->group(function () {
            Route::match(['get', 'post'],'index', [AutoUpPointController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [AutoUpPointController::class, 'create'])->name('create');
        });
        Route::prefix('autodownpoint')->name('autodownpoint.')->group(function () {
            Route::match(['get', 'post'],'index', [AutoDownPointController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [AutoDownPointController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'del', [AutoDownPointController::class, 'del'])->name('del');
        });
    });
});
