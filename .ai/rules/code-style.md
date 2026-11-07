---
paths:
  - 'app/**'
  - 'config/**'
  - 'database/**'
  - 'routes/**'
  - 'tests/**'
---

# Code style

## Let the code explain itself
Express intent through class, method and variable names, not through comments. A reader should understand what the code does and why by reading it. Do not add a docblock that repeats the name or signature (`/** Handle the incoming request. */`, `/** Get the attributes that should be cast. */`), and remove such docblocks from generated boilerplate (migrations, controllers, factories, resources). Prefer renaming or extracting a well-named method over explaining with a comment.

Only add a comment when the intent cannot be captured by naming or structure, for example a non-obvious reason, a constraint from outside the code, or a decision that is still open. Keep it short and explain why, not what.

A PHPDoc block is fine when it carries type information the signature can't express (generics, array shapes, `@mixin`, `@throws` for exceptions callers are expected to handle). Leave out the description line in those blocks.

This overrides the general "prefer PHPDoc blocks over inline comments" guidance in the generated `CLAUDE.md` and `AGENTS.md`.
