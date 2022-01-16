<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Form\Field;

class Select extends Field
{
    use CanCascadeFields;

    /**
     * @var array
     */
    protected static $css = [
        //'/vendor/open-admin/tom-select/tom-select.css',
    ];

    /**
     * @var array
     */
    protected static $js = [
        //'/vendor/open-admin/tom-select/tom-select.complete.min.js',
    ];

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    /**
     * @var bool
     */
    protected $native = false;

    public $additional_script = '';

    /**
     * Set options.
     *
     * @param array|callable|string $options
     *
     * @return $this|mixed
     */
    public function options($options = [])
    {
        // remote options
        if (is_string($options)) {
            // reload selected
            if (class_exists($options) && in_array(Model::class, class_parents($options))) {
                return $this->model(...func_get_args());
            }

            return $this->loadRemoteOptions(...func_get_args());
        }

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
     * @param array $groups
     */

    /**
     * Set option groups.
     *
     * eg: $group = [
     *        [
     *        'label' => 'xxxx',
     *        'options' => [
     *            1 => 'foo',
     *            2 => 'bar',
     *            ...
     *        ],
     *        ...
     *     ]
     *
     * @param array $groups
     *
     * @return $this
     */
    public function groups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Load options for other select on change.
     *
     * @param string $field
     * @param string $sourceUrl
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function load($field, $url, $idField = 'id', $textField = 'text', bool $allowClear = true)
    {
        if (Str::contains($field, '.')) {
            $field = $this->formatName($field);
            $class = str_replace(['[', ']'], '_', $field);
        } else {
            $class = $field;
        }

        $this->additional_script .= <<<EOT

            let elm = document.querySelector("{$this->getElementClassSelector()}");
            var lookupTimeout;
            elm.addEventListener('change', function(event) {
                var query = {$this->choicesObjName()}.getValue().value;
                var current_value = {$this->choicesObjName($field)}.getValue().value;
                admin.ajax.post("{$url}",{query:query},function(data){
                    let found = false;
                    for (i in data.data){
                        if (data.data[i].id == current_value){
                            data.data[i].selected = true;
                            found = true;
                        }
                    }
                    if (!found){
                        data.data.push({'{$idField}':'','{$textField}':'','selected':true});
                    }
                    {$this->choicesObjName($field)}.setChoices(data.data, '{$idField}', '{$textField}', true);
                })
            });
EOT;

        return $this;
    }

    /**
     * Load options for other selects on change.
     *
     * @param array  $fields
     * @param array  $sourceUrls
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function loads($fields = [], $sourceUrls = [], $idField = 'id', $textField = 'text', bool $allowClear = true)
    {
        $fieldsStr = implode('.', $fields);
        $urlsStr = implode('^', $sourceUrls);

        $placeholder = json_encode([
            'id'   => '',
            'text' => trans('admin.choose'),
        ]);

        $strAllowClear = var_export($allowClear, true);

        $script = <<<EOT

        console.log('select: loads not ported yet, not sure if needed');
        /*
var fields = '$fieldsStr'.split('.');
var urls = '$urlsStr'.split('^');

var refreshOptions = function(url, target) {
    $.get(url).then(function(data) {
        target.find("option").remove();
        $(target).select2({
            placeholder: $placeholder,
            allowClear: $strAllowClear,
            data: $.map(data, function (d) {
                d.id = d.$idField;
                d.text = d.$textField;
                return d;
            })
        }).trigger('change');
    });
};

$(document).off('change', "{$this->getElementClassSelector()}");
$(document).on('change', "{$this->getElementClassSelector()}", function () {
    var _this = this;
    var promises = [];

    fields.forEach(function(field, index){
        var target = $(_this).closest('.fields-group').find('.' + fields[index]);
        promises.push(refreshOptions(urls[index] + "?q="+ _this.value, target));
    });
});
*/
EOT;

        Admin::script($script);

        return $this;
    }

