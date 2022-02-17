<?php

namespace OpenAdmin\Admin\Form\Field;

use OpenAdmin\Admin\Form;

class Url extends Text
{
    protected $rules = 'nullable|url';

    public function setForm(Form $form = null)
    {
        $this->form = $form;
        // field type url has a default browser validation
        $this->form->enableValidate();

        return $this;
    }

    public function render()
    {
        $this->prepend('<i class="icon-link"></i>')
            ->defaultAttribute('type', 'url');

        return parent::render();
    }
}
