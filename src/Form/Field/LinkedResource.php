<?php

namespace OpenAdmin\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Form\Field\Traits\HasResource;

class LinkedResource extends Field
{
    use HasResource;

    protected $relation_prefix = 'hasmany-';
    protected $relation_type   = 'hasMany';
    protected $multiple        = true;
    protected $relationName;
    protected $builder;
    protected $option;
    protected $resource_url;
    protected $resource_controller;
}
