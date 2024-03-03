<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class LaravelTest extends DuskTestCase
{
    public function testLaravel()
    {
        $this->visit('/')
            ->assertResponseStatus(200)
            ->see('Laravel');
    }
}
