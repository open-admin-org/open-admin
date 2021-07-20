<?php

namespace OpenAdmin\Admin\Grid\Tools;

use Illuminate\Support\Facades\URL;

class BatchEdit extends BatchAction
{
    public function __construct($title)
    {
        $this->title = $title;
    }

    public function buildBatchUrl($resourcesPath)
    {

        // continue editing with ids in id row
        $parts = parse_url(request('_previous_'));
        $current = URL::current();
        $last_arg = last(explode('/', $current));

        parse_str($parts['query'], $get_data);
        $ids = $get_data['ids'];

        $next_id = array_shift($ids);
        if ($last_arg == $next_id) {
            return $resourcesPath;
        }
        $url = rtrim($resourcesPath, '/')."/{$next_id}/edit";
        if (count($ids)) {
            $url .= '?ids[]='.implode('&ids[]', $ids);
        }

        return $url;
    }

    /**
     * Script of batch delete action.
     */
    public function script()
    {
        return <<<EOT
        document.querySelector('{$this->getElementClass()}').addEventListener("click",function(){
            let resource_url = '{$this->resource}/' + admin.grid.selected.join();
            admin.resource.batch_edit(resource_url);
        });
EOT;
    }
}
