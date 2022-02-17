<?php

namespace OpenAdmin\Admin\Form\Field\Traits;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form\Field;

/**
 * @mixin Field
 */
trait HasMediaPicker
{
    public $picker = false;
    public $picker_path = '';

    /**
     * @param string $picker
     * @param string $column
     *
     * @return $this
     */
    public function pick($path = '')
    {
        if ($path != '') {
            $this->picker_path = '&path='.$path;
        }
        $this->picker = 'one';
        $this->retainable(true);

        return $this;
    }

    /**
     * @param \Closure|null $callback
     */
    protected function addPickBtn(\Closure $callback = null)
    {
        $text = admin_trans('admin.choose');

        $btn = <<<HTML
            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#{$this->modal}">
                <i class="icon-folder-open"></i>  {$text}
            </a>
        HTML;

        if ($callback) {
            $callback($btn);
        } else {
            $this->addVariables(compact('btn'));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    protected function renderMediaPicker()
    {
        if (!class_exists("OpenAdmin\Admin\Media\MediaManager")) {
            throw new \Exception(
                '[Media Manager extention not installed yet.<br> Install using: <b>composer require open-admin-ext/media-manager</b><br><br>'
            );
        }

        $this->modal = sprintf('media-picker-modal-%s', $this->getElementClassString());
        $this->addVariables([
            'modal'       => $this->modal,
            'selector'    => $this->getElementClassString(),
            'name'        => $this->formatName($this->column),
            'multiple'    => !empty($this->multiple),
            'picker_path' => $this->picker_path,
            'trans'       => [
                'choose' => admin_trans('admin.choose'),
                'cancal' => admin_trans('admin.cancel'),
                'submit' => admin_trans('admin.submit'),
            ],
        ]);

        $this->addPickBtn();

        return Admin::component('admin::components.mediapicker', $this->variables());
    }
}
