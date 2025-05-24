<?php

namespace SuperAdmin\Admin\Form\Field;

use SuperAdmin\Admin\Form;

class Email extends Text
{
    protected $rules = 'nullable|email';

    public function setForm(?Form $form = null)
    {
        $this->form = $form;
        // field type url has a default browser validation
        $this->form->enableValidate();

        return $this;
    }

    public function render()
    {
        $this->prepend('<i class="icon-envelope fa-fw"></i>')
            ->defaultAttribute('type', 'email');

        return parent::render();
    }
}
