<?php

/**
 * Copyright (c) 2019. Mallto.Co.Ltd.<mall-to.com> All rights reserved.
 */

namespace SuperAdmin\Admin\Form\Layout;

use Illuminate\Support\Collection;
use SuperAdmin\Admin\Form;

class Layout
{
    /**
     * @var Collection
     */
    protected $columns;

    /**
     * @var Column
     */
    protected $current;

    /**
     * @var Form
     */
    protected $parent;

    /**
     * Layout constructor.
     */
    public function __construct(Form $form)
    {
        $this->parent = $form;

        $this->current = new Column;

        $this->columns = new Collection;
    }

    /**
     * Add a filter to layout column.
     */
    public function addField(Form\Field $field)
    {
        $this->current->add($field);
    }

    /**
     * Add a new column in layout.
     *
     * @param  int  $width
     */
    public function column($width, \Closure $closure)
    {
        if ($this->columns->isEmpty()) {
            $column = $this->current;

            $column->setWidth($width);
        } else {
            $column = new Column($width);

            $this->current = $column;
        }

        $this->columns->push($column);

        $closure($this->parent);
    }

    /**
     * Get all columns in filter layout.
     *
     * @return Collection
     */
    public function columns()
    {
        if ($this->columns->isEmpty()) {
            $this->columns->push($this->current);
        }

        return $this->columns;
    }

    /**
     * Remove reserved fields from form layout.
     */
    public function removeReservedFields(array $fields)
    {
        if (empty($fields)) {
            return;
        }

        foreach ($this->columns() as &$column) {
            $column->removeFields($fields);
        }
    }
}
