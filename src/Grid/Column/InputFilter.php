<?php

namespace OpenAdmin\Admin\Grid\Column;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Grid\Model;

class InputFilter extends Filter
{
    /**
     * @var string
     */
    protected $type;

    /**
     * InputFilter constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
        $this->class = uniqid('column-filter-');
        $this->addition_classes = '';
    }

    /**
     * Add a binding to the query.
     *
     * @param string     $value
     * @param Model|null $model
     */
    public function addBinding($value, Model $model)
    {
        if (empty($value)) {
            return;
        }

        if ($this->type == 'like') {
            $model->where($this->getColumnName(), 'like', "%{$value}%");

            return;
        }

        if (in_array($this->type, ['date', 'time'])) {
            $method = 'where'.ucfirst($this->type);
            $model->{$method}($this->getColumnName(), $value);

            return;
        }

        $model->where($this->getColumnName(), $value);
    }

    /**
     * Add script to page.
     *
     * @return void
     */
    protected function addDateTimeScript()
    {
        $options = [
            'locale'           => config('app.locale'),
            'inline'           => true,
            'allowInputToggle' => true,
            'allowInput'       => true,
            'time_24hr'        => true,
        ];

        if ($this->type == 'date') {
            $options['format'] = 'YYYY-MM-DD';
        } elseif ($this->type == 'datetime') {
            $options['format'] = 'YYYY-MM-DD HH:mm:ss';
            $options['enableSeconds'] = true;
            $options['enableTime'] = true;
        } elseif ($this->type == 'time') {
            $options['format'] = 'HH:mm:ss';
            $options['enableSeconds'] = true;
            $options['enableTime'] = true;
            $options['noCalendar'] = true;
        } else {
            return;
        }

        $options = json_encode($options);

        Admin::script("flatpickr('.{$this->class}',{$options});");
    }

    /**
     * Render this filter.
     *
     * @return string
     */
    public function render()
    {
        $script = <<<'SCRIPT'
document.querySelectorAll('.dropdown-menu input, .flatpickr-month').forEach(el =>{
    el.addEventListener("click",function(e) {
        e.stopPropagation();
    })
});
SCRIPT;
        Admin::script($script);

        if ($this->type == 'date' || $this->type == 'datetime' || $this->type == 'time') {
            $this->addDateTimeScript();
            $this->addition_classes .= 'd-none';
        }

        $value = $this->getFilterValue();
        $active = empty($value) ? '' : 'text-yellow';

        return <<<EOT
<span class="dropdown">
    <form action="{$this->getFormAction()}" pjax-container="true" method="get" style="display: inline-block;">
    <a href="javascript:void(0);" class="dropdown-toggle {$active}" data-bs-toggle="dropdown" data-bs-auto-close="outside" >
        <i class="icon-filter"></i>
    </a>
    <ul class="dropdown-menu" role="menu" style="padding: 10px;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);left: -70px;">
        <li>
            <input type="text" name="{$this->getColumnName()}" value="{$this->getFilterValue()}" class="form-control input-sm {$this->class} {$this->addition_classes}" autocomplete="off"/>
        </li>
        <li class="divider"><hr class="dropdown-divider"></li>
        <li class="text-right">
            <button class="btn btn-sm btn-primary column-filter-submit pull-left" data-loading-text="{$this->trans('search')}..."><i class="icon-search"></i>&nbsp;&nbsp;{$this->trans('search')}</button>
            <span><a href="{$this->getFormAction()}" class="btn btn-sm btn-light column-filter-all"><i class="icon-undo"></i></a></span>
        </li>
    </ul>
    </form>
</span>
EOT;
    }
}
