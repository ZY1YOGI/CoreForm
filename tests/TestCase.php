<?php

namespace Core\Form\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Core\Form\FormServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            FormServiceProvider::class,
        ];
    }
}
