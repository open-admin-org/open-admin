<?php

namespace OpenAdmin\Admin\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Form\Concerns\HasFormFlags;
use OpenAdmin\Admin\Widgets\Form as WidgetForm;

/**
 * Class NestedForm.
 *
 * @method Field\Text           text($column, $label = '')
 * @method Field\Checkbox       checkbox($column, $label = '')
 * @method Field\Radio          radio($column, $label = '')
 * @method Field\Select         select($column, $label = '')
 * @method Field\MultipleSelect multipleSelect($column, $label = '')
 * @method Field\Textarea       textarea($column, $label = '')
 * @method Field\Hidden         hidden($column, $label = '')
 * @method Field\Id             id($column, $label = '')
 * @method Field\Ip             ip($column, $label = '')
 * @method Field\Url            url($column, $label = '')
 * @method Field\Color          color($column, $label = '')
 * @method Field\Email          email($column, $label = '')
 * @method Field\PhoneNumber    phonenumber($column, $label = '')
 * @method Field\Slider         slider($column, $label = '')
 * @method Field\Map            map($latitude, $longitude, $label = '')
 * @method Field\Editor         editor($column, $label = '')
 * @method Field\File           file($column, $label = '')
 * @method Field\Image          image($column, $label = '')
 * @method Field\Date           date($column, $label = '')
 * @method Field\Datetime       datetime($column, $label = '')
 * @method Field\Time           time($column, $label = '')
 * @method Field\Year           year($column, $label = '')
 * @method Field\Month          month($column, $label = '')
 * @method Field\DateRange      dateRange($start, $end, $label = '')
 * @method Field\DateTimeRange  datetimeRange($start, $end, $label = '')
 * @method Field\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\Number         number($column, $label = '')
 * @method Field\Currency       currency($column, $label = '')
 * @method Field\HasMany        hasMany($relationName, $callback)
 * @method Field\SwitchField    switch($column, $label = '')
 * @method Field\Display        display($column, $label = '')
 * @method Field\Rate           rate($column, $label = '')
 * @method Field\Divide         divider()
 * @method Field\Password       password($column, $label = '')
 * @method Field\Decimal        decimal($column, $label = '')
 * @method Field\Html           html($html, $label = '')
 * @method Field\Tags           tags($column, $label = '')
 * @method Field\Icon           icon($column, $label = '')
 * @method Field\Embeds         embeds($column, $label = '')
 */
class NestedForm
{
    use HasFormFlags;

    /**
     * @var mixed
     */
    protected $key;

    /**
     * @var string
     */
    protected $relationName;

    /**
     * NestedForm key.
     *
     * @var Model
     */
    public $model;

    /**
     * Fields in form.
     *
     * @var Collection
     */
    protected $fields;

    /**
     * Original data for this field.
     *
     * @var array
     */
    protected $original = [];

    /**
     * @var \OpenAdmin\Admin\Form|\OpenAdmin\Admin\Widgets\Form
     */
    protected $form;

    /**
     * @var bool
     */
    protected $save_null_values = true;

    /**
     * @var bool
     */
    protected $json = false;

    /**
     * Create a new NestedForm instance.
     *
     * NestedForm constructor.
     *
     * @param string $relation
     * @param Model  $model
     */
    public function __construct($relation, $model = null)
    {
        $this->relationName = $relation;

        $this->model = $model;

        $this->fields = new Collection();
    }

