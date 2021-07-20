<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Contracts\Support\Arrayable;
use OpenAdmin\Admin\Form\Field;

class Radio extends Field
{
    use CanCascadeFields;

    protected $inline = true;

    /*
    protected static $css = [
        '/vendor/open-admin/AdminLTE/plugins/iCheck/all.css',
    ];

    protected static $js = [
        '/vendor/open-admin/AdminLTE/plugins/iCheck/icheck.min.js',
    ];
    */

    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    /**
     * Set options.
     *
     * @param array|callable|string $options
     *
     * @return $this
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = (array) $options;

        return $this;
    }

    /**
     * Set checked.
     *
     * @param array|callable|string $checked
     *
     * @return $this
     */
    public function checked($checked = [])
    {
        if ($checked instanceof Arrayable) {
            $checked = $checked->toArray();
        }

        // input radio checked should be unique
        $this->checked = is_array($checked) ? (array) end($checked) : (array) $checked;

        return $this;
    }

    /**
     * Draw inline radios.
     *
     * @return $this
     */
    public function inline()
    {
        $this->inline = true;

        return $this;
    }

    /**
     * Draw stacked radios.
     *
     * @return $this
     */
    public function stacked()
    {
        $this->inline = false;

        return $this;
    }

    /**
     * Set options.
     *
     * @param array|callable|string $values
     *
     * @return $this
     */
    public function values($values)
    {
        return $this->options($values);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        //$this->script = "$('{$this->getElementClassSelector()}').iCheck({radioClass:'iradio_minimal-blue'});";

        $this->addCascadeScript();

        $this->addVariables(['options' => $this->options, 'checked' => $this->checked, 'inline' => $this->inline]);

        return parent::render();
    }
}
