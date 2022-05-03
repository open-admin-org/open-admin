<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Form\Field;

class KeyValue extends Field
{
    /**
     * @var array
     */
    protected $value = ['' => ''];

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

        $rules["{$this->column}.keys.*"] = 'distinct';
        $rules["{$this->column}.values.*"] = $fieldRules;
        $attributes["{$this->column}.keys.*"] = __('Key');
        $attributes["{$this->column}.values.*"] = __('Value');

        return validator($input, $rules, $this->getValidationMessages(), $attributes);
    }

    protected function setupScript()
    {
        $this->script = <<<JS

document.querySelector('.{$this->column}-add').addEventListener('click', function () {
    var tpl = document.querySelector('template.{$this->column}-tpl').innerHTML;
    var clone = htmlToElement(tpl);
    document.querySelector('tbody.kv-{$this->column}-table').appendChild(clone);
});

document.querySelector('tbody.kv-{$this->column}-table').addEventListener('click', function (event) {
    if (event.target.classList.contains('{$this->column}-remove')){
        event.target.closest('tr').remove();
    }
});

JS;
    }

    public function prepare($value)
    {
        $value = parent::prepare($value);
        if (empty($value)) {
            return [];
        }

        return array_combine($value['keys'], $value['values']);
    }

    public function render()
    {
        $this->setupScript();

        return parent::render();
    }
}
