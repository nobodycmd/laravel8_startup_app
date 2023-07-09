<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Tool\PdfController;

Route::prefix('tool')->name('tool.')->group(function () {
    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::match(['get', 'post'], 'download', [PdfController::class, 'download'])->name('download');
    });
    Route::prefix('html')->name('html.')->group(function () {
        Route::match(['get', 'post'], 'downloadTable', [\App\Http\Controllers\Tool\HtmlController::class, 'downloadTable'])->name('downloadTable');
    });
});
