<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Traits\ModelTree;

class Tree extends Model
{
    use ModelTree;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.menu_table'));

        parent::__construct($attributes);
    }
}
