<?php
// tests/Feature/SuperbanTest.php

namespace Abdulkadir\Superban\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Abdulkadir\Superban\Middleware\SuperbanMiddleware;
use Abdulkadir\Superban\Tests\TestCase;

class SuperbanTest extends TestCase
{
    use RefreshDatabase; 

    public function it_bans_user_for_exceeding_request_threshold()
    {
        Cache::shouldReceive('get')
            ->once()
            ->with('superban:ip:192.168.0.1:test-route', 0)
            ->andReturn(200);

        Cache::shouldReceive('put')
            ->once()
            ->with('superban:ip:192.168.0.1', true, \Mockery::type(\DateTimeInterface::class))
            ->andReturnTrue();

        $request = Request::create('/test-route', 'GET');
        $request->headers->set('X-Forwarded-For', '192.168.0.1');

        Route::get('/test-route', function () {
            return 'Test route';
        })->middleware(SuperbanMiddleware::class);

        $response = $this->actingAs($this->createTestUser())->get('/test-route');

        $response->assertStatus(403);
        $this->assertEquals(true, Cache::get('superban:ip:192.168.0.1'));
    }

    public function it_installs_superban_package_and_publishes_configuration()
    {
        $this->artisan('superban:install')->expectsOutput('Superban package installed successfully.');

        $this->assertFileExists(config_path('superban.php'));
    }
}
