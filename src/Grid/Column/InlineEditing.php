<?php

namespace OpenAdmin\Admin\Grid\Column;

use OpenAdmin\Admin\Grid\Displayers;

trait InlineEditing
{
    /**
     * @param string $selectable
     *
     * @return $this
     */
    public function belongsTo($selectable)
    {
        if (method_exists($selectable, 'display')) {
            $this->display($selectable::display());
        }

        return $this->displayUsing(Displayers\BelongsTo::class, [$selectable]);
    }

    /**
     * @param string $selectable
     *
     * @return $this
     */
    public function belongsToMany($selectable)
    {
        if (method_exists($selectable, 'display')) {
            $this->display($selectable::display());
        }

        return $this->displayUsing(Displayers\BelongsToMany::class, [$selectable]);
    }

    /**
     * Upload file.
     *
     * @return $this
     */
    public function upload()
    {
        return $this->displayUsing(Displayers\Upload::class);
    }

    /**
     * Upload many files.
     *
     * @return $this
     */
    public function uplaodMany()
    {
        return $this->displayUsing(Displayers\Upload::class, [true]);
    }

    /**
     * Grid inline datetime picker.
     *
     * @param string $format
     *
     * @return $this
     */
    public function datetime($options = [])
    {
        $default_options = [
            'inline'    => true,
            'time_24hr' => true,
        ];
        if (empty($options['format'])) {
            $options['format'] = 'YYYY-MM-DD HH:mm:ss';
        }
        if ($options['format'] == 'YYYY-MM-DD HH:mm:ss') {
            $default_options['enableTime'] = true;
            $default_options['enableSeconds'] = true;
        }
        $options = array_merge($default_options, $options);

        return $this->displayUsing(Displayers\Datetime::class, [$options]);
    }

    /**
     * Grid inline date picker.
     *
     * @param string $format
     *
     * @return $this
     */
    public function date()
    {
        return $this->datetime(['format'=>'YYYY-MM-DD']);
    }

    /**
     * Grid inline time picker.
     *
     * @param string $format
     *
     * @return $this
     */
    public function time()
    {
        return $this->datetime(['format'=>'HH:mm:ss', 'enableTime' => true, 'enableSeconds' => true, 'noCalendar' => true]);
    }

    /**
     * Grid inline input.
     *
     * @return $this
     */
    protected function input($mask = [])
    {
        return $this->displayUsing(Displayers\Input::class, [$mask]);
    }

    /**
     * Grid inline text input.
     *
     * @return $this
     */
    public function text()
    {
        return $this->input();
    }

    /**
     * Grid inline ip input.
     *
     * @return $this
     */
    public function ip()
    {
        return $this->input(['alias' => 'ip']);
    }

    /**
     * Grid inline email input.
     *
     * @return $this
     */
    public function email()
    {
        return $this->input(['alias' => 'email']);
    }

    /**
     * Grid inline url input.
     *
     * @return $this
     */
    public function url()
    {
        return $this->input(['alias' => 'url']);
    }

    /**
     * Grid inline currency input.
     *
     * @return $this
     */
    public function currency()
    {
        return $this->input([
            'alias'              => 'currency',
            'radixPoint'         => '.',
            'prefix'             => '',
            'removeMaskOnSubmit' => true,
        ]);
    }

    /**
     * Grid inline decimal input.
     *
     * @return $this
     */
    public function decimal()
    {
        return $this->input([
            'alias'      => 'decimal',
            'rightAlign' => true,
        ]);
    }

    /**
     * Grid inline integer input.
     *
     * @return $this
     */
    public function integer()
    {
        return $this->input([
            'alias' => 'integer',
        ]);
    }

    /**
     * Grid inline textarea.
     *
     * @param int $rows
     *
     * @return $this
     */
    public function textarea($rows = 5)
    {
        return $this->displayUsing(Displayers\Textarea::class, [$rows]);
    }

    /**
     * Grid inline tiemzone select.
     *
     * @return $this
     */
    public function timezone()
    {
        $identifiers = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        $options = collect($identifiers)->mapWithKeys(function ($timezone) {
            return [$timezone => $timezone];
        })->toArray();

        return $this->select($options);
    }

    /**
     * Grid inline select.
     *
     * @param array $options
     *
     * @return mixed
     */
    public function select(array $options)
    {
        return $this->displayUsing(Displayers\Select::class, [$options]);
    }

    /**
     * Grid inline multiple-select input.
     *
     * @param array $options
     *
     * @return $this
     */
    public function multipleSelect(array $options)
    {
        return $this->displayUsing(Displayers\MultipleSelect::class, [$options]);
    }

    /**
     * Grid inline checkbox.
     *
     * @param array $options
     *
     * @return $this
     */
    public function checkbox(array $options)
    {
        return $this->displayUsing(Displayers\Checkbox::class, [$options]);
    }

    /**
     * Grid inline checkbox.
     *
     * @param array $options
     *
     * @return $this
     */
    public function radio(array $options)
    {
        return $this->displayUsing(Displayers\Radio::class, [$options]);
    }

    /**
     * Grid inline switch.
     *
     * @param array $states
     *
     * @return $this
     */
    public function switch(array $states = [])
    {
        return $this->displayUsing(Displayers\SwitchDisplay::class, [$states]);
    }

    /**
     * Grid inline switch group.
     *
     * @param array $states
     *
     * @return $this
     */
    public function switchGroup(array $columns = [], array $states = [])
    {
        return $this->displayUsing(Displayers\SwitchGroup::class, [$columns, $states]);
    }
}
