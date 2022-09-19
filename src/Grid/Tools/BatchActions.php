<?php

namespace OpenAdmin\Admin\Grid\Tools;

use Illuminate\Support\Collection;
use OpenAdmin\Admin\Actions\BatchAction;
use OpenAdmin\Admin\Admin;

class BatchActions extends AbstractTool
{
    /**
     * @var Collection
     */
    protected $actions;

    /**
     * @var bool
     */
    protected $enableEdit = true;

    /**
     * @var bool
     */
    protected $enableDelete = true;

    /**
     * @var bool
     */
    private $holdAll = false;

    /**
     * BatchActions constructor.
     */
    public function __construct()
    {
        $this->actions = new Collection();

        $this->appendDefaultAction();
    }

    /**
     * Append default action(batch delete action).
     *
     * return void
     */
    protected function appendDefaultAction()
    {
        $this->add(new BatchEdit());
        $this->add(new BatchDelete());
    }

    /**
     * Disable edit.
     *
     * @return $this
     */
    public function disableEdit(bool $disable = true)
    {
        $this->enableEdit = !$disable;

        return $this;
    }

    /**
     * Disable delete.
     *
     * @return $this
     */
    public function disableDelete(bool $disable = true)
    {
        $this->enableDelete = !$disable;

        return $this;
    }

    /**
     * Disable delete And Hode SelectAll Checkbox.
     *
     * @return $this
     */
    public function disableDeleteAndHodeSelectAll()
    {
        $this->enableDelete = false;

        $this->holdAll = true;

        return $this;
    }

    /**
     * Add a batch action.
     *
     * @param $name
     * @param BatchAction|null $action
     *
     * @return $this
     */
    public function add($name, BatchAction $action = null)
    {
        $id = $this->actions->count();

        if (func_num_args() == 1) {
            $action = $name;
        } elseif (func_num_args() == 2) {
            $action->setName($name);
        }

        if (method_exists($action, 'setId')) {
            $action->setId($id);
        }

        $this->actions->push($action);

        return $this;
    }

    /**
     * Setup scripts of batch actions.
     *
     * @return void
     */
    protected function addActionScripts()
    {
        $this->actions->each(function ($action) {
            $action->setGrid($this->grid);

            if (method_exists($action, 'script')) {
                Admin::script($action->script());
            }
        });
    }

    /**
     * Render BatchActions button groups.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->enableEdit) {
            $this->actions = $this->actions->filter(function ($action, $key) {
                return get_class($action) != "OpenAdmin\Admin\Grid\Tools\BatchEdit";
            });
        }

        if (!$this->enableDelete) {
            $this->actions = $this->actions->filter(function ($action, $key) {
                return get_class($action) != "OpenAdmin\Admin\Grid\Tools\BatchDelete";
            });
        }

        $this->addActionScripts();

        return Admin::component('admin::grid.batch-actions', [
            'all'     => $this->grid->getSelectAllName(),
            'row'     => $this->grid->getGridRowName(),
            'actions' => $this->actions,
            'holdAll' => $this->holdAll,
        ]);
    }
}
