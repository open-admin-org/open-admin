<?php

namespace OpenAdmin\Admin\Actions\Interactor;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use OpenAdmin\Admin\Actions\RowAction;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form as ModalForm;
use OpenAdmin\Admin\Form\Field;
use Symfony\Component\DomCrawler\Crawler;

class Form extends Interactor
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var string
     */
    protected $modalId;

    /**
     * @var string
     */
    protected $modalSize = '';

    /**
     * @var object
     */
    public $form;

    /**
     * @var bool
     */
    public $multipart = false;

    /**
     * @var bool
     */
    public $addValues = true;

    /**
     * @var string
     */
    protected $confirm = '';

    /**
     * @return array
     */
    public function getRow()
    {
        if ($this->extendsFrom($this->action, 'OpenAdmin\Admin\Actions\RowAction')) {
            return $this->action->getRow();
        }

        return [];
    }

    public function getKey()
    {
        if ($this->extendsFrom($this->action, 'OpenAdmin\Admin\Actions\RowAction')) {
            return $this->getRow()->getKey();
        }

        return false;
    }

    /**
     * @param string $label
     *
     * @return array
     */
    protected function formatLabel($label)
    {
        return array_filter((array) $label);
    }

    /**
     * @param bool $set
     *
     * @return array
     */
    public function addValues($set = false)
    {
        $this->addValues = $set;
    }

    /**
     * @param $message
     *
     * @return $this
     */
    public function confirm($message)
    {
        $this->confirm = $message;

        return $this;
    }

    /**
     * @return $this
     */
    public function modalLarge()
    {
        $this->modalSize = 'modal-lg';

        return $this;
    }

    /**
     * @return $this
     */
    public function modalSmall()
    {
        $this->modalSize = 'modal-sm';

        return $this;
    }

    /**
     * @param string $content
     * @param string $selector
     *
     * @return string
     */
    public function addElementAttr($content, $selector)
    {
        $crawler = new Crawler($content);

        $node = $crawler->filter($selector)->getNode(0);
        $node->setAttribute('modal', $this->getModalId());

        return $crawler->children()->html();
    }

    public function getForm()
    {
        // return an actual form instance instead of the interactor
        if (empty($this->form)) {
            $this->form = new ModalForm($this);
        }

        return $this->form;
    }

    /**
     * @param Field $field
     *
     * @return Field
     */
    protected function addField(Field $field)
    {
        $elementClass = array_merge(['form-control', 'action', $this->getModalId()], $field->getElementClass());
        $field->setForm($this->getForm());
        $field->addElementClass($elementClass);
        $this->checkUploadFiel($field);

        if ($this->addValues && !empty($this->row)) {
            $value = !empty($this->row[$field->column()]) ? $this->row[$field->column()] : null;
            $field->fill([$field->column() => $value]);
        }

        array_push($this->fields, $field);

        return $field;
    }

    protected function checkUploadFiel($field)
    {
        if ($this->hasTrait($field, 'UploadField')) {
            $this->multipart = true;
        }
        if ($this->extendsFrom($field, 'OpenAdmin\Admin\Form\Field\File')) {
            $this->multipart = true;
        }
    }

    public function hasTrait($object, $traitName)
    {
        $reflection = new \ReflectionObject($object);

        return in_array($traitName, $reflection->getTraitNames());
    }

    public function extendsFrom($object, $check)
    {
        $reflection = new \ReflectionObject($object);
        $parent     = $reflection->getParentClass();

        return $parent->name == $check;
    }

    public function enableValidate()
    {
        $this->validateClientSide = true;
        $this->attribute('novalidate', true);
        $this->addFormClass('needs-validation');

        return $this;
    }

    /**
     * @param Request $request
     *
     * @throws ValidationException
     * @throws \Exception
     *
     * @return void
     */
    public function validate(Request $request)
    {
        if ($this->action instanceof RowAction) {
            call_user_func([$this->action, 'form'], $this->getRow());
        } else {
            call_user_func([$this->action, 'form']);
        }

        $failedValidators = [];

        /** @var Field $field */
        foreach ($this->fields as $field) {
            if (!$validator = $field->getValidator($request->all())) {
                continue;
            }

            if (($validator instanceof Validator) && !$validator->passes()) {
                $failedValidators[] = $validator;
            }
        }

        $message = $this->mergeValidationMessages($failedValidators);

        if ($message->any()) {
            throw ValidationException::withMessages($message->toArray());
        }
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
     * @param string $class
     *
     * @return string
     */
    protected function resolveView($class)
    {
        $path = explode('\\', $class);

        $name = strtolower(array_pop($path));

        if (!View::exists("admin::form.{$name}")) {
            $name = 'input';
        }

        return "admin::form.{$name}";
    }

    /**
     * @return void
     */
    public function addModalHtml()
    {
        $field_html    = '';
        $field_scripts = '';
        foreach ($this->fields as $field) {
            $field_html .= $field->render();
            $field_scripts .= $field->getScript();
        }

        $data = [
            'field_html'    => $field_html,
            'field_scripts' => $field_scripts,
            'multipart'     => $this->multipart,
            'title'         => $this->action->name(),
            'modal_id'      => $this->getModalId(),
            'modal_size'    => $this->modalSize,
            'method'        => $this->action->getMethod(),
            'url'           => $this->action->getHandleRoute(),
            '_key'          => $this->getKey(),
            '_action'       => $this->action->getCalledClass(),
            '_model'        => $this->action->parameters()['_model'],
        ];

        $modal = view('admin::actions.form.modal', $data)->render();

        Admin::html($modal);
    }

    /**
     * @return string
     */
    public function getModalId()
    {
        if (!$this->modalId) {
            if ($this->action instanceof RowAction) {
                $this->modalId = uniqid('row_action_modal_').mt_rand(1000, 9999);
            } else {
                $this->modalId = strtolower(str_replace('\\', '_', get_class($this->action)));
            }
        }

        return $this->modalId;
    }

    /**
     * @return string
     */
    public function preScript()
    {
        return '';
    }

    /**
     * @return void
     */
    public function addScript()
    {
        $this->row = $this->getRow();

        if ($this->action instanceof RowAction) {
            call_user_func([$this->action, 'form'], $this->row);
        } else {
            call_user_func([$this->action, 'form']);
        }
        $this->addModalHtml();

        $this->action->attribute('modal', $this->getModalId());
        $ajaxMethod = strtolower($this->action->getMethod());

        $script = <<<SCRIPT

            document.querySelectorAll('{$this->action->selector($this->action->selectorPrefix)}').forEach(el=>{
                el.addEventListener('{$this->action->event}',function(){
                    var data = el.dataset;
                    var target = el;

                    var modalId = el.getAttribute("modal");
                    var myModalEl = document.getElementById(modalId);
                    var modal = bootstrap.Modal.getOrCreateInstance(myModalEl)
                    modal.show();

                    if (myModalEl.querySelector("[name='_key']").value == ""){
                        myModalEl.querySelector("[name='_key']").value = admin.grid.selected.join();
                    }

                    myModalEl.querySelector('form').addEventListener('submit',function(e){
                        e.preventDefault();
                        var form = this;
                        admin.form.submit(form,function(data){
                            admin.actions.actionResolver([data,el]);
                        });
                        modal.hide();
                    });
                });
            });
        SCRIPT;

        Admin::script($script);

        return ' ';
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
        if ($className = ModalForm::findFieldClass($method)) {
            $column = Arr::get($arguments, 0, '');

            /* @var Field $field */
            $field = new $className($column, array_slice($arguments, 1));
            $field->setForm($this->form);
            $field = $this->addField($field);

            return $field;
        }

        return $this;
    }
}
