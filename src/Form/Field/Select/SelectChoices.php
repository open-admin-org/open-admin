<?php

namespace OpenAdmin\Admin\Form\Field\Select;

use OpenAdmin\Admin\Form\Field\Select;

/**
 * Set usages of Choicesjs (default).
 *
 * all configurations see https://github.com/jshjohnson/Choices
 */
class SelectChoices implements SelectDecorator
{
    public Select $select;

    public function init(Select $select)
    {
        $this->select = $select;
    }

    public function items()
    {
        $options = $this->select->getOptions();

        return array_map(function ($key, $label) {
            return ['value' => $key, 'label' => $label, 'id' => $key, 'select' => false, 'disabled' => false];
        }, array_keys($options), $options);
    }

    public function render()
    {
        $configs = array_merge([
            'removeItems'      => $this->select->getSetting('removable'),
            'removeItemButton' => $this->select->getSetting('removable'),
            'placeholder'      => [
                'id'   => '',
                'text' => $this->select->getLabel(),
            ],
            'classNames' => [
                'containerOuter' => 'choices '.$this->select->getElementClassString(),
            ],
            //'choices' => $this->items()
        ], $this->select->getConfig());

        $configs = $this->select->getJsConfig($configs);

        $script = 'var '.$this->select->getJsInstanceName()." = new Choices('{$this->select->getElementClassSelector()}',{$configs});";
        if ($this->select->getSetting('create') == true) {
            $this->addCreate();
        }

        $script .= $this->select->getAdditional_script();

        $this->select->setScript($script);
        $this->select->addVariables([
            'options' => $this->select->getOptions(),
            'groups'  => $this->select->getGroups(),
        ]);
    }

    public function addCreate()
    {
        $vars = $this->select->getJsVars();

        $this->select->additional_script .= <<<JS
            // pretty dirty hack for choices js to be able to add new items
            choicesjs_allow_create({$vars['js_ins_name']});

        JS;
    }

    public function ajax($url, $valueField, $labelField)
    {
        $vars = $this->select->getJsVars();

        $this->select->configKey('valueField', $valueField);
        $this->select->configKey('labelField', $labelField);

        $this->select->additional_script .= <<<JS
            let search_{$vars['js_var_name']} = document.querySelector("{$vars['js_selector']}");
            var lookupTimeout;
            search_{$vars['js_var_name']}.addEventListener('search', function(event) {
                clearTimeout(lookupTimeout);
                lookupTimeout = setTimeout(function(){
                    var query = {$vars['js_ins_name']}.input.value;
                    admin.ajax.post("{$url}",{query:query},function(data){
                        {$vars['js_ins_name']}.setChoices(data.data, '{$valueField}', '{$labelField}', true);
                    })
                }, 250);
            });

            search_{$vars['js_var_name']}.addEventListener('choice', function(event) {
                {$vars['js_ins_name']}.setChoices([], '{$valueField}', '{$labelField}', true);
            });
        JS;
    }

    public function ajaxOptions($url, $valueField, $labelField, $parameters = [])
    {
        $vars = $this->select->getJsVars();

        $parameters_json = json_encode($parameters);

        $this->select->additional_script .= <<<JS
            var current_value = document.querySelector('select{$vars['js_selector']}').dataset.value;
            admin.ajax.post("{$url}",{$parameters_json},function(data){
                for (i in data.data){
                    if (data.data[i].{$valueField} == current_value){
                        data.data[i].selected = true;
                    }
                }
                {$vars['js_ins_name']}.setChoices(data.data, '{$valueField}', '{$labelField}', true);
            });
        JS;
    }

    public function load($target_field, $url, $valueField = 'id', $labelField = 'text')
    {
        $vars = $this->select->getJsVars($target_field);

        $this->select->additional_script .= <<<JS
            document.querySelector("{$vars['js_selector']}").addEventListener('change', function(event) {
                var query = {$vars['js_ins_name']}?.getValue()?.value;
                var current_value = {$vars['js_target_ins_name']}?.getValue()?.value;
                admin.ajax.post("{$url}",{query:query},function(data){
                    let found = false;
                    for (i in data.data){
                        if (data.data[i].{$valueField} == current_value){
                            data.data[i].selected = true;
                            found = true;
                        }
                    }
                    if (!found){
                        data.data.push({'{$valueField}':'','{$labelField}':'','selected':true});
                    }
                    {$vars['js_target_ins_name']}.setChoices(data.data, '{$valueField}', '{$labelField}', true);
                })
            });
        JS;
    }
}
