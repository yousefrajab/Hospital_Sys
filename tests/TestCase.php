<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Feature tests wipe the database. Refuse to run against anything but the test database
        // (a cached config, for example, would otherwise point them at the real one).
        $database = config('database.connections.' . config('database.default') . '.database');
        if ($database !== 'hos_db_test') {
            throw new RuntimeException("Tests must run on hos_db_test, not [{$database}]. Run `php artisan config:clear`.");
        }
    }
}
