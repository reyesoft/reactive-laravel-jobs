<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of ReactiveLaravelJobs. ReactiveLaravelJobs can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace Tests;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;
use Orchestra\Testbench\Database\MigrateProcessor;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * @internal
 *
 * @property TestResponse $response
 */
abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // $this->loadMigrationsFrom(__DIR__ . '/../dummy/database/migrations');

        $this->loadDummyAppRoutes();
    }

    protected function tearDown(): void
    {
        $this->response = new TestResponse(new Response());
    }

    /**
     * remove on future, just try to run phpunit without this function.
     *
     * @see https://github.com/nunomaduro/larastan/issues/376
     */
    public function getAnnotations()
    {
        return [];
    }

    private function loadDummyAppRoutes(): self
    {
        Route::group(
            [
                'namespace' => 'DummyApp\\Http\\Controllers',
                'middleware' => [
                ],
                'prefix' => 'v2',
            ],
            function (): void {
                include __DIR__ . '/../dummy/routes/api.php';
            }
        );

        return $this;
    }

    protected function loadMigrationsFrom($paths): void
    {
        $options = is_array($paths) ? $paths : ['--path' => $paths];

        if (isset($options['--realpath']) && is_string($options['--realpath'])) {
            $options['--path'] = [$options['--realpath']];
        }

        $options['--realpath'] = true;

        $migrator = new MigrateProcessor($this, $options);

        Artisan::call('migrate:status', ['--env' => 'testing']);
        $artisan_output = Artisan::output();
        if (preg_match('/^\| N|^No migrations|^Migration table not found/is', $artisan_output) > 0) {
            fwrite(STDERR, ' ⌚ Migrating and seeding...' . PHP_EOL);

            $migrator->up();
            $this->artisan('db:seed');

            fwrite(STDERR, ' ✓ Done!' . PHP_EOL);
        } elseif ($artisan_output === '') {
            throw new Exception('Unexpected response calling migrate:status on loadMigrationsFrom() function.');
        }
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $filename = '/tmp/database-reactive-laravel-jobs.sqlite';
        if (!file_exists($filename)) {
            file_put_contents($filename, '');
        }

        $app->make('config')->set('database.default', 'testbench');
        $app->make('config')->set(
            'database.connections.testbench',
            [
                'driver' => 'sqlite',
                'database' => $filename,
                // 'database' => ':memory:',
                'prefix' => '',
            ]
        );
        /*
        $app->make('config')->set(
            'reactive-laravel-jobs',
            include 'config/reactive-laravel-jobs.php'
        );
        */
    }

    /**
     * @param Application $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            // \DummyApp\Providers\DummyServiceProvider::class,
            // ServiceProvider::class,
            // Service::class,
        ];
    }

    /**
     * @param Application $app
     */
    /*
    protected function resolveApplicationExceptionHandler($app): void
    {
      $app->singleton(ExceptionHandler::class, TestExceptionHandler::class);
    }
    */
}
