<?php

namespace OpenAdmin\Admin\Form\Field;

class CheckboxButton extends Checkbox
{
    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addCascadeScript();

        $this->addVariables([
            'options' => $this->options,
            'checked' => $this->checked,
        ]);

        return parent::fieldRender();
    }
}
