<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form\Field\Traits\BelongsToRelation;
use OpenAdmin\Admin\Grid\Selectable;

class BelongsTo extends AbstractDisplayer
{
    /**
     * BelongsToRelation constructor.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($value, $grid, $column, $row)
    {
        //$this->setSelectable($arguments[0]);

        parent::__construct($value, $grid, $column, $row);
    }

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl($selectable, $multiple = 0)
    {
        $selectable = str_replace('\\', '_', $selectable);
        $args = [$multiple];

        return route('admin.handle-selectable', compact('selectable', 'args'));
    }

    /**
     * @return mixed
     */
    protected function getOriginalData()
    {
        return $this->getColumn()->getOriginal();
    }

    /**
     * @param string $selectable
     * @param string $column
     *
     * @return string
     */
    public function display($selectable = null)
    {
        if (!class_exists($selectable) || !is_subclass_of($selectable, Selectable::class)) {
            throw new \InvalidArgumentException(
                "[Class [{$selectable}] must be a sub class of OpenAdmin\Admin\Grid\Selectable"
            );
        }

        return Admin::component('admin::grid.inline-edit.belongsto', [
            'modal'         => sprintf('modal-grid-selector-%s', $this->getClassName()),
            'key'           => $this->getKey(),
            'original'      => $this->getOriginalData(),
            'value'         => $this->getValue(),
            'resource'      => $this->getResource(),
            'name'          => $this->getName(),
            'relation'      => get_called_class(),
            'display_field' => $selectable::$display_field,
            'labelClass'    => $selectable::$labelClass,
            'seperator'     => $selectable::$seperator,
            'url'           => $this->getLoadUrl($selectable, get_called_class() == BelongsToMany::class),
        ]);
    }
}
