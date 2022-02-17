<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use OpenAdmin\Admin\Facades\Admin;

class Secret extends AbstractDisplayer
{
    public function display($dotCount = 6)
    {
        $this->addScript();

        $dots = str_repeat('*', $dotCount);

        return <<<HTML
<span class="secret-wrapper">
    <i class="icon-eye" style="cursor: pointer;"></i>
    &nbsp;
    <span class="secret-placeholder" style="vertical-align: middle;">{$dots}</span>
    <span class="secret-content d-none">{$this->getValue()}</span>
</span>
HTML;
    }

    protected function addScript()
    {
        $script = <<<'JS'
document.querySelectorAll('.secret-wrapper i').forEach(el=>{
    el.addEventListener("click",function (e) {
        e.target.classList.toggle("icon-eye");
        e.target.classList.toggle("icon-eye-slash");
        e.target.parentNode.querySelector(".secret-placeholder").classList.toggle("d-none");
        e.target.parentNode.querySelector(".secret-content").classList.toggle("d-none");
    });
});
JS;

        Admin::script($script);
    }
}
