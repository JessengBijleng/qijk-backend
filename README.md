# qijk backend

The API behind the [qijk widget](https://github.com/JessengBijleng/Qijk-widget). Customers embed the widget on their websites so their visitors can see what their own kitchen would look like with a kitchen wrap. This backend:

- generates those visualizations with an AI,
- authorizes that a widget is allowed to use the API,
- stores the requests visitors make.

## Getting started

```sh
composer setup
composer dev
php artisan test --compact
```

## Docs

- [docs/overview.md](docs/overview.md): goal, roles, responsibilities, constraints, glossary, decisions, open questions.
- [docs/development.md](docs/development.md): stack, scripts, project structure, conventions.
