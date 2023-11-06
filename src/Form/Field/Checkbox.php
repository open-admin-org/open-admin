<?php

namespace OpenAdmin\Admin\Form\Field;

use Admin;
use Illuminate\Contracts\Support\Arrayable;

class Checkbox extends MultipleSelect
{
    protected $stacked = false;

    /**
     * @var string
     */
    protected $cascadeEvent = 'change';


    /**
     * Set options.
     *
     * @param array|callable|string $options
     *
     * @return $this|mixed
     */
    public function options($options = [])
    {
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        if (is_callable($options)) {
            $this->options = $options;
        } else {
            $this->options = (array) $options;
        }

        return $this;
    }

    /**
     * Set checked.
     *
     * @param array|callable|string $checked
     *
     * @return $this
     */
    public function checked($checked = [])
    {
        if ($checked instanceof Arrayable) {
            $checked = $checked->toArray();
        }

        $this->checked = (array) $checked;

        return $this;
    }

    /**
     * Draw inline checkboxes.
     *
     * @return $this
     */
    public function inline()
    {
        $this->stacked = false;

        return $this;
    }

    /**
     * Draw stacked checkboxes.
     *
     * @return $this
     */
    public function stacked()
    {
        $this->stacked = true;

        return $this;
    }

    public function script()
    {
        $var_name = $this->getVariableName();
        $elementClassSelector = $this->getElementClassSelector();
        $requireMessage = $this->validationMessages['required'] ?? 'This field is required';

        if ($this->stacked && in_array("required", $this->rules)) {
            $script = <<<JS

            function {$var_name}_check_stacked(){
                if ( document.querySelectorAll("{$elementClassSelector}:checked").length == 0){
                    document.querySelectorAll("{$elementClassSelector}").forEach((rel)=>{
                        rel.setCustomValidity("{$requireMessage}");
                    })
                }else{
                    document.querySelectorAll("{$elementClassSelector}").forEach((rel)=>{
                        rel.setCustomValidity("");
                        rel.removeAttribute("required");
                    })
                }
            };
            document.querySelectorAll("{$elementClassSelector}").forEach((el)=>{
                el.addEventListener('change', function(e) {
                    {$var_name}_check_stacked();
                })
            });
            {$var_name}_check_stacked();
        JS;
        }

        Admin::script($script);
    }


    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->addVariables([
            'checked'      => $this->checked,
            'stacked'      => $this->stacked,
        ]);

        $this->script();

        return parent::render();
    }
}
