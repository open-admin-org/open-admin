<?php

namespace SuperAdmin\Admin\Grid\Tools;

use SuperAdmin\Admin\Admin;
use SuperAdmin\Admin\Grid;

class ExportButton extends AbstractTool
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Create a new Export button instance.
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Render Export button.
     *
     * @return string
     */
    public function render()
    {
        if (! $this->grid->showExportBtn()) {
            return '';
        }
        $page = request('page', 1);

        return Admin::component('admin::components.export-btn', [
            'page' => $page,
            'grid' => $this->grid,
        ]);
    }
}
