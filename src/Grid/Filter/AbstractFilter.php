<?php

namespace OpenAdmin\Admin\Grid\Filter;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenAdmin\Admin\Grid\Filter;
use OpenAdmin\Admin\Grid\Filter\Presenter\Checkbox;
use OpenAdmin\Admin\Grid\Filter\Presenter\DateTime;
use OpenAdmin\Admin\Grid\Filter\Presenter\MultipleSelect;
use OpenAdmin\Admin\Grid\Filter\Presenter\Presenter;
use OpenAdmin\Admin\Grid\Filter\Presenter\Radio;
use OpenAdmin\Admin\Grid\Filter\Presenter\Select;
use OpenAdmin\Admin\Grid\Filter\Presenter\Text;

/**
 * Class AbstractFilter.
 *
 * @method Text url()
 * @method Text email()
 * @method Text integer()
 * @method Text decimal($options = [])
 * @method Text currency($options = [])
 * @method Text percentage($options = [])
 * @method Text ip()
 * @method Text mac()
 * @method Text phonenumber($mask = '19999999999')
 * @method Text inputmask($options = [], $icon = '')
 * @method Text placeholder($placeholder = '')
 */
abstract class AbstractFilter
{
    /**
     * Element id.
     *
     * @var array|string
     */
    protected $id;

    /**
     * Label of presenter.
     *
     * @var string
     */
    protected $label;

    /**
     * @var array|string
     */
    protected $value;

    /**
     * @var array|string
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected $column;

    /**
     * Presenter object.
     *
     * @var Presenter
     */
    protected $presenter;

    /**
     * Query for filter.
     *
     * @var string
     */
    protected $query = 'where';

    /**
     * @var Filter
     */
    protected $parent;

    /**
     * @var string
     */
    protected $view = 'admin::filter.where';

    /**
     * @var Collection
     */
    public $group;

    /**
     * @var bool
     */
    protected $ignore = false;

    public $cols_label;
    public $cols_field;

    /**
     * AbstractFilter constructor.
     *
     * @param $column
     * @param string $label
     */
    public function __construct($column, $label = '')
    {
        $this->column = $column;
        $this->label  = $this->formatLabel($label);
        $this->id     = $this->formatId($column);

        $this->setupDefaultPresenter();
    }

    /**
     * Setup default presenter.
     *
     * @return void
     */
    protected function setupDefaultPresenter()
    {
        $this->setPresenter(new Text($this->label));
    }

    /**
     * Format label.
     *
     * @param string $label
     *
     * @return string
     */
    protected function formatLabel($label)
    {
        $label = $label ?: ucfirst($this->column);

        return str_replace(['.', '_'], ' ', $label);
    }

    /**
     * Format name.
     *
     * @param string $column
     *
     * @return string
     */
    protected function formatName($column)
    {
        $columns = explode('.', $column);

        if (count($columns) == 1) {
            $name = $columns[0];
        } else {
            $name = array_shift($columns);
            foreach ($columns as $column) {
                $name .= "[$column]";
            }
        }

        $parenName = $this->parent->getName();

        return $parenName ? "{$parenName}_{$name}" : $name;
    }

    /**
     * Format id.
     *
     * @param $columns
     *
     * @return array|string
     */
    protected function formatId($columns)
    {
        return str_replace('.', '_', $columns);
    }

    /**
     * @param Filter $filter
     */
    public function setParent(Filter $filter)
    {
        $this->parent = $filter;
    }

    /**
     * Get siblings of current filter.
     *
     * @param null $index
     *
     * @return AbstractFilter[]|mixed
     */
    public function siblings($index = null)
    {
        if (!is_null($index)) {
            return Arr::get($this->parent->filters(), $index);
        }

        return $this->parent->filters();
    }

    /**
     * Get previous filter.
     *
     * @param int $step
     *
     * @return AbstractFilter[]|mixed
     */
    public function previous($step = 1)
    {
        return $this->siblings(
            array_search($this, $this->parent->filters()) - $step
        );
    }

    /**
     * Get next filter.
     *
     * @param int $step
     *
     * @return AbstractFilter[]|mixed
     */
    public function next($step = 1)
    {
        return $this->siblings(
            array_search($this, $this->parent->filters()) + $step
        );
    }

    /**
     * Get query condition from filter.
     *
     * @param array $inputs
     *
     * @return array|mixed|null
     */
    public function condition($inputs)
    {
        if ($this->ignore) {
            return;
        }

        $value = Arr::get($inputs, $this->column);

        if (!isset($value)) {
            return;
        }

        $this->value = $value;

        return $this->buildCondition($this->column, $this->value);
    }

    /**
     * Ignore this query filter.
     *
     * @return $this
     */
    public function ignore()
    {
        $this->ignore = true;

        return $this;
    }

    /**
     * Select filter.
     *
     * @param array|\Illuminate\Support\Collection $options
     *
     * @return Select
     */
    public function select($options = [])
    {
        return $this->setPresenter(new Select($options));
    }

