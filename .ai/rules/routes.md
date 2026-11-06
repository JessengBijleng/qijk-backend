---
paths:
  - 'routes/**'
---

# Routes

## One route file per category
Do not grow one big routes file. Put routes in a separate file per category in `routes/`, named after it: `users.php` holds all user routes, `products.php` all product routes. Keep `web.php` (and `api.php`) as thin entry points that only `require` the category files, so they inherit the right middleware group. Point every route at a single action controller.
