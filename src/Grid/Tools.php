<?php

namespace SuperAdmin\Admin\Grid;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use SuperAdmin\Admin\Actions\Action;
use SuperAdmin\Admin\Actions\BatchAction;
use SuperAdmin\Admin\Actions\GridAction;
use SuperAdmin\Admin\Grid;
use SuperAdmin\Admin\Grid\Tools\AbstractTool;
use SuperAdmin\Admin\Grid\Tools\BatchActions;
use SuperAdmin\Admin\Grid\Tools\FilterButton;

class Tools implements Renderable
{
    /**
     * Parent grid.
     *
     * @var Grid
     */
    protected $grid;

    /**
     * Collection of tools.
     *
     * @var Collection
     */
    protected $tools;

    /**
     * Create a new Tools instance.
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;

        $this->tools = new Collection;

        $this->appendDefaultTools();
    }

    /**
     * Append default tools.
     */
    protected function appendDefaultTools()
    {
        $this->append(new BatchActions)
            ->append(new FilterButton);
    }

    /**
     * Append tools.
     *
     * @param  AbstractTool|string  $tool
     * @return $this
     */
    public function append($tool)
    {
        if ($tool instanceof GridAction || $tool instanceof BatchAction) {
            $tool->setGrid($this->grid);
        }

        if ($tool instanceof Action) {
            $model = $this->grid->model()->getOriginalModel();
            $model_str = str_replace('\\', '_', get_class($model));
            $tool->parameter('_model', $model_str);
        }

        $this->tools->push($tool);

        return $this;
    }

    /**
     * Prepend a tool.
     *
     * @param  AbstractTool|string  $tool
     * @return $this
     */
    public function prepend($tool)
    {
        $this->tools->prepend($tool);

        return $this;
    }

    /**
     * Disable filter button.
     *
     * @return void
     */
    public function disableFilterButton(bool $disable = true)
    {
        $this->tools = $this->tools->map(function ($tool) use ($disable) {
            if ($tool instanceof FilterButton) {
                return $tool->disable($disable);
            }

            return $tool;
        });
    }

    /**
     * Disable refresh button.
     *
     * @return void
     *
     * @deprecated
     */
    public function disableRefreshButton(bool $disable = true)
    {
        //
    }

    /**
     * Disable batch actions.
     *
     * @return void
     */
    public function disableBatchActions(bool $disable = true)
    {
        $this->tools = $this->tools->map(function ($tool) use ($disable) {
            if ($tool instanceof BatchActions) {
                return $tool->disable($disable);
            }

            return $tool;
        });
    }

    public function batch(\Closure $closure)
    {
        call_user_func($closure, $this->tools->first(function ($tool) {
            return $tool instanceof BatchActions;
        }));
    }

    /**
     * Render header tools bar.
     *
     * @return string
     */
    public function render()
    {
        return $this->tools->map(function ($tool) {
            if ($tool instanceof AbstractTool) {
                if (! $tool->allowed()) {
                    return '';
                }

                return $tool->setGrid($this->grid)->render();
            }

            if ($tool instanceof Renderable) {
                return $tool->render();
            }

            if ($tool instanceof Htmlable) {
                return $tool->toHtml();
            }

            return (string) $tool;
        })->implode(' ');
    }
}
