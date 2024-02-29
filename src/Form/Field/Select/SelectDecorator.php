<?php

namespace OpenAdmin\Admin\Form\Field\Select;

use OpenAdmin\Admin\Form\Field\Select;

interface SelectDecorator
{
    /**
     * Return tur if the decorator can use on the Field
     * @param  Select $select
     * @return $this
     */
    public function init(Select $select);

    /**
     * Render function for decorator
     *
     * @return void
     */
    public function render();

    /**
     * Function that sets up addition scripts for ajax loading
     *
     * @return void
     */
    public function ajax($url, $valueField, $labelField);

    /**
     * Function that sets up addition scripts for to remote options
     *
     * @return void
     */
    public function ajaxOptions($url, $valueField, $labelField, $parameters = []);

    /**
     * Function that sets up addition scripts for loading other fields
     *
     * @return void
     */
    public function load($field, $url, $valueField, $labelField);
}
