<?php

namespace OpenAdmin\Admin\Form\Field\Select;

use OpenAdmin\Admin\Form\Field\Select;

class SelectNative implements SelectDecorator
{
    public Select $select;

    public function init(Select $select)
    {
        $this->select = $select;
    }

    public function render()
    {
        $this->select->setScript($this->select->getAdditional_script());
        //$this->select->attribute('data-value', implode(',', (array) $this->select->value()));
    }

    public function ajax($url, $valueField, $labelField)
    {
        $this->select->additional_script = <<<'JS'
            console.log("Ajax not implemented for NativeSelect")
        JS;
    }

    public function ajaxOptions($url, $valueField, $labelField, $parameters = [])
    {
        $vars            = $this->select->getJsVars();
        $parameters_json = json_encode($parameters);
        $this->select->additional_script .= <<<JS
            var current_value = document.querySelector('select{$vars['js_selector']}').value;
            admin.ajax.post("{$url}",{$parameters_json},function(data){
                let field = document.querySelector('select{$vars['js_selector']}')
                field.innerHTML = '';
                for (i in data.data){
                    let selected = (data.data[i].{$valueField} == current_value)
                    field[i] = new Option(data.data[i].{$labelField}, data.data[i].{$valueField}, false, selected);
                }
            });
        JS;
    }

    public function load($target_field, $url, $valueField = 'id', $labelField = 'text')
    {
        $vars = $this->select->getJsVars($target_field);

        $this->select->additional_script .= <<<JS
            document.querySelector("{$vars['js_selector']}").addEventListener('change', function(event) {
                var query = document.querySelector("{$vars['js_selector']}").value;
                var current_value = document.querySelector("{$vars['js_target_selector']}").value;
                admin.ajax.post("{$url}",{query:query},function(data){

                    let field = document.querySelector('select{$vars['js_target_selector']}')
                    field.innerHTML = '';
                    for (i in data.data){
                        let selected = (data.data[i].{$valueField} == current_value)
                        field[i] = new Option(data.data[i].{$labelField}, data.data[i].{$valueField}, false, selected);
                    }
                })
            });
        JS;
    }
}
