<?php

namespace OpenAdmin\Admin\Form\Field\Traits;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Form;

/**
 * @property Form $form
 */
trait CanCascadeFields
{
    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @param $operator
     * @param $value
     * @param $closure
     *
     * @return $this
     */
    public function when($operator, $value, $closure = null)
    {
        if (func_num_args() == 2) {
            $closure = $value;
            $value = $operator;
            $operator = '=';
        }

        $this->formatValues($operator, $value);

        $this->addDependents($operator, $value, $closure);

        return $this;
    }

    /**
     * @param string $operator
     * @param mixed  $value
     */
    protected function formatValues(string $operator, &$value)
    {
        if (in_array($operator, ['in', 'notIn'])) {
            $value = Arr::wrap($value);
        }

        if (is_array($value)) {
            $value = array_map('strval', $value);
        } else {
            $value = strval($value);
        }
    }

    /**
     * @param string   $operator
     * @param mixed    $value
     * @param \Closure $closure
     */
    protected function addDependents(string $operator, $value, \Closure $closure)
    {
        $this->conditions[] = compact('operator', 'value', 'closure');

        $this->form->cascadeGroup($closure, [
            'column' => $this->column(),
            'index'  => count($this->conditions) - 1,
            'class'  => $this->getCascadeClass($value),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function fill($data)
    {
        parent::fill($data);

        $this->applyCascadeConditions();
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function getCascadeClass($value)
    {
        if (is_array($value)) {
            $value = implode('-', $value);
        }

        return sprintf('cascade-%s-%s', $this->getElementClassString(), $value);
    }

    /**
     * Apply conditions to dependents fields.
     *
     * @return void
     */
    protected function applyCascadeConditions()
    {
        if ($this->form) {
            $this->form->fields()
                ->filter(function (Form\Field $field) {
                    return $field instanceof CascadeGroup
                        && $field->dependsOn($this)
                        && $this->hitsCondition($field);
                })->each->visiable();
        }
    }

    /**
     * @param CascadeGroup $group
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function hitsCondition(CascadeGroup $group)
    {
        $condition = $this->conditions[$group->index()];

        extract($condition);

        $old = old($this->column(), $this->value());

        switch ($operator) {
            case '=':
                return $old == $value;
            case '>':
                return $old > $value;
            case '<':
                return $old < $value;
            case '>=':
                return $old >= $value;
            case '<=':
                return $old <= $value;
            case '!=':
                return $old != $value;
            case 'in':
                return in_array($old, $value);
            case 'notIn':
                return !in_array($old, $value);
            case 'has':
                return in_array($value, $old);
            case 'oneIn':
                return count(array_intersect($value, $old)) >= 1;
            case 'oneNotIn':
                return count(array_intersect($value, $old)) == 0;
            default:
                throw new \Exception("Operator [$operator] not support.");
        }
    }

    /**
     * Js Value.
     */
    protected function getValueByJs()
    {
        return addslashes(old($this->column(), $this->value()));
    }

    /**
     * Add cascade scripts to contents.
     *
     * @return void
     */
    protected function addCascadeScript()
    {
        if (empty($this->conditions)) {
            return;
        }

        $cascadeGroups = collect($this->conditions)->map(function ($condition) {
            return [
                'class'    => $this->getCascadeClass($condition['value']),
                'operator' => $condition['operator'],
                'value'    => $condition['value'],
            ];
        })->toJson();

        $script = <<<SCRIPT
;(function () {
    var inArray = function (find,arr){
        return arr.indexOf(find);
    }
    var operator_table = {
        '=': function(a, b) {
            if (Array.isArray(a) && Array.isArray(b)) {
                a.sort();
                b.sort();
                return a.join() == b.join()
            }
            return a == b;
        },
        '>': function(a, b) { return a > b; },
        '<': function(a, b) { return a < b; },
        '>=': function(a, b) { return a >= b; },
        '<=': function(a, b) { return a <= b; },
        '!=': function(a, b) {

            if (Array.isArray(a) && Array.isArray(b)) {
                a.sort();
                b.sort();
                return !(a.join() == b.join())
            }

             return a != b;
        },
        'in': function(a, b) { return inArray(a, b) != -1; },
        'notIn': function(a, b) { return inArray(a, b) == -1; },
        'has': function(a, b) { return inArray(b, a) != -1; },
        'oneIn': function(a, b) { return a.filter(v => b.includes(v)).length >= 1; },
        'oneNotIn': function(a, b) { return a.filter(v => b.includes(v)).length == 0; },
    };
    var cascade_groups = {$cascadeGroups};

    cascade_groups.forEach(function (event) {
        var default_value = '{$this->getValueByJs()}' + '';
        var class_name = event.class;
        if( operator_table[event.operator](default_value, event.value) ) {
            document.querySelector('.'+class_name+'').classList.remove('d-none');
        }else{
            document.querySelector('.'+class_name+'').classList.add('d-none');
        }
    });

    document.querySelectorAll('{$this->getElementClassSelector()}').forEach( el =>{
        el.addEventListener('{$this->cascadeEvent}', function (e) {
            {$this->getFormFrontValue()}
            cascade_groups.forEach(function (event) {
                var group = document.querySelector('div.cascade-group.'+event.class);
                if( operator_table[event.operator](checked, event.value) ) {
                    group.classList.remove('d-none');
                } else {
                    group.classList.add('d-none');
                }
            });
        });
    })
    function getValuesFrom(selector){
        var arr = []
        document.querySelectorAll(selector).forEach(el=>{
            arr.push(el.value);
        });
        return arr;
    }
})();
SCRIPT;

        Admin::script($script);
    }

    /**
     * @return string
     */
    protected function getFormFrontValue()
    {
        $check_class = str_replace("\Field\\", "\Field\Traits\\", get_class($this));
        switch ($check_class) {
            case Radio::class:
            case RadioButton::class:
            case RadioCard::class:
            case Select::class:
            case BelongsTo::class:
            case BelongsToMany::class:
            case MultipleSelect::class:
                return 'var checked = this.value;';
            case Checkbox::class:
            case CheckboxButton::class:
            case CheckboxCard::class:
                return "var checked = getValuesFrom('{$this->getElementClassSelector()}:checked')";
            default:
                throw new \InvalidArgumentException('Invalid form field type');
        }
    }
}
