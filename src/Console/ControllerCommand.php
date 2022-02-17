<?php

namespace OpenAdmin\Admin\Console;

class ControllerCommand extends MakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:controller {model}
        {--title=}
        {--name=}
        {--stub= : Path to the custom stub file. }
        {--namespace=}
        {--O|output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make admin controller (simular to admin:make)';
}
