<?php

namespace OpenAdmin\Admin\Form\Field;

class Email extends Text
{
    protected $rules = 'nullable|email';

    public function render()
    {
        $this->prepend('<i class="icon-envelope fa-fw"></i>')
            ->defaultAttribute('type', 'email');

        return parent::render();
    }
}
