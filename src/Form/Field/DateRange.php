<?php

namespace OpenAdmin\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;

class DateRange extends Field
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

    /**
     * Column name.
     *
     * @var array
     */
    protected $column = [];

    protected static $js = [
        '/vendor/open-admin/flatpickr/plugins/rangePlugin.js',
    ];

    public function __construct($column, $arguments)
    {
        $this->column['start'] = $column;
        $this->column['end'] = $arguments[0];

        array_shift($arguments);

        $this->label = $this->formatLabel($arguments);
        $this->id = $this->formatId($this->column);

        $this->options(['format' => $this->format]);
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

    /**
     * {@inheritdoc}
     */
    public function prepare($value)
    {
        $value = parent::prepare($value);
        if ($value === '') {
            $value = null;
        }

        return $value;
    }

    public function render()
    {
        $this->options = array_merge($this->defaults, $this->options);
        $this->options['format'] = $this->format;
        $this->options['locale'] = array_key_exists('locale', $this->options) ? $this->options['locale'] : config('app.locale');
        $this->options['allowInputToggle'] = true;
        $this->options['plugins'] = '__replace_me__';

        $this->check_format_options();

        $options_start = json_encode($this->options);
        $options_start = str_replace('"__replace_me__"', '[new rangePlugin({ input: "'.$this->getElementClassSelector()['end'].'"})]', $options_start);

        //$options_end = json_encode($this->options);

        $this->script = <<<EOT
            flatpickr('{$this->getElementClassSelector()['start']}',{$options_start});

        EOT;

        return parent::render();
    }
}
