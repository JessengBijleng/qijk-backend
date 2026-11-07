<?php

return [

    // A key from config/ai.php. An empty model uses the provider's default text model.
    'provider' => env('KITCHEN_ANALYSIS_PROVIDER', 'openai'),

    'model' => env('KITCHEN_ANALYSIS_MODEL'),

    'timeout' => (int) env('KITCHEN_ANALYSIS_TIMEOUT', 60),

    // Photos are personal data, so this disk must stay private.
    'disk' => env('KITCHEN_ANALYSIS_DISK', env('FILESYSTEM_DISK', 'local')),

];
