<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Form\Field\Select\SelectChoices;
use OpenAdmin\Admin\Form\Field\Select\SelectNative;
use OpenAdmin\Admin\Form\Field\Select\SelectTomSelect;
use OpenAdmin\Admin\Form\Field\Traits\CanCascadeFields;
use OpenAdmin\Admin\Form\Field\Traits\HasJavascriptConfig;
use OpenAdmin\Admin\Form\Field\Traits\HasSettings;

class Select extends Field
{
    use CanCascadeFields;
    use HasSettings;
    use HasJavascriptConfig;

    public $must_prepare = true;

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    /**
     * @var decorator
     */
    protected $decorator;

    /**
     * @var bool
     */
    protected $native = false;

    public $additional_script = '';

    public function init()
    {
        $this->settings = [
            'removable' => true,
            'html'      => true,
            'create'    => false,
        ];

        //$this->decorator(SelectTomSelect::class);
        $this->decorator(SelectChoices::class);
    }

    public function allowDecorator()
    {
        $class = get_class($this);

        return in_array($class, [
            'OpenAdmin\Admin\Form\Field\Select',
            'OpenAdmin\Admin\Form\Field\Tags',
            'OpenAdmin\Admin\Form\Field\MultipleSelect',
            'OpenAdmin\Admin\Form\Field\Timezone',
        ]);
    }

    /**
     * Sets the decorator for select fields.
     *
     * @param SelectDecorator $decorator
     *
     * @return $this
     */
    public function decorator($decorator)
    {
        $this->decorator = new $decorator();
        $this->decorator->init($this);

        return $this;
    }

    /**
     * Set create option
     *
     * @param bool $set
     *
     * @return $this
     */
    public function create($set = true)
    {
        $this->settings['create'] = $set;

        return $this;
    }

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
     * Returns variable name for Javascript object.
     */
    public function getJsInstanceName($field = false)
    {
        if (empty($field)) {
            $field = $this->getVariableName();
        }

        return str_replace('__', '_', 'js_select_'.$field);
    }

    /**
     * Returns variables for Javascript render.
     */
    public function getJsVars($target_field = false)
    {
        $field_name  = $this->formatName($this->column);
        $js_var_name = $this->getVariableName();
        $js_ins_name = $this->getJsInstanceName();
        $js_selector = $this->getElementClassSelector();

        $vars = [
            'field_name'  => $field_name,
            'js_var_name' => $js_var_name,
            'js_ins_name' => $js_ins_name,
            'js_selector' => $js_selector,
        ];

        if ($target_field) {
            $js_target_var_name = $this->formatName($target_field);
            $js_target_ins_name = $this->getJsInstanceName($target_field);
            $js_target_selector = str_replace($field_name, $target_field, $js_selector);

            if (Str::contains($target_field, '[*]')) {
                $js_target_var_name = str_replace('[*]', '', $js_target_var_name);
                $js_target_selector = str_replace('[*]', '', $js_target_selector);
                $js_target_ins_name = str_replace($field_name, $js_target_var_name, $js_ins_name);
            }
            $vars['js_target_ins_name'] = $js_target_ins_name;
            $vars['js_target_selector'] = $js_target_selector;
        }

        return $vars;
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
    public function load($target_field, $url, $idField = 'id', $textField = 'text')
    {
        $this->decorator->load($target_field, $url, $idField = 'id', $textField = 'text');

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
     *
     * @return $this
     */
    public function ajaxOptions($url, $valueField = 'id', $labelField = 'text', $parameters = [])
    {
        $this->decorator->ajaxOptions($url, $valueField, $labelField, $parameters);

        return $this;
    }

    // backwards compatible
    public function loadRemoteOptions($url, $parameters = [])
    {
        $valueField = 'id';
        $labelField = 'text';
        $this->decorator->ajaxOptions($url, $valueField, $labelField, $parameters);

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
    public function ajax($url, $valueField = 'id', $labelField = 'text')
    {
        $this->decorator->ajax($url, $valueField, $labelField);

        return $this;
    }

    /**
     * Set use browser native selectbox.
     *
     * @return $this
     */
    public function useNative()
    {
        $this->decorator(SelectNative::class);

        return $this;
    }

    /**
     * Set as readonly (actual dissable with backup hidden field).
     */
    public function readonly($set = true): self
    {
        $this->useNative();
        $this->configKey('readonly', $set);
        $this->disabled($set);

        return parent::readonly($set);
    }

    public function prepare($value)
    {
        $value = parent::prepare($value);

        return $value;
    }

    public function prepare_relation($value)
    {
        if ($this->getSetting('create') && is_array($value)) {
            $model = $this->form->model();
            if (method_exists($model, $this->column)) {
                $relation = $model->{$this->column}();
                $related  = $relation->getRelated();
                $key      = $related->getKeyName();

                $existing_ids = $related->whereIn($key, array_values($value))->get([$key])->pluck($key)->toArray();
                $missing      = array_diff($value, $existing_ids);

                foreach ($missing as $key => $myvalue) {
                    $value[$key] = $related->create([$this->getSetting('create') => $myvalue])->id;
                }
            }
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        // filter and render options
        if ($this->options instanceof \Closure) {
            if ($this->form) {
                $this->options = $this->options->bindTo($this->form->model());
            }
            $this->options(call_user_func($this->options, $this->value, $this));
        }
        $this->options = array_filter($this->options, 'strlen');

        if ($this->allowDecorator()) {
            $this->decorator->render($this);
        }

        $this->attribute('data-value', implode(',', (array) $this->value()));
        $this->addVariables([
            'settings' => $this->settings,
            'options'  => $this->options,
            'groups'   => $this->groups,
        ]);

        // cascading scripts
        $this->addCascadeScript();

        return parent::render();
    }
}
