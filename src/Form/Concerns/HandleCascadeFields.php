<?php

namespace SuperAdmin\Admin\Form\Concerns;

use SuperAdmin\Admin\Form\Field;

trait HandleCascadeFields
{
    public function cascadeGroup(\Closure $closure, array $dependency)
    {
        $this->pushField($group = new Field\CascadeGroup($dependency));

        call_user_func($closure, $this);

        $group->end();
    }
}
