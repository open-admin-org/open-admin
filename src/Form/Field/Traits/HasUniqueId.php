<?php

namespace OpenAdmin\Admin\Form\Field\Traits;

trait HasUniqueId
{
    /**
     * unique id to prevent element selector collision.
     *
     * @var string
     */
    public $uniqueId;

    public function uniqueId($length = 10)
    {
        $characters       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