    /**
     * @param array|\Illuminate\Support\Collection $options
     *
     * @return MultipleSelect
     */
    public function multipleSelect($options = [])
    {
        return $this->setPresenter(new MultipleSelect($options));
    }

    /**
     * @param array|\Illuminate\Support\Collection $options
     *
     * @return Radio
     */
    public function radio($options = [])
    {
        return $this->setPresenter(new Radio($options));
    }

    /**
     * @param array|\Illuminate\Support\Collection $options
     *
     * @return Checkbox
     */
    public function checkbox($options = [])
    {
        return $this->setPresenter(new Checkbox($options));
    }

    /**
     * Datetime filter.
     *
     * @param array|\Illuminate\Support\Collection $options
     *
     * @return DateTime
     */
    public function datetime($options = [])
    {
        return $this->setPresenter(new DateTime($options));
    }

    /**
     * Date filter.
     *
     * @return DateTime
     */
    public function date($options = [])
    {
        $options = array_merge(['format' => 'YYYY-MM-DD'], $options);

        return $this->datetime($options);
    }

    /**
     * Time filter.
     *
     * @return DateTime
     */
    public function time($options = [])
    {
        $options = array_merge(['format' => 'HH:mm:ss', 'noCalendar' => true], $options);

        return $this->datetime($options);
    }

    /**
     * Day filter.
     *
     * @return DateTime
     */
    public function day($options = [])
    {
        $options = array_merge(['mask' => '99', 'rightAlign' => false], $options);

        return $this->inputmask($options, 'calendar');
    }

    /**
     * Month filter.
     *
     * @return DateTime
     */
    public function month($options = [])
    {
        $options = array_merge(['mask' => '99', 'rightAlign' => false], $options);

        return $this->inputmask($options, 'calendar');
    }

    /**
     * Year filter.
     *
     * @return DateTime
     */
    public function year($options = [])
    {
        $options = array_merge(['mask' => '9999', 'rightAlign' => false], $options);

        return $this->inputmask($options, 'calendar');
    }

    /**
     * Set presenter object of filter.
     *
     * @param Presenter $presenter
     *
     * @return mixed
     */
    protected function setPresenter(Presenter $presenter)
    {
        $presenter->setParent($this);

        return $this->presenter = $presenter;
    }

    /**
     * Get presenter object of filter.
     *
     * @return Presenter
     */
    protected function presenter()
    {
        return $this->presenter;
    }

    /**
     * Set default value for filter.
     *
     * @param null $default
     *
     * @return $this
     */
    public function default($default = null)
    {
        if ($default) {
            $this->defaultValue = $default;
        }

        return $this;
    }

    public function getFilterBoxId()
    {
        return $this->parent ? $this->parent->getFilterID() : 'filter-box';
    }

    /**
     * Get element id.
     *
     * @return array|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set element id.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $this->formatId($id);

        return $this;
    }

    /**
     * Get column name of current filter.
     *
     * @return string
     */
    public function getColumn()
    {
        $parentName = $this->parent->getName();

        return $parentName ? "{$parentName}_{$this->column}" : $this->column;
    }

    /**
     * Get value of current filter.
     *
     * @return array|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Build conditions of filter.
     *
     * @return mixed
     */
    protected function buildCondition()
    {
        $column = explode('.', $this->column);

        if (count($column) == 1) {
            return [$this->query => func_get_args()];
        }

        return $this->buildRelationQuery(...func_get_args());
    }

    /**
     * Build query condition of model relation.
     *
     * @return array
     */
    protected function buildRelationQuery()
    {
        $args = func_get_args();

        $relation = substr($this->column, 0, strrpos($this->column, '.'));
        $args[0]  = last(explode('.', $this->column));

        return ['whereHas' => [$relation, function ($relation) use ($args) {
            call_user_func_array([$relation, $this->query], $args);
        }]];
    }

    /**
     * Variables for filter view.
     *
     * @return array
     */
    protected function variables()
    {
        return array_merge([
            'id'         => $this->id,
            'column'     => $this->column,
            'name'       => $this->formatName($this->column),
            'label'      => $this->label,
            'value'      => $this->value ?: $this->defaultValue,
            'cols_label' => $this->cols_label ?? 2,
            'cols_field' => $this->cols_field ?? 8,
            'presenter'  => $this->presenter(),
        ], $this->presenter()->variables());
    }

    public function setCols($label = 2, $field = 8)
    {
        $this->cols_label = $label;
        $this->cols_field = $field;

        return $this;
    }

    /**
     * Render this filter.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        if ($this->cols_label || $this->cols_field) {
            $this->setCols($this->cols_label, $this->cols_field);
        } elseif ($this->parent->cols_label || $this->parent->cols_field) {
            $this->setCols($this->parent->cols_label, $this->parent->cols_field);
        }

        return view($this->view, $this->variables());
    }

    /**
     * Render this filter.
     *
     * @return \Illuminate\View\View|string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param $method
     * @param $params
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (method_exists($this->presenter, $method)) {
            return $this->presenter()->{$method}(...$params);
        }

        throw new \Exception('Method "'.$method.'" not exists.');
    }
}
