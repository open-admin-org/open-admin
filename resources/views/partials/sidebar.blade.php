<aside id="sidebar" class="menu-width sidebar bg-semi-dark">

    @if(config('admin.enable_user_panel'))
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{ Admin::user()->avatar }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{{ Admin::user()->name }}</p>
            <!-- Status -->
            <a href="#"><i class="icon-circle text-success"></i> {{ trans('admin.online') }}</a>
        </div>
    </div>
    @endif

    @if(config('admin.enable_menu_search'))
    <!-- search form (Optional) -->
    <form class="sidebar-form" style="overflow: initial;" onsubmit="return false;">
        <div class="input-group">
            <input type="text" autocomplete="off" class="form-control autocomplete" placeholder="Search...">
            <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="icon-search"></i></button>
            </span>
            <ul class="dropdown-menu" role="menu" style="min-width: 210px;max-height: 300px;overflow: auto;">
                @foreach(Admin::menuLinks() as $link)
                <li>
                    <a href="{{ admin_url($link['uri']) }}"><i class="{{ $link['icon'] }}"></i>{{ admin_trans($link['title']) }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </form>
    <!-- /.search form -->
    @endif

    <nav>

        <div class="custom-menu">
            <ul class="list-unstyled ps-0 root" id="menu">
                @each('admin::partials.menu', Admin::menu(), 'item')
            </ul>
        </div>
    </nav>
</aside>
