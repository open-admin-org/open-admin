<?php

namespace OpenAdmin\Admin\Form\Field;

class Currency extends Text
{
    /**
     * @var string
     */
    protected $symbol = '$';

    /**
     * @var array
     */
    protected static $js = [
        '/vendor/open-admin/inputmask/inputmask.min.js',
    ];

    /**
     * @see https://github.com/RobinHerbots/Inputmask#options
     *
     * @var array
     */
    protected $options = [
        'alias'              => 'currency',
        'radixPoint'         => '.',
        'prefix'             => '',
        'removeMaskOnSubmit' => true,
    ];

    /**
     * Set symbol for currency field.
     *
     * @param string $symbol
     *
     * @return $this
     */
    public function symbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Set digits for input number.
     *
     * @param int $digits
     *
     * @return $this
     */
    public function digits($digits)
    {
        return $this->options(compact('digits'));
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($value)
    {
        $value = parent::prepare($value);

        return (float) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->inputmask($this->options);

        $this->prepend($this->symbol);
        $this->style('max-width', '160px');

        return parent::render();
    }
}
