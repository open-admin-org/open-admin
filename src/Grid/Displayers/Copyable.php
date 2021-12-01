<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use OpenAdmin\Admin\Facades\Admin;

/**
 * Class Copyable.
 *
 * @see https://codepen.io/shaikmaqsood/pen/XmydxJ
 */
class Copyable extends AbstractDisplayer
{
    protected function addScript()
    {
        $script = <<<SCRIPT
document.querySelectorAll('#{$this->grid->tableID} .grid-column-copyable').forEach( el => {
    el.addEventListener("click",function(e){

        var content = el.dataset.content;

        let tmp_input = document.createElement('input');
        tmp_input.setAttribute('type', 'text');
        tmp_input.setAttribute('value', content);
        document.body.appendChild(tmp_input);
        tmp_input.focus();
        tmp_input.select();
        document.execCommand("copy");
        tmp_input.remove();
        admin.toastr.toast("Added to clipboard");
    });
});
SCRIPT;

        Admin::script($script);
    }

    public function display()
    {
        $this->addScript();

        $content = $this->getColumn()->getOriginal();

        return <<<HTML
<a href="javascript:void(0);" class="grid-column-copyable text-muted" data-content="{$content}" title="Copied!" data-placement="bottom">
    <i class="icon-copy"></i>
</a>&nbsp;{$this->getValue()}
HTML;
    }
}
