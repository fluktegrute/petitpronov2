<?php

namespace Tests;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Laravel\Fortify\Features;
use Livewire\Compiler\CacheManager;
use Livewire\Compiler\Compiler;
use Livewire\Factory\Factory;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF for all tests — tokens are not needed in automated tests.
        $this->withoutMiddleware(PreventRequestForgery::class);

        // Fake external HTTP calls — hCaptcha verification always succeeds in tests.
        Http::fake([
            'hcaptcha.com/*' => Http::response(['success' => true], 200),
        ]);

        // The livewire.compiler singleton hardcodes storage_path('framework/views/livewire')
        // which is owned by www-data in development. Clear and rebind to a writable tmp path.
        $cacheDir = sys_get_temp_dir() . '/livewire-test-cache';
        $cacheManager = new CacheManager($cacheDir);

        $this->app->forgetInstance('livewire.compiler');
        $this->app->forgetInstance('livewire.factory');
        $this->app->singleton('livewire.compiler', fn () => new Compiler($cacheManager));
        $this->app->singleton('livewire.factory', fn ($app) => new Factory(
            $app['livewire.finder'],
            $app['livewire.compiler'],
        ));
    }

    protected function skipUnlessFortifyHas(string $feature, ?string $message = null): void
    {
        if (! Features::enabled($feature)) {
            $this->markTestSkipped($message ?? "Fortify feature [{$feature}] is not enabled.");
        }
    }
}
