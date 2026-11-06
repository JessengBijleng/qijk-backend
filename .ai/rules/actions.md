---
paths:
  - 'app/Actions/**'
---

# Actions

## Use Action classes for reusable logic
Reusable business logic lives in Action classes in `app/Actions`. One action per class, named verb + noun (e.g. `CreateUser`), exposing a single public `handle()` method with typed parameters and return type. Inject dependencies through the constructor and resolve actions from the container (constructor or method injection), never with `new`. Generate with `php artisan make:class`.
