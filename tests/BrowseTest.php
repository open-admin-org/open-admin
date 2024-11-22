<?php

use Laravel\Dusk\Browser;

class BrowseTest extends TestCase
{
    public function testBrowse()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/welcome');
            sleep(5);
            $browser->assertSee('Documentation');
        });

    }
}
