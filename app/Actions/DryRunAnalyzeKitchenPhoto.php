<?php

namespace App\Actions;

use App\Models\KitchenAnalysis;
use Illuminate\Http\UploadedFile;

class DryRunAnalyzeKitchenPhoto extends AnalyzeKitchenPhoto
{
    public function __construct() {}

    public function handle(UploadedFile $photo): KitchenAnalysis
    {
        return (new KitchenAnalysis)->forceFill([
            'id' => '00000000000000000000000000',
            'cabinet_count' => 12,
            'drawer_count' => 5,
            'estimated_wrappable_surface_m2' => 9.5,
            'description' => 'Dry run: a white kitchen with twelve cabinet doors and five drawers.',
        ]);
    }
}
