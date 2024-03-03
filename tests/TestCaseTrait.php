<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use OpenAdmin\Admin\AdminServiceProvider;
use OpenAdmin\Admin\Facades\Admin;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Workbench\App\Admin\Controllers\AuthController;

use function Orchestra\Testbench\workbench_path;

trait TestCaseTrait
{
    use WithWorkbench;

    protected function getPackageProviders($app): array
    {
        return [
            AdminServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Admin' => Admin::class,
        ];
    }

    public function ignorePackageDiscoveriesFrom(): array
    {
        return [];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }

    protected function defineEnvironment($app): void
    {
        tap($app['config'], $this->setupConfig());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $setupConfig = $this->setupConfig();
        $this->beforeServingApplication(fn (Application $app, Repository $config) => $setupConfig($config));
    }

    protected function setupConfig(): callable
    {
        return static function (Repository $config) {
            $config->set('filesystems.disks.admin', [
                'driver'     => 'local',
                'root'       => public_path('uploads'),
                'visibility' => 'public',
                'url'        => 'http://localhost:8000/uploads/',
            ]);
            $config->set('admin.auth.controller', AuthController::class);
            $config->set('admin.directory', __DIR__.'/../workbench/app/Admin');
            $config->set('admin.bootstrap', __DIR__.'/../workbench/app/Admin/bootstrap.php');
        };
    }
}
