<?php

namespace OpenAdmin\Admin\Form\Field;

class Password extends Text
{
    public $showPasswordToggle = false;

    public function toggleShow($set = true)
    {
        $this->showPasswordToggle = $set;
    }

    public function setupScript()
    {
        $this->script = <<<'JS'
            window.togglePassword = function(ref){
                var field = document.getElementById(ref.dataset.id);
                if (field.type == "password"){
                    field.type = "text";
                    ref.classList.remove("icon-eye");
                    ref.classList.add("icon-eye-slash");
                }else{
                    field.type = "password";
                    ref.classList.remove("icon-eye-slash");
                    ref.classList.add("icon-eye");
                }
            };
        JS;
    }

    public function render()
    {
        $this->prepend('<i class="icon-eye-slash fa-fw"></i>')
             ->defaultAttribute('type', 'password');

        if ($this->showPasswordToggle) {
            $this->setupScript();
            $this->append('<i class="icon-eye fa-fw" style="cursor:pointer;margin:-5px;padding:5px;" data-id="'.$this->id.'" onclick="window.togglePassword(this)"></i>');
        }

        return parent::render();
    }
}
