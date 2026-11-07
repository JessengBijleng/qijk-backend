<?php

namespace App\Models;

use Database\Factories\KitchenAnalysisFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenAnalysis extends Model
{
    /** @use HasFactory<KitchenAnalysisFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'photo_disk',
        'photo_path',
        'photo_mime_type',
        'cabinet_count',
        'drawer_count',
        'estimated_wrappable_surface_m2',
        'description',
        'provider',
        'model',
    ];

    protected function casts(): array
    {
        return [
            'cabinet_count' => 'integer',
            'drawer_count' => 'integer',
            'estimated_wrappable_surface_m2' => 'float',
        ];
    }
}
