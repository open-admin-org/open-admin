<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    public function testLoginPage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('admin/auth/login')
                ->assertSee('Login');
        });
    }

    public function testVisitWithoutLogin(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('admin')
                ->assertGuest()
                ->assertPathIs('/admin/auth/login');
        });
    }

    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('admin/auth/login')
                ->assertSee('Login')
                ->type('username', 'admin')
                ->type('password', 'admin')
                ->press('Login')
                ->assertSee('Dashboard')
                ->assertAuthenticated('admin')
                ->assertPathIs('/admin')
                ->assertSee('Dashboard')
                ->assertSee('Description...')
                ->assertSee('Environment')
                ->assertSee('PHP version')
                ->assertSee('Laravel version')
                ->assertSee('Available extensions')
                ->assertSeeLink('open-admin-ext/helpers', 'https://github.com/open-admin-extensions/helpers')
                ->assertSeeLink('open-admin-ext/backup', 'https://github.com/open-admin-extensions/backup')
                ->assertSeeLink('open-admin-ext/media-manager', 'https://github.com/open-admin-extensions/media-manager')
                ->assertSee('Dependencies')
                ->assertSee('laravel/framework')
                ->assertSee('Admin')
                ->click("li.treeview > a.has-subs")
                ->assertSeeLink( 'Users')
                ->assertSeeLink('Roles')
                ->assertSeeLink('Permission')
                ->assertSeeLink('Menu')
                ->assertSeeLink('Operation log');
        });
    }

    public function testLogout(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('admin/auth/logout')
                ->assertPathIs('/admin/auth/login')
                ->assertGuest();
        });
    }
}
