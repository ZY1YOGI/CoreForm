<?php

namespace Form\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Form\FormServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            FormServiceProvider::class,
        ];
    }
}
