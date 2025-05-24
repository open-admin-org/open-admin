<?php

namespace SuperAdmin\Admin\Form\Field;

use SuperAdmin\Admin\Form\Field;

class Nullable extends Field
{
    public function __construct() {}

    public function __call($method, $parameters)
    {
        return $this;
    }
}
