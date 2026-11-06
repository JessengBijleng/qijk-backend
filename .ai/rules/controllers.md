---
paths:
  - 'app/Http/Controllers/**'
---

# Controllers

## Use single action controllers
Every controller is a single action controller with exactly one public `__invoke` method; do not create resource or multi-method controllers. Name it after the action it performs (e.g. `StoreProductController`) and generate it with `php artisan make:controller --invokable`. Register it as `Route::post('/products', StoreProductController::class)`.

## Delegate reusable logic to Actions
Keep controllers thin: handle the request, authorization and response. Logic that is or could be reused (by other controllers, jobs, commands or listeners) belongs in an Action class in `app/Actions`, injected into `__invoke` and called from there.
