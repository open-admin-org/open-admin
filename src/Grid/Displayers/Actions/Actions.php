<?php

namespace OpenAdmin\Admin\Grid\Displayers\Actions;

use OpenAdmin\Admin\Actions\RowAction;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Grid\Actions\Delete;
use OpenAdmin\Admin\Grid\Actions\Edit;
use OpenAdmin\Admin\Grid\Actions\Show;
use OpenAdmin\Admin\Grid\Displayers\AbstractDisplayer;

class Actions extends AbstractDisplayer
{
    protected $view = 'admin::grid.actions.actions';

    /**
     * @var array
     */
    protected $custom = [];

    /**
     * @var array
     */
    protected $default = [];

    /**
     * @var array
     */
    protected $defaultClass = [Edit::class, Show::class, Delete::class];

    /**
     * @var string
     */
    protected $resource;

    /**
     * Disable all actions.
     *
     * @var bool
     */
    protected $disableAll = false;

    /**
     * Show hide labels.
     *
     * @var bool
     */
    public $showLabels = false;

    /**
     * Show hide actionsColumn.
     *
     * @var bool
     */
    public $hideActionsColumn = false;

    /**
     * diy translate.
     *
     * @var array
     */
    protected $trans = [];

    /**
     * Get route key name of current row.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->row->{$this->row->getRouteKeyName()};
    }

    /**
     * Disable all actions.
     *
     * @return $this
     */
    public function disableAll()
    {
        $this->disableAll = true;

        return $this;
    }

    /**
     * Show hide Labels.
     *
     * @return $this
     */
    public function showLabels($default = true)
    {
        $this->showLabels = $default;

        return $this;
    }

    /**
     * Set resource of current resource.
     *
     * @param $resource
     *
     * @return $this
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource of current resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource ?: parent::getResource();
    }

    /**
     * @param RowAction $action
     *
     * @return $this
     */
    public function add(RowAction $action)
    {
        $this->prepareAction($action);

        array_push($this->custom, $action);

        return $this;
    }

    /**
     * @param RowAction $action
     *
     * @return $this
     */
    public function pre(RowAction $action)
    {
        $this->prepareAction($action);

        array_unshift($this->default, $action);

        return $this;
    }

    /**
     * Prepend default `edit` `view` `delete` actions.
     */
    protected function prependDefaultActions()
    {
        foreach ($this->defaultClass as $class) {
            /** @var RowAction $action */
            $action = new $class();

            $this->prepareAction($action);

            array_push($this->default, $action);
        }
    }

    /**
     * @param RowAction $action
     */
    protected function prepareAction(RowAction $action)
    {
        $action->setGrid($this->grid)
            ->setColumn($this->column)
            ->setRow($this->row);
    }

    /**
     * Disable view action.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableView(bool $disable = true)
    {
        $this->disableShow($disable);
    }

    public function disableShow(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, Show::class);
        } elseif (!in_array(Show::class, $this->defaultClass)) {
            array_push($this->defaultClass, Show::class);
        }

        return $this;
    }

    /**
     * Disable delete.
     *
     * @param bool $disable
     *
     * @return $this.
     */
    public function disableDelete(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, Delete::class);
        } elseif (!in_array(Delete::class, $this->defaultClass)) {
            array_push($this->defaultClass, Delete::class);
        }

        return $this;
    }

    /**
     * Disable edit.
     *
     * @param bool $disable
     *
     * @return $this
     */
    public function disableEdit(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->defaultClass, Edit::class);
        } elseif (!in_array(Edit::class, $this->defaultClass)) {
            array_push($this->defaultClass, Edit::class);
        }

        return $this;
    }

    /**
     * @param null|\Closure $callback
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function display($callback = null)
    {
        if ($callback instanceof \Closure) {
            $callback->call($this, $this);
        }

        if ($this->disableAll) {
            return '';
        }

        $this->prependDefaultActions();

        $variables = [
            'default'           => $this->default,
            'custom'            => $this->custom,
            'showLabels'        => $this->showLabels,
            'hideActionsColumn' => $this->hideActionsColumn,
            'key'               => $this->getRouteKey(),
        ];

        if (empty($variables['default']) && empty($variables['custom'])) {
            return;
        }

        return Admin::component($this->view, $variables);
    }
}
