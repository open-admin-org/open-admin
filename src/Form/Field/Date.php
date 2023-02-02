<?php

namespace OpenAdmin\Admin\Form\Field;

class Date extends Text
{
    protected $format = 'YYYY-MM-DD';

    protected $defaults = [
        'weekNumbers'   => true,
        'time_24hr'     => true,
        'enableSeconds' => true,
        'enableTime'    => false,
        'allowInput'    => true,
        'noCalendar'    => false,
    ];

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    public function prepare($value)
    {
        $value = parent::prepare($value);

        // allows the value to be empty
        if (empty($value)) {
            $value = null;
        }

        // if the field is not present in the request it should not be processed
        if (empty($value) && !request()->has($this->column)) {
            $value = false;
        }

        return $value;
    }

    public function check_format_options()
    {
        $format = $this->options['format'];
        if (substr($format, -2) != 'ss') {
            $this->options['enableSeconds'] = false;
        }
        if (strpos($format, 'H') !== false) {
            $this->options['enableTime'] = true;
        }
    }

    public function render()
    {
        $this->options = array_merge($this->defaults, $this->options);
        $this->options['format'] = $this->format;
        $this->options['locale'] = array_key_exists('locale', $this->options) ? $this->options['locale'] : config('app.locale');
        $this->options['allowInputToggle'] = true;
        $this->check_format_options();

        $this->script = "flatpickr('{$this->getElementClassSelector()}',".json_encode($this->options).');';

        $this->prepend('<i class="icon-calendar fa-fw"></i>');
        $this->style('max-width', '160px');

        return parent::render();
    }
}
