<?php

namespace OpenAdmin\Admin\Controllers;

use OpenAdmin\Admin\Grid\Actions\Restore;
use OpenAdmin\Admin\Grid\Tools\BatchRestore;

trait HasRecycleBin
{
    public function __construct()
    {
        // register hooks

        $this->registerHook('alterGrid', function ($controller, $grid) {
            $grid->filter(function ($filter) {
                $filter->scope('trashed', 'Recycle Bin')->onlyTrashed();
            });

            $grid->actions(function ($actions) {
                if (request('_scope_') == 'trashed') {
                    $actions->add(new Restore());
                }
                return $actions;
            });

            $grid->batchActions (function ($batch) {
                if (request('_scope_') == 'trashed') {
                    $batch->add(new BatchRestore());
                }
            });

            return $grid;
        });
    }
}
