<?php

namespace OpenAdmin\Admin\Form\Field;

class DateMultiple extends Text
{
    protected $format = 'YYYY-MM-DD';

    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

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
        $this->options['format'] = $this->format;
        $this->options['locale'] = array_key_exists('locale', $this->options) ? $this->options['locale'] : config('app.locale');
        $this->options['allowInputToggle'] = true;
        $this->options['dateFormat'] = 'Y-m-d';
        $this->options['mode'] = 'multiple';
        $this->options['plugins'] = "[
            ShortcutButtonsPlugin({
              button: {
                label: 'Clear',
              },
              onClick: (index, fp) => {
                fp.clear();
                fp.close();
              }
            })
          ]";

        $this->script = "flatpickr('{$this->getElementClassSelector()}',".json_encode($this->options).');';

        $this->prepend('<i class="icon-calendar"></i>')
            ->defaultAttribute('style', 'width: 100%');

        return parent::render();
    }
}
