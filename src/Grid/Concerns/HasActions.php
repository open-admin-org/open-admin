<?php

namespace SuperAdmin\Admin\Grid\Concerns;

use Closure;
use SuperAdmin\Admin\Grid;

trait HasActions
{
    /**
     * Callback for grid actions.
     *
     * @var Closure
     */
    protected $actionsCallback;

    /**
     * Actions column display class.
     *
     * @var string
     */
    protected $actionsClass;

    /**
     * Set grid action callback.
     *
     * @param  Closure|string  $actions
     * @return $this
     */
    public function actions($actions)
    {
        if ($actions instanceof Closure) {
            $this->actionsCallback = $actions;
        }

        return $this;
    }

    /**
     * Get action display class.
     *
     * @return \Illuminate\Config\Repository|mixed|string
     */
    public function getActionClass()
    {
        if ($this->actionsClass) {
            return $this->actionsClass;
        }

        if ($class = config('admin.grid_action_class')) {
            return $class;
        }

        return Grid\Displayers\Acions\Actions::class;
    }

    /**
     * @return $this
     */
    public function setActionClass(string $actionClass)
    {
        if (is_subclass_of($actionClass, Grid\Displayers\Actions\Actions::class) || ($actionClass == Grid\Displayers\Actions\Actions::class)) {
            $this->actionsClass = $actionClass;
        }

        return $this;
    }

    /**
     * Disable all actions.
     *
     * @return $this
     */
    public function disableActions(bool $disable = true)
    {
        return $this->option('show_actions', ! $disable);
    }

    /**
     * Set grid batch-action callback.
     *
     *
     * @return $this
     */
    public function batchActions(Closure $closure)
    {
        $this->tools(function (Grid\Tools $tools) use ($closure) {
            $tools->batch($closure);
        });

        return $this;
    }

    /**
     * @return Grid|mixed
     */
    public function disableBatchActions(bool $disable = true)
    {
        $this->tools->disableBatchActions($disable);

        return $this->option('show_row_selector', ! $disable);
    }

    /**
     * Add `actions` column for grid.
     *
     * @return void
     */
    protected function appendActionsColumn()
    {
        if (! $this->option('show_actions')) {
            return;
        }

        $this->addColumn(Grid\Column::ACTION_COLUMN_NAME, trans('admin.action'))
            ->displayUsing($this->getActionClass(), [$this->actionsCallback]);
    }
}
