<?php

namespace OpenAdmin\Admin\Form\Field;

class Color extends Text
{
    protected static $css = [
        '/vendor/open-admin/coloris/coloris.min.css',
    ];

    protected static $js = [
        '/vendor/open-admin/coloris/coloris.min.js',
    ];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Use `format` format.
     *   // * hex: outputs #RRGGBB or #RRGGBBAA (default).
     *   // * rgb: outputs rgb(R, G, B) or rgba(R, G, B, A).
     *   // * hsl: outputs hsl(H, S, L) or hsla(H, S, L, A).
     *   // * auto: guesses the format from the active input field. Defaults to hex if it fails.
     *   // * mixed: outputs #RRGGBB when alpha is 1; otherwise rgba(R, G, B, A).
     *
     * @return $this
     */
    public function format($format = 'hex')
    {
        return $this->options(['format'=> $format]);
    }

    /**
     * Set using alpha.
     *
     * @param bool $set
     *
     * @return $this
     */
    public function alpha($set = true)
    {
        return $this->options(['alpha'=> $set]);
    }

    /**
     * Set config for coloris.
     *
     * all configurations see https://github.com/mdbassit/Coloris/
     *
     * @param string $key
     * @param mixed  $val
     *
     * @return $this
     */
    public function options($options = [])
    {
        $this->options = array_merge($options, $this->options);

        return $this;
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $options = array_merge([
            'el'         => $this->getElementClassSelector(),
            'theme'      => 'polaroid',
            'focusInput' => false,

        ], $this->options);
        $options = json_encode($options);

        //$this->setElementClass('form-control');

        $this->script = "Coloris($options);";

        $this->prepend('<i class="icon-eye-dropper"></i>');
        //$this->style('max-width', '160px');

        return parent::render();
    }
}
