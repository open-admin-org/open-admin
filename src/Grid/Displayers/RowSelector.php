<?php

namespace OpenAdmin\Admin\Grid\Displayers;

class RowSelector extends AbstractDisplayer
{
    public function display()
    {
        return <<<HTML
<input type="checkbox" class="{$this->grid->getGridRowName()}-checkbox form-check-input row-selector" data-id="{$this->getKey()}" onchange="admin.grid.select_row(event,this)" autocomplete="off"/>
HTML;
    }
}
