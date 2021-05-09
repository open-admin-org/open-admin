<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use OpenAdmin\Admin\Admin;

class RowSelector extends AbstractDisplayer
{
    public function display()
    {

        return <<<EOT
<input type="checkbox" class="{$this->grid->getGridRowName()}-checkbox form-check-input" data-id="{$this->getKey()}" onchange="admin.grid.select_row(event,this)" autocomplete="off"/>
EOT;
    }
}
