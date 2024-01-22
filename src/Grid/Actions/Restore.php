<?php

namespace OpenAdmin\Admin\Grid\Actions;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\Response;
use OpenAdmin\Admin\Actions\RowAction;

class Restore extends RowAction
{
    public $name = '';
    public $icon = 'icon-trash-restore';

    public function name()
    {
        return $this->name = __('admin.restore');
    }

    public function handle(Model $model): Response
    {
        // $model ...
        $model->restore();

        return $this->response()->success(__('admin.restore_success'))->refresh();
    }

    public function dialog()
    {
        $this->confirm(__('admin.restore_confirm'), '', ['icon'=>'question', 'confirmButtonText'=>__('admin.yes')]);
    }
}
