<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MultipleSelect extends Select
{
    /**
     * Other key for many-to-many relation.
     *
     * @var string
     */
    protected $otherKey;

    public $must_prepare = true;

    public function __construct($column, $arguments = [])
    {
        $this->configKey('removeItemButton', true);

        parent::__construct($column, $arguments);
    }

    /**
     * Get other key for this many-to-many relation.
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getOtherKey()
    {
        if ($this->otherKey) {
            return $this->otherKey;
        }

        if (is_callable([$this->form->model(), $this->column])
            && ($relation = $this->form->model()->{$this->column}()) instanceof BelongsToMany
        ) {
            /* @var BelongsToMany $relation */
            $fullKey      = $relation->getQualifiedRelatedPivotKeyName();
            $fullKeyArray = explode('.', $fullKey);

            return $this->otherKey = end($fullKeyArray);
        }

        throw new \Exception('Column of this field must be a `BelongsToMany` or `HasMany` relation.');
    }

    /**
     * @inheritdoc
     */
    public function fill($data)
    {
        if ($this->form && $this->form->shouldSnakeAttributes()) {
            $key = Str::snake($this->column);
        } else {
            $key = $this->column;
        }

        $relations = Arr::get($data, $key);

        if (is_string($relations)) {
            $this->value = json_decode($relations);
            if (!is_array($this->value)) {
                $this->value = explode(',', $relations);
            }
        }

        if (!is_array($relations)) {
            $this->applyCascadeConditions();

            return;
        }

        $first = current($relations);

        if (is_null($first)) {
            $this->value = null;

            // MultipleSelect value store as an ont-to-many relationship.
        } elseif (is_array($first)) {
            foreach ($relations as $relation) {
                $this->value[] = Arr::get($relation, "pivot.{$this->getOtherKey()}");
            }

            // MultipleSelect value store as a column.
        } else {
            $this->value = $relations;
        }

        $this->applyCascadeConditions();
    }

    /**
     * @inheritdoc
     */
    public function setOriginal($data)
    {
        $relations = Arr::get($data, $this->column);

        if (is_string($relations)) {
            $this->original = explode(',', $relations);
        }

        if (!is_array($relations)) {
            return;
        }

        $first = current($relations);

        if (is_null($first)) {
            $this->original = null;

            // MultipleSelect value store as an ont-to-many relationship.
        } elseif (is_array($first)) {
            foreach ($relations as $relation) {
                $this->original[] = Arr::get($relation, "pivot.{$this->getOtherKey()}");
            }

            // MultipleSelect value store as a column.
        } else {
            $this->original = $relations;
        }
    }

    public function prepare($value)
    {
        $value = parent::prepare($value);
        $value = (array) $value;

        return array_filter($value, 'strlen');
    }
}
