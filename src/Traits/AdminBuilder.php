<?php

namespace SuperAdmin\Admin\Traits;

use SuperAdmin\Admin\Form;
use SuperAdmin\Admin\Grid;
use SuperAdmin\Admin\Tree;

/**
 * @deprecated
 */
trait AdminBuilder
{
    /**
     * @return Grid
     */
    public static function grid(\Closure $callback)
    {
        return new Grid(new static, $callback);
    }

    /**
     * @return Form
     */
    public static function form(\Closure $callback)
    {
        return new Form(new static, $callback);
    }

    /**
     * @return Tree
     */
    public static function tree(?\Closure $callback = null)
    {
        return new Tree(new static, $callback);
    }
}
