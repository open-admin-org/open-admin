<?php

namespace OpenAdmin\Admin\Widgets;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Form as BaseForm;
use OpenAdmin\Admin\Form\Concerns\HasFormAttributes;
use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Layout\Content;

/**
 * Class Form.
 *
 * @method Field\Text           text($name, $label = '')
 * @method Field\Password       password($name, $label = '')
 * @method Field\Checkbox       checkbox($name, $label = '')
 * @method Field\CheckboxButton checkboxButton($name, $label = '')
 * @method Field\CheckboxCard   checkboxCard($name, $label = '')
 * @method Field\Radio          radio($name, $label = '')
 * @method Field\RadioButton    radioButton($name, $label = '')
 * @method Field\RadioCard      radioCard($name, $label = '')
 * @method Field\Select         select($name, $label = '')
 * @method Field\MultipleSelect multipleSelect($name, $label = '')
 * @method Field\Textarea       textarea($name, $label = '')
 * @method Field\Hidden         hidden($name, $label = '')
 * @method Field\Id             id($name, $label = '')
 * @method Field\Ip             ip($name, $label = '')
 * @method Field\Url            url($name, $label = '')
 * @method Field\Color          color($name, $label = '')
 * @method Field\Email          email($name, $label = '')
 * @method Field\PhoneNumber    phonenumber($name, $label = '')
 * @method Field\Slider         slider($name, $label = '')
 * @method Field\File           file($name, $label = '')
 * @method Field\Image          image($name, $label = '')
 * @method Field\Date           date($name, $label = '')
 * @method Field\Datetime       datetime($name, $label = '')
 * @method Field\Time           time($name, $label = '')
 * @method Field\Year           year($column, $label = '')
 * @method Field\Month          month($column, $label = '')
 * @method Field\DateRange      dateRange($start, $end, $label = '')
 * @method Field\DateTimeRange  dateTimeRange($start, $end, $label = '')
 * @method Field\TimeRange      timeRange($start, $end, $label = '')
 * @method Field\Number         number($name, $label = '')
 * @method Field\Currency       currency($name, $label = '')
 * @method Field\SwitchField    switch($name, $label = '')
 * @method Field\Display        display($name, $label = '')
 * @method Field\Rate           rate($name, $label = '')
 * @method Field\Divider        divider($title = '')
 * @method Field\Decimal        decimal($column, $label = '')
 * @method Field\Html           html($html)
 * @method Field\Tags           tags($column, $label = '')
 * @method Field\Icon           icon($column, $label = '')
 * @method Field\Captcha        captcha($column, $label = '')
 * @method Field\Listbox        listbox($column, $label = '')
 * @method Field\Table          table($column, $label, $builder)
 * @method Field\Timezone       timezone($column, $label = '')
 * @method Field\KeyValue       keyValue($column, $label = '')
 * @method Field\ListField      list($column, $label = '')
 * @method mixed                handle(Request $request)
 */
class Form implements Renderable
{
    use BaseForm\Concerns\HandleCascadeFields;
    use HasFormAttributes;

    /**
     * The title of form.
     *
     * @var string
     */
    public $title;

    /**
     * The description of form.
     *
     * @var string
     */
    public $description;

    /**
     * @var Field[]
     */
    public $fields = [];

    /**
     * @var array
     */
    public $data = [];

    /**
     * Available buttons.
     *
     * @var array
     */
    protected $buttons = ['reset', 'submit'];

    /**
     * Width for label and submit field.
     *
     * @var array
     */
    protected $width = [
        'label' => 2,
        'field' => 8,
    ];

    /**
     * @var bool
     */
    public $inbox = true;

    /**
     * @var string
     */
    public $confirm = '';

    /**
     * @var Form
     */
    protected $form;

    /**
     * Form constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->fill($data);
        $this->initFormAttributes();
        $this->form_classes[] = 'card';
    }

    /**
     * Get form title.
     *
     * @return mixed
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get form description.
     *
     * @return mixed
     */
    public function description()
    {
        return $this->description ?: ' ';
    }

