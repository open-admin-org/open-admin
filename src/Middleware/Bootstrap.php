<?php

namespace OpenAdmin\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Facades\Admin;

class Bootstrap
{
    public function handle(Request $request, Closure $next)
    {
        Admin::bootstrap();

        return $next($request);
    }
}
