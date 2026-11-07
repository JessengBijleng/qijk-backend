<?php

use App\Http\Controllers\StoreKitchenAnalysisController;
use Illuminate\Support\Facades\Route;

Route::post('/kitchen-analyses', StoreKitchenAnalysisController::class);