    /**
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function confirm($message)
    {
        $this->confirm = $message;

        return $this;
    }

    /**
     * Fill data to form fields.
     *
     * @param array $data
     *
     * @return $this
     */
    public function fill($data = [])
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        if (!empty($data)) {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function sanitize()
    {
        foreach (['_form_', '_token'] as $key) {
            request()->request->remove($key);
        }

        return $this;
    }

    /**
     * Disable reset button.
     *
     * @return $this
     */
    public function disableReset()
    {
        array_delete($this->buttons, 'reset');

        return $this;
    }

    /**
     * Disable submit button.
     *
     * @return $this
     */
    public function disableSubmit()
    {
        array_delete($this->buttons, 'submit');

        return $this;
    }

    /**
     * Set field and label width in current form.
     *
     * @param int $fieldWidth
     * @param int $labelWidth
     *
     * @return $this
     */
    public function setWidth($fieldWidth = 8, $labelWidth = 2)
    {
        collect($this->fields)->each(function ($field) use ($fieldWidth, $labelWidth) {
            /* @var Field $field  */
            $field->setWidth($fieldWidth, $labelWidth);
        });

        // set this width
        $this->width = [
            'label' => $labelWidth,
            'field' => $fieldWidth,
        ];

        return $this;
    }

    /**
     * Determine if the form has field type.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasField($name)
    {
        return isset(BaseForm::$availableFields[$name]);
    }

    /**
     * Add a form field to form.
     *
     * @param Field $field
     *
     * @return $this
     */
    public function pushField(Field $field)
    {
        $field->setWidgetForm($this);

        array_push($this->fields, $field);

        return $this;
    }

    /**
     * Get all fields of form.
     *
     * @return Field[]
     */
    public function fields()
    {
        return collect($this->fields);
    }

    /**
     * Get variables for render form.
     *
     * @return array
     */
    protected function getVariables()
    {
        $this->fields()->each->fill($this->data());

        return [
            'title'      => $this->title,
            'fields'     => $this->fields,
            'attributes' => $this->formatAttribute(),
            'method'     => $this->attributes['method'],
            'buttons'    => $this->buttons,
            'width'      => $this->width,
        ];
    }

    /**
     * Determine if form fields has files.
     *
     * @return bool
     */
    public function hasFile()
    {
        foreach ($this->fields as $field) {
            if ($field instanceof Field\File) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate this form fields.
     *
     * @param Request $request
     *
     * @return bool|MessageBag
     */
    public function validate(Request $request)
    {
        if (method_exists($this, 'form')) {
            $this->form();
        }

        $failedValidators = [];

        /** @var Field $field */
        foreach ($this->fields() as $field) {
            if (!$validator = $field->getValidator($request->all())) {
                continue;
            }

            if (($validator instanceof Validator) && !$validator->passes()) {
                $failedValidators[] = $validator;
            }
        }

        $message = $this->mergeValidationMessages($failedValidators);

        return $message->any() ? $message : false;
    }

    /**
     * Merge validation messages from input validators.
     *
     * @param \Illuminate\Validation\Validator[] $validators
     *
     * @return MessageBag
     */
    protected function mergeValidationMessages($validators)
    {
        $messageBag = new MessageBag();

        foreach ($validators as $validator) {
            $messageBag = $messageBag->merge($validator->messages());
        }

        return $messageBag;
    }

    /**
     * Add a fieldset to form.
     *
     * @param string  $title
     * @param Closure $setCallback
     *
     * @return Field\Fieldset
     */
    public function fieldset(string $title, Closure $setCallback)
    {
        $fieldset = new Field\Fieldset();

        $this->html($fieldset->start($title))->plain();

        $setCallback($this);

        $this->html($fieldset->end())->plain();

        return $fieldset;
    }

    /**
     * @return $this
     */
    public function unbox()
    {
        $this->inbox = false;

        return $this;
    }

    protected function addConfirmScript()
    {
        $id = $this->attributes['id'];

        $trans = [
            'cancel' => trans('admin.cancel'),
            'submit' => trans('admin.submit'),
        ];

        $settings = [
            'type'                => 'question',
            'showCancelButton'    => true,
            'confirmButtonText'   => $trans['submit'],
            'cancelButtonText'    => $trans['cancel'],
            'title'               => $this->confirm,
            'text'                => '',
        ];

        $settings = trim(json_encode($settings, JSON_PRETTY_PRINT));

        $script = <<<JS

        var confirmSubmit = function(e) {
            e.preventDefault();

            var form = e.target.closest('form');
            Swal.fire($settings).then(function (result) {
                if (result.value == true) {
                    if (admin.form.validate(form)){
                        form.dispatchEvent(new Event('submit', { cancelable: true }));
                    }
                }
            });
            return false;

        };
        document.querySelector('form#{$id} button[type=submit]').removeEventListener("click", confirmSubmit);
        document.querySelector('form#{$id} button[type=submit]').addEventListener("click", confirmSubmit);

JS;

        Admin::script($script);
    }

    protected function addCascadeScript()
    {
        $id = $this->attributes['id'];

        $script = <<<SCRIPT
        admin.form.disable_cascaded_forms("form#{$id}");
SCRIPT;

        Admin::script($script);
    }

    protected function prepareForm()
    {
        if (method_exists($this, 'form')) {
            $this->form();
        }

        if (!empty($this->confirm)) {
            $this->addConfirmScript();
        }

        $this->addCascadeScript();
    }

    protected function prepareHandle()
    {
        if (method_exists($this, 'handle')) {
            $this->method('POST');
            $this->action(admin_url('_handle_form_'));
            $this->hidden('_form_')->default(get_called_class());
        }
    }

    /**
     * Render the form.
     *
     * @return string
     */
    public function render()
    {
        $this->prepareForm();

        $this->prepareHandle();

        $form = view('admin::widgets.form', $this->getVariables())->render();

        if (!$this->title || !$this->inbox) {
            return $form;
        }

        return (new Box($this->title, $form))->render();
    }

    /**
     * Generate a Field object and add to form builder if Field exists.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return Field|$this
     */
    public function __call($method, $arguments)
    {
        if (!$this->hasField($method)) {
            return $this;
        }

        $class = BaseForm::$availableFields[$method];

        $field = new $class(Arr::get($arguments, 0), array_slice($arguments, 1));

        return tap($field, function ($field) {
            $this->pushField($field);
        });
    }

    /**
     * @param Content $content
     *
     * @return Content
     */
    public function __invoke(Content $content)
    {
        return $content->title($this->title())
            ->description($this->description())
            ->body($this);
    }
}
