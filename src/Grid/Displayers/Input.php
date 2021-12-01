<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use OpenAdmin\Admin\Admin;

class Input extends AbstractDisplayer
{
    public function display($mask = '')
    {
        return Admin::component('admin::grid.inline-edit.input', [
            'key'      => $this->getKey(),
            'type'     => 'text',
            'value'    => $this->getValue(),
            'display'  => $this->getValue(),
            'name'     => $this->getPayloadName(),
            'resource' => $this->getResource(),
            'trigger'  => "ie-trigger-{$this->getClassName()}-{$this->getKey()}",
            'target'   => "ie-content-{$this->getClassName()}-{$this->getKey()}",
            'mask'     => $mask,
        ]);
    }
}
