<p align="center">
    <a href="https://github.styleci.io/repos/257561284">
        <img src="https://github.styleci.io/repos/257561284/shield?branch=master" alt="StyleCI">
    </a>
</p>

## About

This is the backend part of my [Pizza Shop](https://github.com/itorgov/pizza-shop) project written with Laravel 7 framework.

## Installation

This is a typical Laravel application.
So, you can just follow to official [instruction](https://laravel.com/docs/7.x/installation).

### Configure MySQL

You need to create a database specific for your project, and a user to access it.
You may create a separate user, granting only specific privileges.

### Settings

1. Copy the `.env.example` file to a new file named `.env`.
2. Run `php artisan key:generate`.
3. Edit your `.env` file.
4. Run `composer install`.
5. Run `php artisan migrate`.
6. Optional. Run `php artisan db:seed` if you want scaffold database with test data.

## Tests

To run tests use `php artisan test` command.
