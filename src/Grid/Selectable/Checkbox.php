<?php

namespace OpenAdmin\Admin\Grid\Selectable;

use OpenAdmin\Admin\Grid\Displayers\AbstractDisplayer;

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
