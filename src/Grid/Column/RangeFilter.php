<?php

namespace OpenAdmin\Admin\Grid\Column;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Grid\Model;

class RangeFilter extends Filter
{
    /**
     * @var string
     */
    protected $type;

    /**
     * RangeFilter constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
        $this->class = [
            'start' => uniqid('column-filter-start-'),
            'end'   => uniqid('column-filter-end-'),
        ];
    }

    /**
     * Add a binding to the query.
     *
     * @param mixed $value
     * @param Model $model
     */
    public function addBinding($value, Model $model)
    {
        $value = array_filter((array) $value);

        if (empty($value)) {
            return;
        }

        if (!isset($value['start'])) {
            return $model->where($this->getColumnName(), '<', $value['end']);
        } elseif (!isset($value['end'])) {
            return $model->where($this->getColumnName(), '>', $value['start']);
        } else {
            return $model->whereBetween($this->getColumnName(), array_values($value));
        }
    }

    protected function addScript()
    {
        if ($this->type == 'time') {
            Admin::script("Inputmask({'mask':'99:99:99'}).mask(document.querySelectorAll('.{$this->class['start']},{$this->class['end']}'));");
        } else {
            $options = [
                'locale'           => config('app.locale'),
                'allowInputToggle' => true,
                'allowInput'       => true,
            ];

            if ($this->type == 'date') {
                $options['format'] = 'YYYY-MM-DD';
            } elseif ($this->type == 'datetime') {
                $options['format'] = 'YYYY-MM-DD HH:mm:ss';
                $options['enableSeconds'] = true;
                $options['enableTime'] = true;
            } else {
                return;
            }

            $options = json_encode($options);
            Admin::script("flatpickr('.{$this->class['start']}',{$options});flatpickr('.{$this->class['end']}',{$options});");
        }
    }

    /**
     * Render this filter.
     *
     * @return string
     */
    public function render()
    {
        $script = <<<'SCRIPT'

document.querySelectorAll('.dropdown-menu input').forEach(el =>{
    el.addEventListener("click",function(e) {
        e.stopPropagation();
    })
});
SCRIPT;

        Admin::script($script);

        $this->addScript();

        $value = array_merge(['start' => '', 'end' => ''], $this->getFilterValue([]));
        $active = empty(array_filter($value)) ? '' : 'text-yellow';

        return <<<EOT
<span class="dropdown">
<form action="{$this->getFormAction()}" pjax-container method="get" style="display: inline-block;">
    <a href="javascript:void(0);" class="dropdown-toggle {$active}" data-bs-toggle="dropdown">
        <i class="icon-filter"></i>
    </a>
    <ul class="dropdown-menu" role="menu" style="min-width:13rem; padding: 10px;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);left: -70px;border-radius: 0;">
        <li>
            <input type="text" class="form-control input {$this->class['start']}" name="{$this->getColumnName()}[start]" value="{$value['start']}" autocomplete="off"/>
        </li>
        <li style="margin: 5px;"></li>
        <li>
            <input type="text" class="form-control input {$this->class['start']}" name="{$this->getColumnName()}[end]"  value="{$value['end']}" autocomplete="off"/>
        </li>
        <li><hr class="dropdown-divider" /></li>
        <li class="text-right">
            <button class="btn btn-sm btn-primary btn-flat column-filter-submit pull-left" data-loading-text="{$this->trans('search')}..."><i class="icon-search"></i>&nbsp;&nbsp;{$this->trans('search')}</button>
            <span><a href="{$this->getFormAction()}" class="btn btn-sm btn-default btn-light column-filter-all"><i class="icon-undo"></i></a></span>
        </li>
    </ul>
    </form>
</span>
EOT;
    }
}
