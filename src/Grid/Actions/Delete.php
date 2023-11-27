<?php

namespace OpenAdmin\Admin\Grid\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OpenAdmin\Admin\Actions\Response;
use OpenAdmin\Admin\Actions\RowAction;

class Delete extends RowAction
{
    public $icon = 'icon-trash';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('admin.delete');
    }

    public function addScript()
    {
        $this->attributes = [
            'onclick' => 'admin.resource.delete(event,this)',
            'data-url'=> "{$this->getResource()}/{$this->getKey()}",
        ];
    }

    /*
    // could use dialog as well instead of addScript
    public function dialog()
    {
        $options  = [
            "type" => "warning",
            "showCancelButton"=> true,
            "confirmButtonColor"=> "#DD6B55",
            "confirmButtonText"=> __('confirm'),
            "showLoaderOnConfirm"=> true,
            "cancelButtonText"=>  __('cancel'),
        ];
        $this->confirm('Are you sure delete?', '', $options);
    }
    */

    /**
     * @param Model $model
     *
     * @return Response
     */
    public function handle(Model $model)
    {
        $trans = [
            'failed'    => trans('admin.delete_failed'),
            'succeeded' => trans('admin.delete_succeeded'),
        ];

        try {
            DB::transaction(function () use ($model) {
                $model->delete();
            });
        } catch (\Exception $exception) {
            return $this->response()->error("{$trans['failed']} : {$exception->getMessage()}");
        }

        return $this->response()->success($trans['succeeded'])->refresh();
    }


    /**
     * Render row action with a tooltip.
     *
     * @return string
     */
    public function render() {
        $linkClass = ($this->parent->getActionClass() != "OpenAdmin\Admin\Grid\Displayers\Actions\Actions") ? 'dropdown-item' : '';
        $icon = $this->getIcon();

        $tooltip = 'Delete'; //tooltip content

        if ($href = $this->href()) {
            return "<a href='{$href}' class='{$linkClass}' title='{$tooltip}'>{$icon}<span class='label'>{$this->name()}</span></a>";
        }

        $this->addScript();

        $attributes = $this->formatAttributes();

        return sprintf(
            "<a data-_key='%s' href='javascript:void(0);' class='%s {$linkClass}' {$attributes} title='{$tooltip}'>{$icon}<span class='label'>%s</span></a>",
            $this->getKey(),
            $this->getElementClass(),
            $this->asColumn ? $this->display($this->row($this->column->getName())) : $this->name()
        );
    }

}
