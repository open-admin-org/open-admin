<?php

namespace OpenAdmin\Admin\Form\Field;

class Icon extends Text
{
    protected $default = '';

    protected static $js = [
        '/vendor/open-admin/fields/icon-picker/icon-picker.js',
    ];

    public function render()
    {
        $this->script = <<<EOT
new Iconpicker(document.querySelector("{$this->getElementClassSelector()}"),{
    showSelectedIn: document.querySelector("{$this->getElementClassSelector()}-icon"),
    defaultValue: '{$this->value}',
});
EOT;

        $this->prepend('<span class="'.substr($this->getElementClassSelector(), 1).'-icon"><i class="'.$this->value.'"></i></span>');
        $this->style('max-width', '160px');

        return parent::render();
    }
}
