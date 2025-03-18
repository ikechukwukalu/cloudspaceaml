<?php

use Cloudspace\AML\AMLService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/aml/check', function (Request $request, AMLService $amlService) {
    return response()->json($amlService->checkSanctions($request->all()));
});
