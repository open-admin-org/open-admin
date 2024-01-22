<?php

namespace OpenAdmin\Admin\Grid\Tools;

use Illuminate\Database\Eloquent\Collection;
use OpenAdmin\Admin\Actions\BatchAction;

class BatchRestore extends BatchAction
{
    public $name = '';
    public $icon = 'icon-trash-restore';

    public function name()
    {
        return $this->name = __('admin.batch_restore');
    }

    public function handle(Collection $collection)
    {
        foreach ($collection as $model) {
            $model->restore();
        }

        return $this->response()->success(__('admin.restore_success'))->refresh();
    }

    public function dialog()
    {
        $this->confirm(__('admin.batch_restore_confirm'), '', ['icon'=>'question', 'confirmButtonText'=>__('admin.yes')]);
    }
}
