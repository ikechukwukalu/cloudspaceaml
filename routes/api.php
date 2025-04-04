<?php

use Cloudspace\AML\Http\Controllers\Api\RiskScanViewerController;
use Cloudspace\AML\Services\SanctionScan\AMLService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/aml/check', function (Request $request, AMLService $amlService) {
    return response()->json($amlService->checkSanctions($request->all()));
});

Route::prefix('aml')->group(function () {

    Route::prefix('scans')->group(function () {
        Route::get('/', [RiskScanViewerController::class, 'index']);
        Route::get('/{id}', [RiskScanViewerController::class, 'show']);
        Route::get('/{id}/pdf', [RiskScanViewerController::class, 'downloadPdf']);
    });

});

