<?php

namespace App\Http\Resources;

use App\Models\KitchenAnalysis;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin KitchenAnalysis
 */
class KitchenAnalysisResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cabinet_count' => $this->cabinet_count,
            'drawer_count' => $this->drawer_count,
            'estimated_wrappable_surface_m2' => $this->estimated_wrappable_surface_m2,
            'description' => $this->description,
        ];
    }
}
