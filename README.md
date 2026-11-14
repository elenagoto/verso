# 📖 Verso

A small content manager built with Laravel: authors write articles, admins manage users. The scope is kept deliberately small, so the focus stays on clean Laravel conventions and on testing.

> Work in progress. See [Status](#status) for what is done and what comes next.

## Features

- **Articles** with a title, slug, content and an optional image.
    - Status stored as a backed enum (`draft`, `published`, `archived`) and cast on the model.
    - A `published` query scope, so only published articles appear publicly.
    - Soft deletes: a deleted article is hidden, not erased.
- **Users** with a role stored as a backed enum (`admin`, `editor`).
    - Every user can edit their own profile.
    - Admins can list all users (with their article count) and change other users' roles. An admin cannot change their own role, so the app can never be left without an admin.
- **Authorisation** through policies, so the rules live in one place and controllers, form requests and views all ask the same question.
- **Validation** through form requests, so controllers only receive data that is already valid and authorised.

## Stack

|           |                                                   |
| --------- | ------------------------------------------------- |
| Language  | PHP 8.4                                           |
| Framework | Laravel 13                                        |
| Database  | MySQL 8.4 (application), SQLite in memory (tests) |
| Tests     | PHPUnit 12                                        |

## Design notes

- **Roles are an enum, not a table.** A role decides what the code allows, so adding one always means changing the code (the policies). Keeping roles in an enum keeps that knowledge in one place and avoids comparing against names stored in the database.
- **Factories default to the least privileged state.** `User::factory()` creates an editor; tests ask for an admin explicitly with `User::factory()->admin()`. Nothing that affects authorisation is random, so tests are deterministic.
- **Strict models outside production.** `Model::shouldBeStrict()` makes a typo in an attribute name or an accidental N+1 query throw an exception in development and tests, instead of failing silently.
- **Tests run on SQLite, the app on MySQL.** This is fine for the features used here (string-backed enums, soft deletes, scopes, policies). Database-specific SQL, such as some JSON or full-text queries, could behave differently on the two.

## Running it locally

Requirements: PHP 8.4, Composer, Node.js and MySQL, or any Docker setup that provides them.

```bash
git clone https://github.com/elenagoto/verso.git
cd verso
composer install
cp .env.example .env
php artisan key:generate
```

Set the `DB_*` values in `.env` for your MySQL database, then:

```bash
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

The seeder creates an admin account: `test@example.com`, password `password`. It is for local development only.

## Tests

```bash
php artisan test
```

Tests use SQLite in memory (set in `phpunit.xml`), so they never touch the development database.

## Status

Done:

- Models, migrations, enums, factories and seeder
- Article and user controllers
- Form requests and the user policy

Next:

- Routes and login
- Article policy (only the author or an admin can edit)
- Blade views
- Test suite
- Continuous integration with GitHub Actions
- Deployment

## License

MIT
