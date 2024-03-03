<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use Orchestra\Testbench\Dusk\Options;

if (class_exists(Options::class)) {
    Options::withoutUI();
    Options::addArgument('--enable-file-cookies');
}
