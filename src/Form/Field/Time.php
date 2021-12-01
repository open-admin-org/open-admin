<?php

namespace OpenAdmin\Admin\Form\Field;

class Time extends Date
{
    protected $format = 'HH:mm:ss';

    public function render()
    {
        $this->prepend('<i class="icon-clock"></i>');
        $this->style('max-width', '160px');
        $this->options['noCalendar'] = true;

        return parent::render();
    }
}
