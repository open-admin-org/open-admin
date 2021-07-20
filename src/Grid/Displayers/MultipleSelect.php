<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Admin;

class MultipleSelect extends AbstractDisplayer
{
    public function display($options = [])
    {
        return Admin::component('admin::grid.inline-edit.multiple-select', [
            'key'      => $this->getKey(),
            'name'     => $this->getPayloadName(),
            'value'    => json_encode($this->getValue()),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}",
            'target'   => "ie-template-{$this->getClassName()}",
            'display'  => implode(';', Arr::only($options, $this->getValue())),
            'options'  => $options,
        ]);
    }
}
