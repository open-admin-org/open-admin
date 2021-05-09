<?php

namespace OpenAdmin\Admin\Grid\Tools;

class BatchDelete extends BatchAction
{
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
            admin.resource.delete_batch(resource_url);
        });
EOT;
    }
}
