<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Workbench\App\Models\Tree;

class ModelTreeTest extends DuskTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testSelectOptions()
    {
        $rootText = 'Root Text';

        $options = Tree::selectOptions(function ($query) {
            return $query->where('uri', '');
        }, $rootText);

        $count = Tree::query()->where('uri', '')->count();

        $this->assertEquals(array_shift($options), $rootText);
        $this->assertEquals(count($options), $count);
    }
}
