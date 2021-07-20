<?php

namespace OpenAdmin\Admin\Form\Field;

class Datetime extends Date
{
    protected $format = 'YYYY-MM-DD HH:mm:ss';

    public function render()
    {
        $this->style('max-width', '160px');

        return parent::render();
    }
}
