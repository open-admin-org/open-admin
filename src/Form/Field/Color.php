<?php

namespace OpenAdmin\Admin\Form\Field;

class Color extends Text
{
    /*
    protected static $css = [
        '/vendor/open-admin/colr_pickr/colr_pickr.min.css',
    ];

    protected static $js = [
        '/vendor/open-admin/colr_pickr/colr_pickr.min.js',
    ];
    */

    /**
     * Use `hex` format.
     *
     * @return $this
     */
    public function hex()
    {
        return $this->options(['format' => 'hex']);
    }

    /**
     * Use `rgb` format.
     *
     * @return $this
     */
    public function rgb()
    {
        return $this->options(['format' => 'rgb']);
    }

    /**
     * Use `rgba` format.
     *
     * @return $this
     */
    public function rgba()
    {
        return $this->options(['format' => 'rgba']);
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $options = json_encode($this->options);

        $this->setElementClass("form-control-color");
        $this->attribute("type","color");

       // $this->script = "new ColorPicker(document.querySelector('{$this->getElementClassSelector()}'))";
        //$this->script = "$('{$this->getElementClassSelector()}').parent().colorpicker($options);";

        $this->prepend('<i class="icon-eyedropper"></i>');
        $this->style("max-width","160px");

        return parent::render();
    }
}
