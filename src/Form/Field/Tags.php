<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenAdmin\Admin\Form\Field;

class Tags extends Select
{
    /**
     * @var array
     */
    protected $value = [];

    /**
     * @var bool
     */
    protected $keyAsValue = false;

    /**
     * @var string
     */
    protected $visibleColumn = null;

    /**
     * @var string
     */
    protected $key = null;

    /**
     * @var \Closure
     */
    protected $saveAction = null;

    /**
     * @var array
     */
    protected $separators = [',', ';', '，', '；', ' '];

    /**
     * {@inheritdoc}
     */
    public function fill($data)
    {
        $this->value = Arr::get($data, $this->column);

        if (is_array($this->value) && $this->keyAsValue) {
            $this->value = array_column($this->value, $this->visibleColumn, $this->key);
        }

        if (is_string($this->value)) {
            $this->value = explode(',', $this->value);
        }

        $this->value = array_filter((array) $this->value, 'strlen');
    }

    /**
     * Set visible column and key of data.
     *
     * @param $visibleColumn
     * @param $key
     *
     * @return $this
     */
    public function pluck($visibleColumn, $key)
    {
        if (!empty($visibleColumn) && !empty($key)) {
            $this->keyAsValue = true;
        }

        $this->visibleColumn = $visibleColumn;
        $this->key = $key;

        return $this;
    }

    /**
     * Set the field options.
     *
     * @param array|Collection|Arrayable $options
     *
     * @return $this|Field
     */
    public function options($options = [])
    {
        if (!$this->keyAsValue) {
            return parent::options($options);
        }

        if ($options instanceof Collection) {
            $options = $options->pluck($this->visibleColumn, $this->key)->toArray();
        }

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->options = $options + $this->options;

        return $this;
    }

    /**
     * Set Tag Separators.
     *
     * @param array $separators
     *
     * @return $this
     */
    public function separators($separators = [])
    {
        if ($separators instanceof Collection or $separators instanceof Arrayable) {
            $separators = $separators->toArray();
        }
        if (!empty($separators)) {
            $this->separators = $separators;
        }

        return $this;
    }

    /**
     * Set save Action.
     *
     * @param \Closure $saveAction
     *
     * @return $this
     */
    public function saving(\Closure $saveAction)
    {
        $this->saveAction = $saveAction;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($value)
    {
        $value = parent::prepare($value);
        if (!is_array($value)) {
            return $value;
        }
        $value = array_filter($value, 'strlen');

        if ($this->keyAsValue) {
            return is_null($this->saveAction) ? $value : ($this->saveAction)($value);
        }

        if (is_array($value) && !Arr::isAssoc($value)) {
            $value = implode(',', $value);
        }

        return $value;
    }

    /**
     * Get or set value for this field.
     *
     * @param mixed $value
     *
     * @return $this|array|mixed
     */
    public function value($value = null)
    {
        if (is_null($value)) {
            return empty($this->value) ? ($this->getDefault() ?? []) : $this->value;
        }

        $this->value = (array) $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->config = array_merge([
            'allowHTML'             => true,
            'paste'                 => true,
            'duplicateItemsAllowed' => false,
            'editItems'             => true,
        ], $this->config);

        return parent::render();
    }
}
