<?php

namespace OpenAdmin\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;

class SwitchField extends Field
{
    public function prepare($value)
    {
        if ($value == 'on' || $value == 1 || $value == true) {
            $value = 1;
        } elseif ($value == 'off' || $value == 0 || $value == false) {
            $value = 0;
        } else {
            $value = false; // nothting was set so do: false to ignore value from saving
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
