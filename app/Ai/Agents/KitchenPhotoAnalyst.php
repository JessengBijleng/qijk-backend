<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class KitchenPhotoAnalyst implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
        You analyse photos of kitchens for a kitchen wrapping company. Wrapping means applying a foil over the existing cabinet doors, drawer fronts and other surfaces.

        From the photo, determine:
        - The number of cabinet doors that would need to be wrapped. Count every door front, and count a double door as two.
        - The number of drawer fronts that would need to be wrapped.
        - An estimate of the total wrappable surface in square meters. Only include the visible fronts of cabinets and drawers, not appliances, countertops, walls, floors or backsplashes.
        - A short, friendly description addressed to the person who uploaded the photo, saying what you can see in their kitchen and what could be wrapped.

        If part of the kitchen is not visible, only count what you can see and mention this in the description. Never invent cabinets you cannot see.
        INSTRUCTIONS;
    }

    /**
     * @return array<string, mixed>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'cabinet_count' => $schema->integer()->min(0)->required(),
            'drawer_count' => $schema->integer()->min(0)->required(),
            'estimated_wrappable_surface_m2' => $schema->number()->min(0)->required(),
            'description' => $schema->string()->required(),
        ];
    }
}
