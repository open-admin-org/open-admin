<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Admin;

class Checkbox extends AbstractDisplayer
{
    public function display($options = [])
    {
        return Admin::component('admin::grid.inline-edit.checkbox', [
            'key'      => $this->getKey(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-content-{$this->getClassName()}-{$this->getKey()}",
            'value'    => json_encode($this->getValue()),
            'display'  => implode(';', Arr::only($options, $this->getValue())),
            'options'  => $options,
        ]);
    }
}
