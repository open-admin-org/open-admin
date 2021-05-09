<?php

use OpenAdmin\Admin\Auth\Database\Administrator;

class IndexTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function testIndex()
    {
        $this->visit('admin/')
            ->see('Dashboard')
            ->see('Description...')

            ->see('Environment')
            ->see('PHP version')
            ->see('Laravel version')

            ->see('Available extensions')
            ->seeLink('open-admin-ext/helpers', 'https://github.com/open-admin-extensions/helpers')
            ->seeLink('open-admin-ext/backup', 'https://github.com/open-admin-extensions/backup')
            ->seeLink('open-admin-ext/media-manager', 'https://github.com/open-admin-extensions/media-manager')

            ->see('Dependencies')
            ->see('php')
//            ->see('>=7.0.0')
            ->see('laravel/framework');
    }

    public function testClickMenu()
    {
        $this->visit('admin/')
            ->click('Users')
            ->seePageis('admin/auth/users')
            ->click('Roles')
            ->seePageis('admin/auth/roles')
            ->click('Permission')
            ->seePageis('admin/auth/permissions')
            ->click('Menu')
            ->seePageis('admin/auth/menu')
            ->click('Operation log')
            ->seePageis('admin/auth/logs');
    }
}
