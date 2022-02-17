<?php

namespace OpenAdmin\Admin\Grid\Filter\Presenter;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use OpenAdmin\Admin\Facades\Admin;

class Select extends Presenter
{
    /**
     * Options of select.
     *
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $script = '';

    /**
     * @var string
     */
    protected $additional_script = '';

    /**
     * Select constructor.
     *
     * @param mixed $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Set config for se.
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
     * Returns variable name for ChoicesJS object.
     */
    public function choicesObjName($field = false)
    {
        if (empty($field)) {
            $field = $this->getElementClass();
        }

        return 'choices_'.$field;
    }

    /**
     * Build options.
     *
     * @return array
     */
    protected function buildOptions(): array
    {
        if (is_string($this->options)) {
            $this->loadRemoteOptions($this->options);
        }

        if ($this->options instanceof \Closure) {
            $this->options = $this->options->call($this->filter, $this->filter->getValue());
        }

        if ($this->options instanceof Arrayable) {
            $this->options = $this->options->toArray();
        }

        $configs = array_merge([
            'removeItems'        => true,
            'removeItemButton'   => true,
            'allowHTML'          => true,
            'classNames'         => [
                'containerOuter' => 'choices '.$this->getElementClass(),
            ],
        ], $this->config);
        $configs = json_encode($configs);

        $script = 'var '.$this->choicesObjName()." = new Choices('.{$this->getElementClass()}',{$configs});";
        Admin::script($script.$this->additional_script);

        return is_array($this->options) ? $this->options : [];
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

        $this->additional_script .= <<<JS
        admin.ajax.post("{$url}",{$parameters_json},function(data){
            {$this->choicesObjName()}.setChoices(data.data, 'id', 'text', true);
        });
JS;

        return $this;
    }

    /**
     * Load options from ajax.
     *
     * @param string $resourceUrl
     * @param $idField
     * @param $textField
     */
    public function ajax($url, $idField = 'id', $textField = 'text')
    {
        $this->config = array_merge([
            'removeItems'        => true,
            'removeItemButton'   => true,
            'placeholder'        => $this->label,
        ], $this->config);

        $this->additional_script = <<<JS
            let elm = document.querySelector(".{$this->getElementClass()}");
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
        JS;

        return $this;
    }

    /**
     * @return array
     */
    public function variables(): array
    {
        return [
            'options' => $this->buildOptions(),
            'class'   => $this->getElementClass(),
        ];
    }

    /**
     * @return string
     */
    protected function getElementClass(): string
    {
        return str_replace('.', '_', $this->filter->getColumn());
    }

    /**
     * Get form element class.
     *
     * @param string $target
     *
     * @return mixed
     */
    protected function getClass($target): string
    {
        return str_replace('.', '_', $target);
    }
}
