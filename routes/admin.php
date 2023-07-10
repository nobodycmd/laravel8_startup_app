<?php

use App\Http\Controllers\Admin\DevToolController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginRecordController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\MerchantController;

use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\LogController;

use App\Http\Controllers\Admin\WebLogController;
use App\Http\Controllers\Admin\MessageController;

use \App\Http\Controllers\Admin\CommonController;


Route::prefix('admin')->name('admin.')->middleware([
    \App\Http\Middleware\TrafficCounter::class
])->group(function () {
    Route::prefix('index')->name('index.')->group(function () {
        Route::match(['get', 'post'], 'login', [IndexController::class, 'login'])->name('login');
    });

    Route::prefix('common')->name('common.')->group(function () {
        Route::match(['get', 'post'], 'webFileUpload', [CommonController::class, 'webFileUpload'])->name('webFileUpload');
    });

    Route::middleware([\App\Http\Middleware\AdminAuth::class])->group(function () {
        Route::prefix('index')->name('index.')->group(function () {
            Route::match(['get', 'post'],'system', [IndexController::class, 'system'])->name('system');
            Route::match(['get', 'post'], 'password', [IndexController::class, 'password'])->name('password');
            Route::get('logout', [IndexController::class, 'logout'])->name('logout');
        });

        Route::prefix('test')->name('test.')->group(function () {
            Route::match(['get', 'post'],'payment', [IndexController::class, 'system'])->name('payment');
        });

        Route::prefix('homepage')->name('homepage.')->group(function () {
            Route::match(['get', 'post'],'console', [HomePageController::class, 'console'])->name('console');
            Route::match(['get', 'post'], 'ordernumbercount', [HomePageController::class, 'orderNumberCount'])->name('ordernumbercount');
        });

        Route::prefix('adminpermission')->name('adminpermission.')->group(function () {
            Route::match(['get', 'post'],'index', [AdminPermissionController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [AdminPermissionController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit', [AdminPermissionController::class, 'edit'])->name('edit');
        });

        Route::prefix('weblog')->name('weblog.')->group(function () {
            Route::match(['get', 'post'],'index', [WebLogController::class, 'index'])->name('index');
            Route::match(['get', 'post'],'detail', [WebLogController::class, 'detail'])->name('detail');
        });

        Route::prefix('adminrole')->name('adminrole.')->group(function () {
            Route::match(['get', 'post'],'index', [AdminRoleController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [AdminRoleController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit', [AdminRoleController::class, 'edit'])->name('edit');
            Route::match(['get', 'post'], 'permission', [AdminRoleController::class, 'permission'])->name('permission');
        });
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::match(['get', 'post'],'index', [AdminController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [AdminController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit', [AdminController::class, 'edit'])->name('edit');
            Route::match(['get', 'post'],'googleauthenticator', [AdminController::class, 'googleauthenticator'])->name('googleauthenticator');
        });
        Route::prefix('adminloginrecord')->name('adminloginrecord.')->group(function () {
            Route::get('index', [AdminLoginRecordController::class, 'index'])->name('index');
        });
        Route::prefix('message')->name('message.')->group(function () {
            Route::match(['get',"post"],'sendmessage', [MessageController::class, 'sendMessage'])->name('sendmessage');
        });

        Route::prefix('agent')->name('agent.')->group(function () {
            Route::match(['get', 'post'],'index', [AgentController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [AgentController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit', [AgentController::class, 'edit'])->name('edit');
            Route::post('password', [AgentController::class, 'password'])->name('password');
            Route::post('googleauthenticator', [AgentController::class, 'googleauthenticator'])->name('googleauthenticator');
            Route::post('quicklogin', [AgentController::class, 'quicklogin'])->name('quicklogin');
        });

        Route::prefix('merchant')->name('merchant.')->group(function () {
            Route::match(['get', 'post'],'index', [MerchantController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [MerchantController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit', [MerchantController::class, 'edit'])->name('edit');
            Route::post('password', [MerchantController::class, 'password'])->name('password');
            Route::post('secretkey', [MerchantController::class, 'secretkey'])->name('secretkey');
            Route::match(['get', 'post'], 'balance', [MerchantController::class, 'balance'])->name('balance');
            Route::post('googleauthenticator', [MerchantController::class, 'googleauthenticator'])->name('googleauthenticator');
            Route::post('quicklogin', [MerchantController::class, 'quicklogin'])->name('quicklogin');
            Route::post('cancelPayoutFileOrders', [MerchantController::class, 'cancelPayoutFileOrders'])->name('cancelPayoutFileOrders');
        });

        Route::prefix('system')->name('system.')->group(function () {
            Route::match(['get', 'post'], 'config', [SystemController::class, 'config'])->name('config');
            Route::post('clearcache', [SystemController::class, 'clearcache'])->name('clearcache');
        });

        Route::prefix('log')->name('log.')->group(function () {
            //商户金额流水
            Route::get('merchantaccountlog', [LogController::class, 'merchantaccountlog'])->name('merchantaccountlog');
        });

        Route::prefix('devtool')->name('devtool.')->withoutMiddleware([
            'admin.auth'
        ])->group(function () {
            Route::get('index', [DevToolController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'tables', [DevToolController::class, 'tables'])->name('tables');
            Route::match(['get', 'post'], 'rows', [DevToolController::class, 'rows'])->name('rows');
            Route::match(['get', 'post'], 'codes', [DevToolController::class, 'codes'])->name('codes');
            Route::get('phpinfo', [DevToolController::class, 'phpinfo'])->name('phpinfo');
            Route::get('probe', [DevToolController::class, 'probe'])->name('probe');
        });

    });
});
