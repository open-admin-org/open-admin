<?php

namespace OpenAdmin\Admin\Grid\Actions;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\Response;
use OpenAdmin\Admin\Actions\RowAction;

class Restore extends RowAction
{
    public $name = 'Restore';
    public $icon = 'icon-trash-restore';

    public function handle(Model $model): Response
    {
        // $model ...
        $model->restore();
        return $this->response()->success('Restored Successfully')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure, you want to restore?', '', ['icon'=>'question', 'confirmButtonText'=>'Yes']);
    }
}
