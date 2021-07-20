<?php

namespace OpenAdmin\Admin\Form\Field;

class Ip extends Text
{
    protected $rules = 'nullable|ip';

    protected static $js = [
        '/vendor/open-admin/inputmask/inputmask.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'alias' => 'ip',
    ];

    public function render()
    {
        $this->inputmask($this->options);

        $this->prepend('<i class="icon-laptop fa-fw"></i>');
        $this->style('max-width', '160px');

        return parent::render();
    }
}
