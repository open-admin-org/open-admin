<?php

namespace OpenAdmin\Admin\Grid\Tools;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Grid;

class PerPageSelector extends AbstractTool
{
    /**
     * @var string
     */
    protected $perPage;

    /**
     * @var string
     */
    protected $perPageName = '';

    /**
     * Create a new PerPageSelector instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;

        $this->initialize();
    }

    /**
     * Do initialize work.
     *
     * @return void
     */
    protected function initialize()
    {
        $this->perPageName = $this->grid->model()->getPerPageName();

        $this->perPage = (int) \request()->input(
            $this->perPageName,
            $this->grid->perPage
        );
    }

    /**
     * Get options for selector.
     *
     * @return static
     */
    public function getOptions()
    {
        return collect($this->grid->perPages)
            ->push($this->grid->perPage)
            ->push($this->perPage)
            ->unique()
            ->sort();
    }

    /**
     * Render PerPageSelector。
     *
     * @return string
     */
    public function render()
    {
        Admin::script($this->script());

        $options = $this->getOptions()->map(function ($option) {
            $selected = ($option == $this->perPage) ? 'selected' : '';
            $url = \request()->fullUrlWithQuery([$this->perPageName => $option]);

            return "<option value=\"$url\" $selected>$option</option>";
        })->implode("\r\n");

        $trans = [
            'show'    => trans('admin.show'),
            'entries' => trans('admin.entries'),
        ];

        return <<<HTML
<label class="form-group pull-right d-flex align-items-center" style="margin-right: 10px; font-weight: 100;">
        <small>{$trans['show']}</small>&nbsp;
        <select class="form-select form-select-sm {$this->grid->getPerPageName()}" name="per-page">
            $options
        </select>
        &nbsp;<small>{$trans['entries']}</small>
    </label>
HTML;
    }

    /**
     * Script of PerPageSelector.
     *
     * @return string
     */
    protected function script()
    {
        return <<<JS
document.querySelector('.{$this->grid->getPerPageName()}').addEventListener("change", function(e) {
    admin.ajax.navigate(this.value);
});
JS;
    }
}
