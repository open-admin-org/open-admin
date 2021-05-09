<?php

namespace OpenAdmin\Admin\Form\Field;

class Number extends Text
{
    protected static $js = [
        '/vendor/open-admin/bootstrap5/plugins/number-input.js',
    ];

    protected $view = 'admin::form.number';

    public function render()
    {

        $this->defaultAttribute('type', 'number');
        $this->append("<i class='icon-plus plus'></i>");
        $this->prepend("<i class='icon-minus minus'></i>");
        $this->default($this->default);
        $this->script = <<<EOT
        new NumberInput(document.querySelector('{$this->getElementClassSelector()}'));
        EOT;

        $this->style("max-width","120px");

        return parent::render();
    }

    /**
     * Set min value of number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function min($value)
    {
        $this->attribute('min', $value);

        return $this;
    }

    /**
     * Set max value of number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function max($value)
    {
        $this->attribute('max', $value);

        return $this;
    }

    /**
     * Set max value of number field.
     *
     * @param int $value
     *
     * @return $this
     */
    public function step($value)
    {
        $this->attribute('step', $value);

        return $this;
    }
}
