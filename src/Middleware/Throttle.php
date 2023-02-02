<?php

namespace OpenAdmin\Admin\Middleware;

use Closure;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\RateLimiter;
use OpenAdmin\Admin\Facades\Admin;

class Throttle
{
    protected $loginView = 'admin::login';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // throttle this
        if (Admin::guard()->guest() && config('admin.auth.throttle_logins')) {
            $throttle_attempts = config('admin.auth.throttle_attempts', 5);
            if (RateLimiter::tooManyAttempts('login-tries-'.Admin::guardName(), $throttle_attempts)) {
                $errors = new \Illuminate\Support\MessageBag();
                $errors->add('attempts', $this->getToManyAttemptsMessage());

                return response()->view($this->loginView, ['errors'=>$errors], 429);
            }
        }

        return $next($request);
    }

    protected function getToManyAttemptsMessage()
    {
        return Lang::has('auth.to_many_attempts')
            ? trans('auth.to_many_attempts')
            : 'To many attempts!';
    }
}
