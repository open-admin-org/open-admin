<?php

namespace OpenAdmin\Admin\Controllers\Traits;

use Illuminate\Support\Facades\Hash;

trait AdminUserHelpers
{
    public function handlePassword(& $form)
    {
        if ($form->password && $form->model()->password != $form->password) {
            $form->password = Hash::make($form->password);
        }
    }

    public function showNewHeaderAvatar($form)
    {
        admin_flashjs('documenta.querySelectorAll(".header-avatar").forEach((img) => {img.src = "'.$form->model()->headerAvatar.'"});');
    }
}
