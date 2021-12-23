<?php

namespace OpenAdmin\Admin\Grid\Tools;

class BatchDelete extends BatchAction
{
    public $icon = "icon-trash";

    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * Script of batch delete action.
     */
    public function script()
    {
        return <<<EOT
        document.querySelector('{$this->getElementClass()}').addEventListener("click",function(){
            let resource_url = '{$this->resource}/' + admin.grid.selected.join();
            admin.resource.batch_delete(resource_url);
        });
EOT;
    }
}
