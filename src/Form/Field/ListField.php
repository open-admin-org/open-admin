<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form\Field;

class ListField extends Field
{
    /**
     * @var array
     */
    protected $value = [''];

    /**
     * @var array
     */
    protected $is_sortable = false;

    public function sortable($set = true)
    {
        $this->is_sortable = $set;

        return $this;
    }

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

        if ($this->is_sortable) {
            $this->script .= <<<JS

            var sortable = new Sortable(document.querySelector("tbody.list-{$this->column}-table"), {
                animation:150,
                handle: ".handle",
            });
JS;
        }
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
        $this->setupScript();
        view()->share('is_sortable', $this->is_sortable);

        Admin::style('td .form-group {margin-bottom: 0 !important;}');

        return parent::render();
    }
}
