<?php

namespace OpenAdmin\Admin\Form\Field;

class Fieldset
{
    protected $name = '';

    public function __construct()
    {
        $this->name = uniqid('fieldset-');
    }

    public function start($title)
    {
        return <<<HTML
<div>
    <div class="fieldset">
        <a data-bs-toggle="collapse" href="#{$this->name}" class="{$this->name}-title fieldset-link collapsed">
        <i class="icon-angle-up"></i>&nbsp;&nbsp;{$title}
        </a>
    </div>
    <div class="collapse in" id="{$this->name}">
HTML;
    }

    public function end()
    {
        return '</div></div>';
    }
}
