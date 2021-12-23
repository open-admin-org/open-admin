<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Admin;

class Radio extends AbstractDisplayer
{
    public function display($options = [])
    {
        // prevent null value
        $value = $this->getValue();
        if (!is_null($value)) {
            $display = Arr::get($options, $value, $value);
        } else {
            $display = $value;
        }

        return Admin::component('admin::grid.inline-edit.radio', [
            'key'      => $this->getKey(),
            'name'     => $this->getPayloadName(),
            'value'    => $this->getValue(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}-{$this->getKey()}",
            'target'   => "ie-content-{$this->getClassName()}-{$this->getKey()}",
            'display'  => $display,
            'options'  => $options,
        ]);
    }
}
