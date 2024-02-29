<?php

namespace OpenAdmin\Admin\Form\Field\Select;

use OpenAdmin\Admin\Form\Field\Select;

/**
 * Set usages of Tom-select (default).
 *
 * all configurations see https://github.com/orchidjs/tom-select
 */
class SelectTomSelect implements SelectDecorator
{
    public Select $select;
    public $pre_script;

    public function init(Select $select)
    {
        $this->select = $select;
    }

    public function render()
    {
        $plugins = [];
        if ($this->select->getSetting('removable')) {
            $plugins['remove_button'] = [];
        }

        $configs = array_merge([
            'hideSelected' => false,
            'plugins'      => $plugins,
            'create'       => $this->select->getSetting('create'),
        ], $this->select->getConfig());

        $configs = $this->select->getJsConfig($configs);

        $script = $this->pre_script;
        $script .= 'var '.$this->select->getJsInstanceName()." = new TomSelect('{$this->select->getElementClassSelector()}',{$configs});";
        $script .= $this->select->getAdditional_script();

        $this->select->setScript($script);
        $this->select->addVariables([
            'options' => $this->select->getOptions(),
            'groups'  => $this->select->getGroups(),
        ]);
    }

    public function ajax($url, $valueField, $labelField)
    {
        $vars = $this->select->getJsVars();
        $this->select->configKey('load', "<js>{$vars['js_var_name']}_load</js>");
        $this->select->configKey('valueField', $valueField);
        $this->select->configKey('labelField', $labelField);

        $this->pre_script .= <<<JS
            function {$vars['js_var_name']}_load(query, callback){
                admin.ajax.post("{$url}",{query:query},function(data){
                    callback(data.data);
                })
            }
        JS;
    }

    public function ajaxOptions($url, $valueField, $labelField, $parameters = [])
    {
        $vars = $this->select->getJsVars();

        $this->select->configKey('valueField', $valueField);
        $this->select->configKey('labelField', $labelField);

        $parameters_json = json_encode($parameters);

        $this->select->additional_script .= <<<JS
            var {$vars['js_ins_name']}_current_value = document.querySelector('select{$vars['js_selector']}').dataset.value;
            admin.ajax.post("{$url}",{$parameters_json},function(data){
                {$vars['js_ins_name']}.addOptions(data.data);
                for (i in data.data){
                    if (data.data[i].{$valueField} == {$vars['js_ins_name']}_current_value){
                        {$vars['js_ins_name']}.addItem(data.data[i].{$valueField},true);
                    }
                }
            });
        JS;
    }

    public function load($target_field, $url, $valueField, $labelField)
    {
        $vars = $this->select->getJsVars($target_field);

        $this->select->configKey('valueField', $valueField);
        $this->select->configKey('labelField', $labelField);

        $this->select->additional_script .= <<<JS
            document.querySelector("{$vars['js_selector']}").addEventListener('change', function(event) {
                var query = {$vars['js_ins_name']}?.getValue();
                var current_value = {$vars['js_target_ins_name']}?.getValue();
                admin.ajax.post("{$url}",{query:query},function(data){
                    let found = false;
                    for (i in data.data){
                        if (data.data[i].{$valueField} == current_value){
                            found = data.data[i].{$valueField};
                        }
                    }
                    {$vars['js_target_ins_name']}.clear();
                    {$vars['js_target_ins_name']}.clearOptions();
                    {$vars['js_target_ins_name']}.addOptions(data.data);
                    if (found){
                        {$vars['js_target_ins_name']}.addItem(found,true);
                    }

                })
            });
        JS;
    }
}
