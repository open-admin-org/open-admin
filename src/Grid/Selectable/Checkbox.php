<?php

namespace SuperAdmin\Admin\Grid\Selectable;

use SuperAdmin\Admin\Grid\Displayers\AbstractDisplayer;

class Checkbox extends AbstractDisplayer
{
    public function display($key = '')
    {
        $value = $this->getAttribute($key);

        return <<<HTML
<input type="checkbox" name="item" class="form-check-input" value="{$value}"/>
HTML;
    }
}
