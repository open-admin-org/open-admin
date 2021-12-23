<?php

namespace OpenAdmin\Admin\Actions;

use Illuminate\Http\Request;

abstract class BatchAction extends GridAction
{
    /**
     * @var string
     */
    public $selectorPrefix = '.grid-batch-action-';

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function retrieveModel(Request $request)
    {
        if (!$key = $request->input('_key')) {
            return false;
        }

        $modelClass = str_replace('_', '\\', $request->get('_model'));

        if (is_string($key)) {
            $key = explode(',', $key);
        }

        if ($this->modelUseSoftDeletes($modelClass)) {
            return $modelClass::withTrashed()->findOrFail($key);
        }

        return $modelClass::findOrFail($key);
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->addScript();

        $modalId = '';
        if ($this->interactor instanceof Interactor\Form) {
            $modalId = $this->interactor->getModalId();

            if ($content = $this->html()) {
                return $this->interactor->addElementAttr($content, $this->selector);
            }
        }

        $icon = $this->getIcon();

        $res = $this->html();
        if (!empty($res)) {
            return $res;
        }

        return sprintf(
            "<a href='javascript:void(0);' class='%s dropdown-item batch-action' %s>{$icon}%s</a>",
            $this->getElementClass(),
            $modalId ? "modal='{$modalId}'" : '',
            $this->name()
        );
    }
}