    /**
     * Load options from current selected resource(s).
     *
     * @param string $model
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function model($model, $idField = 'id', $textField = 'name')
    {
        if (!class_exists($model)
            || !in_array(Model::class, class_parents($model))
        ) {
            throw new \InvalidArgumentException("[$model] must be a valid model class");
        }

        $this->options = function ($value) use ($model, $idField, $textField) {
            if (empty($value)) {
                return [];
            }

            $resources = [];

            if (is_array($value)) {
                if (Arr::isAssoc($value)) {
                    $resources[] = Arr::get($value, $idField);
                } else {
                    $resources = array_column($value, $idField);
                }
            } else {
                $resources[] = $value;
            }

            return $model::find($resources)->pluck($textField, $idField)->toArray();
        };

        return $this;
    }

    /**
     * Load options from remote.
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $options
     *
     * @return $this
     */
    protected function loadRemoteOptions($url, $parameters = [], $options = [])
    {
        $this->config = array_merge([
            'removeItems'        => true,
            'removeItemButton'   => true,
        ], $this->config);

        $parameters_json = json_encode($parameters);

        $this->additional_script .= <<<EOT
        admin.ajax.post("{$url}",{$parameters_json},function(data){
            {$this->choicesObjName()}.setChoices(data.data, 'id', 'text', true);
        });
EOT;

        return $this;
    }

    /**
     * Load options from ajax results.
     *
     * @param string $url
     * @param $idField
     * @param $textField
     *
     * @return $this
     */
    public function ajax($url, $idField = 'id', $textField = 'text')
    {
        $this->config = array_merge([
            'removeItems'        => true,
            'removeItemButton'   => true,
            'placeholder'        => $this->label,
        ], $this->config);

        $this->additional_script = <<<EOT
            let elm = document.querySelector("{$this->getElementClassSelector()}");
            var lookupTimeout;
            elm.addEventListener('search', function(event) {
                clearTimeout(lookupTimeout);
                lookupTimeout = setTimeout(function(){
                    var query = {$this->choicesObjName()}.input.value;
                    admin.ajax.post("{$url}",{query:query},function(data){
                        {$this->choicesObjName()}.setChoices(data.data, '{$idField}', '{$textField}', true);
                    })
                }, 250);
            });

            elm.addEventListener('choice', function(event) {
                {$this->choicesObjName()}.setChoices([], '{$idField}', '{$textField}', true);
            });
        EOT;

        return $this;
    }

    /**
     * Set use browser native selectbox.
     *
     * @return $this
     */
    public function useNative()
    {
        $this->native = true;

        return $this;
    }

    /**
     * Set use browser native selectbox.
     *
     * @return $this
     */
    public function useChoicesjs()
    {
        $this->native = false;

        return $this;
    }

    /**
     * Set config for Choicesjs.
     *
     * all configurations see https://github.com/jshjohnson/Choices
     *
     * @param string $key
     * @param mixed  $val
     *
     * @return $this
     */
    public function config($key, $val)
    {
        $this->config[$key] = $val;

        return $this;
    }

    /**
     * Set as readonly (actual dissable with backup hidden field).
     */
    public function readOnly()
    {
        $this->useNative();
        $this->config('readonly', true);
        $this->attribute('disabled', true);

        return parent::readOnly();
    }

    public function choicesObjName($field = false)
    {
        if (empty($field)) {
            $field = $this->getElementClassString();
        }

        return 'choices_'.$field;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $configs = array_merge([
            'removeItems'        => true,
            'removeItemButton'   => true,
            'placeholder'        => [
                'id'   => '',
                'text' => $this->label,
            ],
            'classNames' => [
                'containerOuter' => 'choices '.$this->getElementClassString(),
            ],
        ], $this->config);
        $configs = json_encode($configs);

        if (!$this->native && (get_class($this) == 'OpenAdmin\Admin\Form\Field\Select' || get_class($this) == 'OpenAdmin\Admin\Form\Field\MultipleSelect')) {
            $this->script .= 'var '.$this->choicesObjName()." = new Choices('{$this->getElementClassSelector()}',{$configs});";
            $this->script .= $this->additional_script;
        }

        if ($this->options instanceof \Closure) {
            if ($this->form) {
                $this->options = $this->options->bindTo($this->form->model());
            }

            $this->options(call_user_func($this->options, $this->value, $this));
        }

        $this->options = array_filter($this->options, 'strlen');

        $this->addVariables([
            'options' => $this->options,
            'groups'  => $this->groups,
        ]);

        $this->addCascadeScript();

        $this->attribute('data-value', implode(',', (array) $this->value()));

        return parent::render();
    }
}
