## Purpose

This repository is a minimal PHP backend. The entire app is currently a single entrypoint at `index.php` which prints a simple message.

Keep changes small and runnable: this project has no framework, no Composer manifest, and no tests detected. The agent's goal is to keep the codebase executable with `php` and avoid introducing non-standard tooling unless the PR adds the required config files (for example, `composer.json`).

## Key files

- `index.php` — single entrypoint. Example content: `echo "Hello, World! This is the index.php file.";`

## What to change and how to verify

- Small feature or bug fix: edit `index.php` or add new PHP files and include them from `index.php`.
- Adding dependencies: create `composer.json` and vendor directory; document install steps in `README.md` and update these instructions.

Quick verification commands (assume PHP is installed):

- Syntax check: `php -l index.php`
- Run a local dev server in project root: `php -S 0.0.0.0:8000 -t .`
- Smoke test by visiting `http://localhost:8000` or `curl http://localhost:8000` after starting the server.

## Project-specific conventions (observed)

- No framework: code should be plain PHP and self-contained.
- No package manager detected: avoid introducing dependencies without adding `composer.json` and documenting install steps.

## Editing / PR guidance for an AI coding agent

1. Preserve behavior: run `php -l` after edits and ensure the dev server returns the expected output.
2. Keep changes minimal and well-scoped — this repo currently demonstrates a single-purpose PHP script.
3. If adding tests or CI, include config files (for example, `composer.json`, `phpunit.xml`, or a `.github/workflows/*` workflow) in the same PR and document how to run them in the `README.md`.
4. Do not assume hidden infra (databases, env vars, external APIs) unless new files or configs indicate them.

## When unsure

- Ask the maintainer whether they want a minimal enhancement (keep single-file app) or a scaffolded project (Composer, PSR-4 structure, tests). Provide both options in the PR description if you propose a scaffold.

---

If anything in these instructions is unclear or missing, tell me which area you'd like expanded (run/debug commands, suggested scaffolding, or CI examples) and I will update the file.
