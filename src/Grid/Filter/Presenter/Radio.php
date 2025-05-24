<?php

namespace SuperAdmin\Admin\Grid\Filter\Presenter;

use Illuminate\Contracts\Support\Arrayable;
use SuperAdmin\Admin\Facades\Admin;

class Radio extends Presenter
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * Display inline.
     *
     * @var bool
     */
    protected $inline = true;

    /**
     * Radio constructor.
     *
     * @param  array  $options
     */
    public function __construct($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = (array) $options;

        return $this;
    }

    /**
     * Draw stacked radios.
     *
     * @return $this
     */
    public function stacked(): self
    {
        $this->inline = false;

        return $this;
    }

    protected function prepare()
    {
        // $script = "$('.{$this->filter->getId()}').iCheck({radioClass:'iradio_minimal-blue'});";
        // Admin::script($script);
    }

    public function variables(): array
    {
        $this->prepare();

        return [
            'options' => $this->options,
            'inline' => $this->inline,
        ];
    }
}
