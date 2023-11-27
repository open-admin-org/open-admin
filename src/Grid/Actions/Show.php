<?php

namespace OpenAdmin\Admin\Grid\Actions;

use OpenAdmin\Admin\Actions\RowAction;

class Show extends RowAction
{
    public $icon = 'icon-eye';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('admin.show');
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}";
    }


    /**
     * Render row action with a tooltip.
     *
     * @return string
     */
    public function render() {
        $linkClass = ($this->parent->getActionClass() != "OpenAdmin\Admin\Grid\Displayers\Actions\Actions") ? 'dropdown-item' : '';
        $icon = $this->getIcon();

        $tooltip = 'Show details'; //tooltip content

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
