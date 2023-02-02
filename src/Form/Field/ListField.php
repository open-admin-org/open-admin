<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Form\Field\Traits\Sortable;

class ListField extends Field
{
    use Sortable;
    /**
     * @var array
     */
    protected $value = [''];

    /**
     * Fill data to the field.
     *
     * @param array $data
     *
     * @return void
     */
    public function fill($data)
    {
        $this->data = $data;

        $this->value = Arr::get($data, $this->column, $this->value);
        if (!is_array($this->value)) {
            $this->value = json_decode($this->value);
        }
        if (empty($this->value)) {
            $this->value = [''];
        }

        $this->formatValue();
    }

    /**
     * {@inheritdoc}
     */
    public function getValidator(array $input)
    {
        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        if (!is_string($this->column)) {
            return false;
        }

        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        if (!Arr::has($input, $this->column)) {
            return false;
        }

        $rules["{$this->column}.*"] = $fieldRules;
        $attributes["{$this->column}.*"] = __('Value');

        $rules["{$this->column}"][] = 'array';

        $attributes["{$this->column}"] = $this->label;

        return validator($input, $rules, $this->getValidationMessages(), $attributes);
    }

    /**
     * {@inheritdoc}
     */
    protected function setupScript()
    {
        $this->script = <<<JS

        document.querySelector('.{$this->column}-add').addEventListener('click', function () {
            var tpl = document.querySelector('template.{$this->column}-tpl').innerHTML;
            var clone = htmlToElement(tpl);
            document.querySelector('tbody.list-{$this->column}-table').appendChild(clone);
        });

        document.querySelector('tbody.list-{$this->column}-table').addEventListener('click', function (event) {
            if (event.target.classList.contains('{$this->column}-remove')){
                event.target.closest('tr').remove();
            }
        });
JS;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($value)
    {
        $value = (array) parent::prepare($value);

        $values = array_values($value);
        if (count($values) == 1 && empty($values[0])) {
            return [];
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addSortable('tbody.list-', '-table');
        view()->share('options', $this->options);

        $this->setupScript();

        Admin::style('td .form-group {margin-bottom: 0 !important;}');

        return parent::render();
    }
}
