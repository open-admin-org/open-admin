<?php

namespace SuperAdmin\Admin\Widgets\Navbar;

use Illuminate\Contracts\Support\Renderable;
use SuperAdmin\Admin\Admin;

class RefreshButton implements Renderable
{
    public function render()
    {
        return Admin::component('admin::components.refresh-btn');
    }
}
