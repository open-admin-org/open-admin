<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Form\Field;

class SwitchField extends Field
{
    public function prepare($value)
    {
        if ($value == 'on') {
            $value = 1;
        } else {
            $value = 0;
        }

        return $value;
    }

    public function render()
    {
        if (!$this->shouldRender()) {
            return '';
        }

        return parent::render();
    }
}
