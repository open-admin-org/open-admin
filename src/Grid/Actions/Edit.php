<?php

namespace OpenAdmin\Admin\Grid\Actions;

use OpenAdmin\Admin\Actions\RowAction;

class Edit extends RowAction
{
    public $icon = 'icon-pen';

    /**
     * @return array|null|string
     */
    public function name()
    {
        return __('admin.edit');
    }

    /**
     * @return string
     */
    public function href()
    {
        return "{$this->getResource()}/{$this->getKey()}/edit";
    }
}
