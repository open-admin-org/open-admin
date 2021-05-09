<?php

namespace OpenAdmin\Admin\Form\Field;

class Mobile extends Text
{
    protected static $js = [
        '/vendor/open-admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'mask' => '99999999999',
    ];

    public function render()
    {
        $this->inputmask($this->options);

        $this->prepend('<i class="fa fa-phone fa-fw"></i>');
        $this->style("max-width","160px");

        return parent::render();
    }
}
