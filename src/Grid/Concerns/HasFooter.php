<?php

namespace OpenAdmin\Admin\Grid\Concerns;

use Closure;
use OpenAdmin\Admin\Grid\Tools\Footer;

trait HasFooter
{
    /**
     * @var Closure
     */
    protected $footer;

    public $fixedFooter = true;

    /**
     * Set footer fixed.
     *
     * @param bool
     *
     * @return $this|Closure
     */
    public function fixedFooter($bool = true)
    {
        $this->fixedFooter = $bool;

        return $this;
    }

    /**
     * Set grid footer.
     *
     * @param Closure|null $closure
     *
     * @return $this|Closure
     */
    public function footer(Closure $closure = null)
    {
        if (!$closure) {
            return $this->footer;
        }

        $this->footer = $closure;

        return $this;
    }

    /**
     * Render grid footer.
     *
     * @return string
     */
    public function renderFooter()
    {
        if (!$this->footer) {
            return '';
        }

        return (new Footer($this))->render();
    }
}
