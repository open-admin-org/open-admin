<?php

use SuperAdmin\Admin\Auth\Database\Administrator;
use SuperAdmin\Admin\Auth\Database\Menu;

class MenuTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function test_menu_index()
    {
        $this->visit('admin/auth/menu')
            ->see('Menu')
            ->see('Index')
            ->see('Auth')
            ->see('Users')
            ->see('Roles')
            ->see('Permission')
            ->see('Menu');
    }

    public function test_add_menu()
    {
        $item = ['parent_id' => '0', 'title' => 'Test', 'uri' => 'test'];

        $this->visit('admin/auth/menu')
            ->seePageIs('admin/auth/menu')
            ->see('Menu')
            ->submitForm('Submit', $item)
            ->seePageIs('admin/auth/menu')
            ->seeInDatabase(config('admin.database.menu_table'), $item)
            ->assertEquals(8, Menu::count());

        //        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);
        //
        //        $this->visit('admin')
        //            ->see('Test')
        //            ->click('Test');
    }

    public function test_delete_menu()
    {
        $this->delete('admin/auth/menu/8')
            ->assertEquals(7, Menu::count());
    }

    public function test_edit_menu()
    {
        $this->visit('admin/auth/menu/1/edit')
            ->see('Menu')
            ->submitForm('Submit', ['title' => 'blablabla'])
            ->seePageIs('admin/auth/menu')
            ->seeInDatabase(config('admin.database.menu_table'), ['title' => 'blablabla'])
            ->assertEquals(7, Menu::count());
    }

    public function test_show_page()
    {
        $this->visit('admin/auth/menu/1/edit')
            ->seePageIs('admin/auth/menu/1/edit');
    }

    public function test_edit_menu_parent()
    {
        $this->expectException(\Laravel\BrowserKitTesting\HttpException::class);

        $this->visit('admin/auth/menu/5/edit')
            ->see('Menu')
            ->submitForm('Submit', ['parent_id' => 5]);
    }
}
