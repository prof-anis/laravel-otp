<?php

namespace Tobexkee\LaravelOtp\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tobexkee\LaravelOtp\LaravelOtpServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            LaravelOtpServiceProvider::class
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
