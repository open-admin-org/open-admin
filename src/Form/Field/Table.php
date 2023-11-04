<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Exception\FieldException;
use OpenAdmin\Admin\Form\NestedForm;
use OpenAdmin\Admin\Widgets\Form as WidgetForm;

class Table extends HasMany
{
    /**
     * @var string
     */
    protected $viewMode = 'table';

    public $save_null_values = true;

    /**
     * Table constructor.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->column = $column;

        if (count($arguments) == 1) {
            $this->label   = $this->formatLabel();
            $this->builder = $arguments[0];
        }

        if (count($arguments) == 2) {
            list($this->label, $this->builder) = $arguments;
        }
    }

    /**
     * Save null values or not.
     *
     * @param bool $set
     *
     * @return $this
     */
    public function saveNullValues($set = true)
    {
        $this->save_null_values = $set;

        return $this;
    }

    /**
     * @return array
     */
    protected function buildRelatedForms()
    {
        $forms = [];

        if ($values = old($this->column)) {
            foreach ($values as $key => $data) {
                if ($data[NestedForm::REMOVE_FLAG_NAME] == 1) {
                    continue;
                }
                $data = empty($data) ? [] : $data;
                $forms[$key] = $this->buildNestedForm($this->column, $this->builder, $key)->fill($data);
            }
        } else {
            foreach ($this->value ?? [] as $key => $data) {
                if (isset($data['pivot'])) {
                    $data = array_merge($data, $data['pivot']);
                }
                $data = empty($data) ? [] : $data;
                $forms[$key] = $this->buildNestedForm($this->column, $this->builder, $key)->fill($data);
            }
        }
        return $forms;
    }

    public function prepare($input)
    {
        $form = $this->buildNestedForm($this->column, $this->builder);
        $form->setOriginal($this->original, null);
        $prepare = $form->prepare($input);

        // don't collect if empty
        if (empty($prepare)) {
            return false;
        }

        $data = collect($prepare)->reject(function ($item) {
            return Arr::get($item, NestedForm::REMOVE_FLAG_NAME) == 1;
        })->map(function ($item) {
            unset($item[NestedForm::REMOVE_FLAG_NAME]);
            return $item;
        })->toArray();

        // strip the keys
        return array_values($data);
    }

    protected function getKeyName()
    {
        if (is_null($this->form)) {
            return;
        }

        return 'id';
    }

    protected function buildNestedForm($column, \Closure $builder, $key = null)
    {
        $form = new NestedForm($column);
        $form->setJson();
        $form->saveNullValues($this->save_null_values);

        if ($this->form instanceof WidgetForm) {
            $form->setWidgetForm($this->form);
        } else {
            $form->setForm($this->form);
        }

        $form->setKey($key);
        call_user_func($builder, $form);

        $form->hidden(NestedForm::REMOVE_FLAG_NAME)->default(0)->addElementClass(NestedForm::REMOVE_FLAG_CLASS);

        return $form;
    }

    public function render()
    {
        if (!empty($this->form->model()->getRelations()[$this->column])) {
            throw new FieldException("\$form->table() is not supported for relations, use json / text field type. Or use \$form->hasMany() for relations with mode=table");
        };

        return $this->renderTable();
    }
}
