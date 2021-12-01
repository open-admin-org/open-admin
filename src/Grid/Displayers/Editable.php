<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use Illuminate\Support\Arr;

class Editable extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * Type of editable.
     *
     * @var string
     */
    protected $type = '';

    /**
     * Options of editable function.
     *
     * @var array
     */
    protected $options = [
        'emptytext'  => '<i class="icon-pencil"></i>',
    ];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Add options for editable.
     *
     * @param array $options
     */
    public function addOptions($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Add attributes for editable.
     *
     * @param array $attributes
     */
    public function addAttributes($attributes = [])
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    /**
     * @param array $arguments
     */
    protected function buildEditableOptions(array $arguments = [])
    {
        $this->type = Arr::get($arguments, 0, 'text');

        call_user_func_array([$this, $this->type], array_slice($arguments, 1));
    }

    /**
     * @return string
     */
    public function display()
    {
        $this->options['name'] = $column = $this->getName();

        $class = 'grid-editable-'.str_replace(['.', '#', '[', ']'], '-', $column);

        $this->buildEditableOptions(func_get_args());

        $options = json_encode($this->options);

        $class = '\OpenAdmin\Admin\Grid\Displayers\\'.ucfirst($this->type);
        $displayer = new $class($this->value, $this->grid, $this->column, $this->row);
        $displayer->options = $this->options;

        return $displayer->display();
    }
}