    /**
     * Get current model.
     *
     * @return Model|null
     */
    public function model()
    {
        return $this->model;
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
     * Handle as json form.
     *
     * @param bool $set
     *
     * @return $this
     */
    public function setJson($set = true)
    {
        $this->json = $set;

        return $this;
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed|null
     */
    public function getKey()
    {
        if ($this->model) {
            $key = $this->model->getKey();
        }

        if (!is_null($this->key)) {
            $key = $this->key;
        }

        if (isset($key)) {
            return $key;
        }

        return static::NEW_KEY_NAME.static::DEFAULT_KEY_NAME;
    }

    /**
     * Set key for current form.
     *
     * @param mixed $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set Form.
     *
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set Widget/Form.
     *
     * @param WidgetForm $form
     *
     * @return $this
     */
    public function setWidgetForm(WidgetForm $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form.
     *
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set original values for fields.
     *
     * @param array  $data
     * @param string $relatedKeyName
     *
     * @return $this
     */
    public function setOriginal($data, $relatedKeyName = null)
    {
        if (empty($data)) {
            return $this;
        }

        foreach ($data as $key => $value) {
            /*
             * like $this->original[30] = [ id = 30, .....]
             */
            if ($relatedKeyName) {
                $key = $value[$relatedKeyName];
            }

            $this->original[$key] = $value;
        }

        return $this;
    }

    /**
     * Prepare for insert or update.
     *
     * @param array $input
     *
     * @return mixed
     */
    public function prepare($input)
    {
        if (!empty($input) && is_array($input)) {
            foreach ($input as $key => $record) {
                $this->setRequestFieldKeys($key);
                $this->setFieldOriginalValue($key);
                $input[$key] = $this->prepareRecord($record);
            }
        }

        return $input;
    }

    /**
     * Set original data for each field.
     *
     * @param string $key
     *
     * @return void
     */
    protected function setFieldOriginalValue($key)
    {
        $values = [];
        if (array_key_exists($key, $this->original)) {
            $values = $this->original[$key];
        }

        $this->fields->each(function (Field $field) use ($values) {
            $field->setOriginal($values);
        });
    }

    /**
     * Set request field name and key
     *
     * @param string $key
     *
     * @return void
     */
    protected function setRequestFieldKeys($key)
    {
        $relationName = $this->relationName;
        $this->fields->each(function (Field $field) use ($key, $relationName) {
            $column = $field->column();
            if (is_array($column)) {
                $fieldKey = [];
                foreach ($column as $col) {
                    $fieldKey[] = $relationName.'.'.$key.'.'.$col;
                }
            } else {
                $fieldKey = $relationName.'.'.$key.'.'.$column;
            }
            $field->setRequestFieldKey($fieldKey);
        });
    }

    /**
     * Do prepare work before store and update.
     *
     * @param array $record
     *
     * @return array
     */
    protected function prepareRecord($record)
    {
        if (!empty($record[static::REMOVE_FLAG_NAME]) && $record[static::REMOVE_FLAG_NAME] == 1) {
            return $record;
        }

        $prepared = [];

        /* @var Field $field */
        foreach ($this->fields as $field) {
            $columns = $field->column();

            $value = $this->fetchColumnValue($record, $columns);

            if ($value === false) {
                continue;
            }

            if (method_exists($field, 'prepare')) {
                $value = $field->prepare($value);
            }

            if (
                ($field instanceof Field\Hidden)
                || ($value != $field->original() || $this->json)  // keep fields if original is the same otherwise values gets lost
                || ($this->save_null_values && $value == null)
            ) {
                if (is_array($columns)) {
                    foreach ($columns as $name => $column) {
                        Arr::set($prepared, $column, $value[$name]);
                    }
                } elseif (is_string($columns)) {
                    Arr::set($prepared, $columns, $value);
                }
            }
        }

        $prepared[static::REMOVE_FLAG_NAME] = $record[static::REMOVE_FLAG_NAME] ?? null;

        return $prepared;
    }

    /**
     * Fetch value in input data by column name.
     *
     * @param array        $data
     * @param string|array $columns
     *
     * @return array|mixed
     */
    protected function fetchColumnValue($data, $columns)
    {
        if (is_string($columns)) {
            return Arr::get($data, $columns);
        }

        if (is_array($columns)) {
            $value = [];
            foreach ($columns as $name => $column) {
                if (!Arr::has($data, $column)) {
                    continue;
                }
                $value[$name] = Arr::get($data, $column);
            }

            return $value;
        }
    }

    /**
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $this->fields->push($field);

        return $this;
    }

    /**
     * Get fields of this form.
     *
     * @return Collection
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * Fill data to all fields in form.
     *
     * @param array $data
     *
     * @return $this
     */
    public function fill(array $data = [])
    {
        /* @var Field $field */
        foreach ($this->fields() as $field) {
            $field->fill($data);
        }

        return $this;
    }

    /**
     * Get the html and script of template.
     *
     * @return array
     */
    public function getTemplateHtmlAndScript()
    {
        $html    = '';
        $scripts = [];

        /* @var Field $field */
        foreach ($this->fields() as $field) {
            //when field render, will push $script to Admin
            $html .= $field->render();

            /*
             * Get and remove the last script of Admin::$script stack.
             */
            if ($field->getScript()) {
                $scripts[] = array_pop(Admin::$script);
            }
        }

        return [$html, implode("\r\n", $scripts)];
    }

    /**
     * Set `errorKey` `elementName` `elementClass` for fields inside hasmany fields.
     *
     * @param Field $field
     *
     * @return Field
     */
    protected function formatField(Field $field)
    {
        $column = $field->column();

        $elementName = $elementClass = $errorKey = [];

        $key     = $this->getKey();
        $ref_key = is_numeric($key) ? $this->relationName.'_'.$key : $key;

        if (is_array($column)) {
            foreach ($column as $k => $name) {
                $errorKey[$k]     = sprintf('%s.%s.%s', $this->relationName, $key, $name);
                $elementName[$k]  = sprintf('%s[%s][%s]', $this->relationName, $key, $name);
                $elementClass[$k] = [$this->relationName, $ref_key, $name];
            }
        } else {
            $errorKey     = sprintf('%s.%s.%s', $this->relationName, $key, $column);
            $elementName  = sprintf('%s[%s][%s]', $this->relationName, $key, $column);
            $elementClass = [$this->relationName, $ref_key, $column];
        }

        return $field->setErrorKey($errorKey)
            ->setElementName($elementName)
            ->setElementClass($elementClass);
    }

    /**
     * Add nested-form fields dynamically.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if ($className = Form::findFieldClass($method)) {
            $column = Arr::get($arguments, 0, '');

            /* @var Field $field */
            $field = new $className($column, array_slice($arguments, 1));

            if ($this->form instanceof WidgetForm) {
                $field->setWidgetForm($this->form);
            } else {
                $field->setForm($this->form);
            }

            $field = $this->formatField($field);

            $this->pushField($field);

            return $field;
        }

        return $this;
    }
}
