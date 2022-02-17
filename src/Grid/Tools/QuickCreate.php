<?php

namespace OpenAdmin\Admin\Grid\Tools;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Grid;

class QuickCreate implements Renderable
{
    /**
     * @var Grid
     */
    protected $parent;

    /**
     * @var Collection
     */
    protected $fields;

    /**
     * QuickCreate constructor.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->parent = $grid;
        $this->fields = Collection::make();
        $this->form = new Form($grid->model());
    }

    /**
     * @param Field $field
     *
     * @return Field
     */
    protected function addField(Field $field)
    {
        $elementClass = array_merge(['quick-create', 'form-control-sm'], $field->getElementClass());

        $field->addElementClass($elementClass);
        $field->setInline(true);
        $this->fields->push($field);

        return $field;
    }

    protected function script()
    {
        $url = $this->parent->resource();

        $script = <<<'JS'
document.querySelector('.quick-create .create').addEventListener('click',function () {
    show(document.querySelector('.quick-create .create-form'),'flex');
    hide(this);
});
document.querySelector('.quick-create .cancel').addEventListener('click',function () {
    hide(document.querySelector('.quick-create .create-form'));
    show(document.querySelector('.quick-create .create'));
});

document.querySelector('.quick-create .create-form').addEventListener('submit',function (e) {

    e.preventDefault();
    var form = this;
    admin.form.submit(form,function(data){
        if (data.status == 200) {
            admin.toastr.success("Saved",{positionClass:"toast-top-center"});
            admin.ajax.reload();
            return;
        }

        if (typeof data.validation !== 'undefined') {
            admin.toastr.warning(data.message, {positionClass:"toast-top-center"})
        }
    });
    return false;
});
JS;

        Admin::script($script);
    }

    /**
     * @param int $columnCount
     *
     * @return array|string
     */
    public function render($columnCount = 0)
    {
        if ($this->fields->isEmpty()) {
            return '';
        }

        $this->script();

        $vars = [
            'columnCount' => $columnCount,
            'fields'      => $this->fields,
            'url'         => $this->parent->resource(),
        ];

        return view('admin::grid.quick-create-form', $vars)->render();
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
            $field->setForm($this->form);
            $field = $this->addField($field);

            return $field;
        }

        return $this;
    }
}
