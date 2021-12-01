<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use Carbon\Carbon;

class DateFormat extends AbstractDisplayer
{
    public function display($format = 'Y-m-d')
    {
        return (new Carbon($this->getValue()))->format($format);
    }
}
