<?php

namespace App\Http\Controllers;

use App\Actions\AnalyzeKitchenPhoto;
use App\Http\Requests\StoreKitchenAnalysisRequest;
use App\Http\Resources\KitchenAnalysisResource;
use Illuminate\Http\JsonResponse;
use Laravel\Ai\Exceptions\AiException;

class StoreKitchenAnalysisController extends Controller
{
    public function __invoke(StoreKitchenAnalysisRequest $request, AnalyzeKitchenPhoto $analyzeKitchenPhoto): KitchenAnalysisResource|JsonResponse
    {
        try {
            $analysis = $analyzeKitchenPhoto->handle($request->file('photo'));
        } catch (AiException $exception) {
            report($exception);

            return response()->json(['message' => 'The kitchen photo could not be analysed. Try again later.'], 502);
        }

        return KitchenAnalysisResource::make($analysis)->response()->setStatusCode(201);
    }
}
