<?php

use App\Http\Controllers\StoreKitchenAnalysisController;
use App\Http\Middleware\UseDryRunKitchenAnalysis;
use Illuminate\Support\Facades\Route;

Route::post('/kitchen-analyses', StoreKitchenAnalysisController::class)
    ->middleware(UseDryRunKitchenAnalysis::class);
