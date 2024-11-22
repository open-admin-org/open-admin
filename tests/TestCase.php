<?php

use Orchestra\Testbench\Concerns\WithWorkbench;

class TestCase extends \Orchestra\Testbench\Dusk\TestCase
{
    use WithWorkbench;
    protected static $baseServeHost = '127.0.0.1';
    protected static $baseServePort = 8001;

}
