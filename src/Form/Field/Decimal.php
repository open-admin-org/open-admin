<?php

namespace OpenAdmin\Admin\Form\Field;

class Decimal extends Text
{
    protected static $js = [
        '/vendor/open-admin/inputmask/inputmask.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'alias'      => 'decimal',
        'rightAlign' => true,
    ];

    public function render()
    {
        $this->inputmask($this->options);

        $this->prepend('<i class="'.$this->icon.'"></i>');
        $this->style('max-width', '160px');

        return parent::render();
    }
}
