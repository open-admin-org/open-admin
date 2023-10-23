<?php

namespace OpenAdmin\Admin;

use OpenAdmin\Admin\Form\Field\Hidden;

/**
 * @mixin Grid
 */
abstract class Resourceable
{
    /**
     * @var string
     */
    public $model;

    /**
     * @var string
     */
    protected $key = '';

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * @var int
     */
    protected $perPage = 10;

    /**
     * @var string
     */
    public static $display_field = 'id';

    /**
     * @var string
     */
    public static $labelClass = '';

    /**
     * @var bool
     */
    protected $hideRelationColumn = true;

    /**
     * @var string
     */
    public static $seperator = ', ';
    public $controller;
    public $grid;
    public $show;
    public $form;
    public $resource_url;
    public $relation_field;
    public $parent_id;

    /**
     * Selectable constructor.
     *
     * @param $key
     * @param $multiple
     */
    public function __construct($key = '')
    {
        $this->key = $key ?: $this->key;

        $this->make();
    }

    /**
     * Make function to create grid,show & form inside resourable
     *
     * @return null|mixed
     */
    abstract public function make();

    public function resourceFromController($controller)
    {
        $this->controller = $controller;
    }

    public function getGrid()
    {
        if (!empty($this->controller)) {
            return $this->controller->getGrid();
        }

        if (!empty($this->grid)) {
            return $this->grid;
        }

        throw new \Exception("From not found on [{$this}] make sure make function creates grid");
    }

    public function getShow($id)
    {
        if (!empty($this->controller)) {
            return $this->controller->getShow($id);
        }

        if (!empty($this->show)) {
            return $this->show;
        }

        throw new \Exception("From not found on [{$this}] make sure make function creates grid");
    }

    public function getForm()
    {
        if (!empty($this->controller)) {
            return $this->controller->getForm();
        }

        if (!empty($this->form)) {
            return $this->form;
        }

        throw new \Exception("From not found on [{$this}] make sure make function creates form");
    }

    public function handle($arguments = [])
    {
        $this->make();

        $this->parent_id = $arguments['parent_id'];
        $actions_args    = explode('/', trim($arguments['action'], '/'));
        $action          = array_pop($actions_args);
        $method          = request()->_method;

        if ($action == 'create') {
            return $this->create();
        }

        if ($action == 'edit') {
            $id = array_pop($actions_args);

            return $this->edit($id);
        }

        if ($method == 'delete') {
            $id = $action;

            return $this->getForm()->destroy($id);
        }

        if (intval($action)) {
            return $this->show($action);
        }

        return $this->index($arguments['parent_id']);
    }

    public function getFormWithParentId()
    {
        $parent_id = $this->parent_id;
        $form      = $this->getForm();
        $form->callFieldByColumn($this->relation_field, function ($field) use ($parent_id) {
            $field->value($parent_id);
            $field->readonly(true);
        });
        $form->setFieldsPrependClass('inside-modal');
        $form->builder->addHiddenField((new Hidden('after-save-url'))->value($this->getLoadUrl($this->parent_id)));

        return $form;
    }

    public function create()
    {
        $form = $this->getFormWithParentId();
        $form->setResourcePath($this->resource_url.'/create');

        return $form->render().Admin::script();
    }

    public function edit($id)
    {
        $form = $this->getFormWithParentId();
        $form->setResourcePath($this->resource_url.'/'.$id.'/edit');

        return $form->edit($id)->render().Admin::script();
    }

    public function show($id)
    {
        return $this->getShow($id);
    }

    public function index($parent_id)
    {
        return $this->makeGrid($parent_id)->render();
    }

    public function getLoadUrl($parent_id)
    {
        $resourceable = str_replace('\\', '_', get_class($this));
        $args         = ['parent_id' => $parent_id, 'action' => ''];

        return urldecode(route('admin.handle-resourceable', compact('resourceable', 'args')));
    }

    public function makeGrid($parent_id)
    {
        $grid = $this->getGrid();
        $grid->model()->where($this->relation_field, $parent_id);
        $grid->resource(urldecode($this->getLoadUrl($parent_id)));
        $grid->disableFilter();
        $grid->disableRowSelector();
        $grid->disableExport();
        $grid->disableColumnSelector();

        if ($this->hideRelationColumn) {
            $grid->hideColumns($this->relation_field);
        }

        return $grid;
    }
}
