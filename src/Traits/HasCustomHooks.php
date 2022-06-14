<?php

namespace OpenAdmin\Admin\Traits;

use Closure;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

trait HasCustomHooks
{
    /**
     * @var array
     */
    protected $hooks = [];

    /**
     * Initialization closure array.
     *
     * @var []Closure
     */
    protected static $initCallbacks;

    /**
     * Initialize with user pre-defined default disables, etc.
     *
     * @param Closure $callback
     */
    public static function init(Closure $callback = null)
    {
        static::$initCallbacks[] = $callback;
    }

    /**
     * Call the initialization closure array in sequence.
     */
    protected function callInitCallbacks()
    {
        if (empty(static::$initCallbacks)) {
            return;
        }

        foreach (static::$initCallbacks as $callback) {
            $callback($this);
        }
    }

    /**
     * Check has hook.
     *
     * @param string  $name
     * @param Closure $callback
     *
     * @return $this
     */
    protected function hasHooks($name)
    {
        return !empty($this->hooks[$name]);
    }

    /**
     * Register a hook.
     *
     * @param string  $name
     * @param Closure $callback
     *
     * @return $this
     */
    protected function hook($name, Closure $callback)
    {
        $this->registerHook($name, $callback);

        return $this;
    }

    /**
     * Register a hook.
     *
     * @param string  $name
     * @param Closure $callback
     *
     * @return $this
     */
    protected function registerHook($name, Closure $callback)
    {
        $this->hooks[$name][] = $callback;

        return $this;
    }

    /**
     * Call hooks by giving name.
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return Response
     */
    protected function callHooks($name, $parameters = [])
    {
        $hooks = Arr::get($this->hooks, $name, []);

        foreach ($hooks as $func) {
            if (!$func instanceof Closure) {
                continue;
            }

            return call_user_func($func, $this, $parameters);
        }
    }
}
