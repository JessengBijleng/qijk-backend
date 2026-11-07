<?php

namespace App\Actions;

use App\Ai\Agents\KitchenPhotoAnalyst;
use App\Models\KitchenAnalysis;
use Illuminate\Http\UploadedFile;
use Laravel\Ai\Exceptions\AiException;
use Laravel\Ai\Files\Image;

class AnalyzeKitchenPhoto
{
    public function __construct(private KitchenPhotoAnalyst $analyst) {}

    /**
     * @throws AiException
     */
    public function handle(UploadedFile $photo): KitchenAnalysis
    {
        $response = $this->analyst->prompt(
            'Analyse this photo of a kitchen.',
            attachments: [Image::fromUpload($photo)],
            provider: config('kitchen_analysis.provider'),
            model: config('kitchen_analysis.model'),
            timeout: config('kitchen_analysis.timeout'),
        );

        // Stored after the analysis, so a failing provider leaves no photo behind.
        $disk = config('kitchen_analysis.disk');

        return KitchenAnalysis::create([
            'photo_disk' => $disk,
            'photo_path' => $photo->store('kitchen-analyses', ['disk' => $disk]),
            'photo_mime_type' => $photo->getMimeType(),
            'cabinet_count' => $response['cabinet_count'],
            'drawer_count' => $response['drawer_count'],
            'estimated_wrappable_surface_m2' => $response['estimated_wrappable_surface_m2'],
            'description' => $response['description'],
            'provider' => $response->meta->provider,
            'model' => $response->meta->model,
        ]);
    }
}
