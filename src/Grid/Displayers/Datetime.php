<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use OpenAdmin\Admin\Admin;

class Datetime extends AbstractDisplayer
{
    public function display($options = [])
    {
        return Admin::component('admin::grid.inline-edit.datetime', [
            'key'       => $this->getKey(),
            'value'     => $this->getValue(),
            'display'   => $this->getValue(),
            'name'      => $this->getPayloadName(),
            'resource'  => $this->getResource(),
            'trigger'   => "ie-trigger-{$this->getClassName()}-{$this->getKey()}",
            'target'    => "ie-content-{$this->getClassName()}-{$this->getKey()}",
            'options'   => json_encode($options),
            'locale'    => config('app.locale'),
        ]);
    }
}
