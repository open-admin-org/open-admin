<!-- Main Header -->
<header class="custom-navbar navbar navbar-light bg-white p-0 align-items-stretch">
    <a class="navbar-brand menu-width container-md bg-semi-dark text-center" href="{{ admin_url('/') }}">
        <span class="short">{!! config('admin.logo-mini', config('admin.name')) !!}</span><span class="long">{!! config('admin.logo', config('admin.name')) !!}</span>
    </a>
    <div class="d-flex flex-fill flex-wrap header-items">

        <a class="flex-shrink order-1 order-sm-0 valign-header px-4 link-secondary" type="button" id='menu-toggle' aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="icon-bars"></i>
        </a>

        <ul class="nav navbar-nav hidden-sm visible-lg-block">
            {!! Admin::getNavbar()->render('left') !!}
        </ul>

        <div class="flex-fill search order-0 order-sm-1" style="display:none;">
            <input class="form-control" type="text" placeholder="Search" aria-label="Search">
        </div>

        <ul class="nav order-2 ms-auto d-flex align-items-center">

            {!! Admin::getNavbar()->render() !!}

            <li class="nav-item">
                <div class="dropdown user-menu d-flex align-items-center px-3" href="#" role="button" id="user-menu-link" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="bg-light inline rounded-circle user-image">
                        <img src="{{ Admin::user()->avatar }}" alt="User Image">
                    </span>
                    <span class="hidden-xs">{{ Admin::user()->name }}</span>
                </div>
                <ul class="dropdown-menu dropdown-menu-end user-menu" aria-labelledby="user-menu-link">
                    <!-- The user image in the menu -->
                    <li class="user-header text-center bg-semi-dark p-3">
                        <span class="bg-light inline rounded-circle user-image medium">
                            <img src="{{ Admin::user()->avatar }}" alt="User Image">
                        </span>
                        <p>
                            <h2>{{ Admin::user()->name }}</h2>
                            <small>Member since admin {{ Admin::user()->created_at }}</small>
                        </p>
                    </li>
                    <li class="user-footer p-2 clearfix">
                        <div class="float-start">
                            <a href="{{ admin_url('auth/setting') }}" class="btn btn-secondary">{{ __('admin.setting') }}</a>
                        </div>
                        <div class="float-end">
                            <a href="{{ admin_url('auth/logout') }}" class="btn no-ajax btn-secondary">{{ __('admin.logout') }}</a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>
