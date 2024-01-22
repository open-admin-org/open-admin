<?php

namespace OpenAdmin\Admin\Grid\Tools;

use Illuminate\Database\Eloquent\Collection;
use OpenAdmin\Admin\Actions\BatchAction;

class BatchRestore extends BatchAction
{
    public $name = 'Batch Restore';
    public $icon = 'icon-trash-restore';

    public function handle(Collection $collection)
    {
        foreach ($collection as $model) {
            $model->restore();
        }

        return $this->response()->success('Restored Successfully')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure, you want to restore all selected?', '', ['icon'=>'question','confirmButtonText'=>'Yes']);
    }
}
