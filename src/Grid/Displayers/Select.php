<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Admin;

class Select extends AbstractDisplayer
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

        return Admin::component('admin::grid.inline-edit.select', [
            'key'      => $this->getKey(),
            'value'    => $this->getValue(),
            'display'  => $display,
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}-{$this->getKey()}",
            'target'   => "ie-content-{$this->getClassName()}-{$this->getKey()}",
            'options'  => $options,
        ]);
    }
}
