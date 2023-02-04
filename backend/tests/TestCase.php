<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestHelpers\TestHelperTrait;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected bool $seed = true;
}
