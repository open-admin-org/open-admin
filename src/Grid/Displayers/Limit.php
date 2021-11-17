<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use Illuminate\Support\Str;
use OpenAdmin\Admin\Facades\Admin;

class Limit extends AbstractDisplayer
{
    protected function addScript()
    {
        $script = <<<'JS'
document.querySelectorAll('.limit-more').forEach(el =>{
    el.addEventListener("click",function (event) {

        event.currentTarget.querySelector("i").classList.toggle("icon-angle-double-down");
        event.currentTarget.querySelector("i").classList.toggle("icon-angle-double-up");
        event.currentTarget.parentNode.querySelectorAll('.text').forEach(text=>{
            text.classList.toggle("d-none");
        });
    });
});
JS;

        Admin::script($script);
    }

    public function display($limit = 100, $end = '...')
    {
        $this->addScript();
        $value = Str::limit($this->value, $limit, $end);
        $original = $this->getColumn()->getOriginal();

        if ($value == $original) {
            return $value;
        }

        return <<<HTML
<div class="limit-text">
    <span class="text">{$value}</span>
    <span class="text d-none">{$original}</span>
    &nbsp;<a href="javascript:void(0);" class="limit-more"><i class="icon icon-angle-double-down"></i></a>
</div>
HTML;
    }
}
