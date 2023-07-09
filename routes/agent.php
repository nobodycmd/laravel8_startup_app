<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agent\IndexController;
use App\Http\Controllers\Agent\HomePageController;
use App\Http\Controllers\Agent\AgentController;
use App\Http\Controllers\Agent\AgentSettlementController;

Route::prefix('agent')->name('agent.')->middleware([
    \App\Http\Middleware\TrafficCounter::class
])->group(function () {
    Route::prefix('index')->name('index.')->group(function () {
        Route::match(['get', 'post'], 'login', [IndexController::class, 'login'])->name('login');
    });
    Route::middleware([\App\Http\Middleware\AgentAuth::class])->group(function () {
        Route::prefix('index')->name('index.')->group(function () {
            Route::get('system', [IndexController::class, 'system'])->name('system');
            Route::match(['get', 'post'], 'password', [IndexController::class, 'password'])->name('password');
            Route::get('logout', [IndexController::class, 'logout'])->name('logout');
        });
        Route::prefix('homepage')->name('homepage.')->group(function () {
            Route::get('console', [HomePageController::class, 'console'])->name('console');
        });
        Route::prefix('agent')->name('agent.')->group(function () {
            Route::get('info', [AgentController::class, 'info'])->name('info');
            Route::get('myagent', [AgentController::class, 'myagent'])->name('myagent');
            Route::get('mymerchant', [AgentController::class, 'mymerchant'])->name('mymerchant');
            Route::get('payinorder', [AgentController::class, 'payinorder'])->name('payinorder');
            Route::get('payoutorder', [AgentController::class, 'payoutorder'])->name('payoutorder');
            Route::match(["get","post"],'merchatpayinline', [AgentController::class, 'merchatPayinLine'])->name('merchatpayinline');
            Route::match(["get","post"],'merchatpayoutline', [AgentController::class, 'merchatPayoutLine'])->name('merchatpayoutline');
        });
        Route::prefix('agentsettlement')->name('agentsettlement.')->group(function () {
            Route::get('index', [AgentSettlementController::class, 'index'])->name('index');
            Route::get('export', [AgentSettlementController::class, 'export'])->name('export');
        });
    });
});
