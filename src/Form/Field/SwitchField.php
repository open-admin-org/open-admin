<?php

namespace OpenAdmin\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;
use Illuminate\Support\Arr;

class SwitchField extends Field
{

    protected $size = 'small';

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    public function states($states = [])
    {
        foreach (Arr::dot($states) as $key => $state) {
            Arr::set($this->states, $key, $state);
        }

        return $this;
    }

    public function prepare($value)
    {
        if ($value == "on"){
            $value = 1;
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
