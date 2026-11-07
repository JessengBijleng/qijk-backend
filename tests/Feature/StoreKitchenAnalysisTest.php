<?php

use App\Ai\Agents\KitchenPhotoAnalyst;
use App\Models\KitchenAnalysis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Exceptions\ProviderOverloadedException;
use Laravel\Ai\Prompts\AgentPrompt;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');

    KitchenPhotoAnalyst::fake([
        [
            'cabinet_count' => 12,
            'drawer_count' => 5,
            'estimated_wrappable_surface_m2' => 9.5,
            'description' => 'A white kitchen with twelve cabinet doors and five drawers.',
        ],
    ])->preventStrayPrompts();
});

it('analyses a kitchen photo and stores the result', function () {
    $response = $this->postJson('/api/v1/kitchen-analyses', [
        'photo' => UploadedFile::fake()->image('kitchen.jpg'),
    ]);

    $analysis = KitchenAnalysis::sole();

    $response->assertCreated()->assertExactJson([
        'data' => [
            'id' => $analysis->id,
            'cabinet_count' => 12,
            'drawer_count' => 5,
            'estimated_wrappable_surface_m2' => 9.5,
            'description' => 'A white kitchen with twelve cabinet doors and five drawers.',
        ],
    ]);

    expect($analysis->photo_disk)->toBe('local')
        ->and($analysis->photo_mime_type)->toBe('image/jpeg');

    Storage::disk('local')->assertExists($analysis->photo_path);
    KitchenPhotoAnalyst::assertPromptedTimes(1);
});

it('prompts the configured provider and model', function () {
    config([
        'kitchen_analysis.provider' => 'gemini',
        'kitchen_analysis.model' => 'gemini-test-model',
    ]);

    $this->postJson('/api/v1/kitchen-analyses', [
        'photo' => UploadedFile::fake()->image('kitchen.png'),
    ])->assertCreated();

    KitchenPhotoAnalyst::assertPrompted(
        fn (AgentPrompt $prompt) => $prompt->provider()->name() === 'gemini'
            && $prompt->model === 'gemini-test-model'
            && $prompt->attachments->count() === 1
    );
});

it('rejects invalid photos without calling the AI provider', function (array $payload) {
    $this->postJson('/api/v1/kitchen-analyses', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('photo');

    KitchenPhotoAnalyst::assertNeverPrompted();
    expect(KitchenAnalysis::count())->toBe(0);
})->with([
    'missing photo' => fn () => [],
    'not an image' => fn () => ['photo' => UploadedFile::fake()->create('kitchen.pdf', 100, 'application/pdf')],
    'unsupported image type' => fn () => ['photo' => UploadedFile::fake()->image('kitchen.gif')],
    'too large' => fn () => ['photo' => UploadedFile::fake()->image('kitchen.jpg')->size(15361)],
]);

it('returns a bad gateway and stores nothing when the AI provider fails', function () {
    KitchenPhotoAnalyst::fake(fn () => throw new ProviderOverloadedException('Overloaded'))->preventStrayPrompts();

    $this->postJson('/api/v1/kitchen-analyses', [
        'photo' => UploadedFile::fake()->image('kitchen.jpg'),
    ])->assertStatus(502)->assertJsonMissingPath('data');

    expect(KitchenAnalysis::count())->toBe(0)
        ->and(Storage::disk('local')->allFiles())->toBeEmpty();
});
