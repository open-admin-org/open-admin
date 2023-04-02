<?php

namespace OpenAdmin\Admin\Grid;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Grid\Selectable\Checkbox;
use OpenAdmin\Admin\Grid\Selectable\Radio;

/**
 * @mixin Grid
 */
abstract class Selectable
{
    /**
     * @var string
     */
    public $model;

    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var string
     */
    protected $key = '';

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * @var int
     */
    protected $perPage = 10;

    /**
     * @var string
     */
    public static $display_field = 'id';

    /**
     * @var string
     */
    public static $labelClass = '';

    /**
     * @var string
     */
    public static $seperator = ', ';

    /**
     * Selectable constructor.
     *
     * @param $key
     * @param $multiple
     */
    public function __construct($multiple = false, $key = '')
    {
        $this->key = $key ?: $this->key;
        $this->multiple = $multiple;

        $this->initGrid();
    }

    /**
     * @return Grid
     */
    abstract public function make();

    /**
     * @param bool $multiple
     *
     * @return string
     */
    public function render()
    {
        $this->make();
        $this->appendRemoveBtn(true);
        $this->disableFeatures()->paginate($this->perPage);
        $this->grid->getFilter()->setFilterID('filter-box-selectable');

        $displayer = $this->multiple ? Checkbox::class : Radio::class;

        $this->prependColumn('__modal_selector__', ' ')->displayUsing($displayer, [$this->key]);

        return $this->grid->render();
    }

    /**
     * @return $this
     */
    protected function disableFeatures()
    {
        return $this->disableExport()
            ->disableActions()
            ->disableBatchActions()
            ->disableCreateButton()
            ->disableColumnSelector()
            ->disablePerPageSelector();
    }

    public function renderFormGrid($values)
    {
        $this->make();

        $this->appendRemoveBtn(false);

        $this->model()->whereKey(Arr::wrap($values));

        $this->disableFeatures()->disableFilter();

        if (!$this->multiple) {
            $this->disablePagination();
        }

        $this->tools(function (Tools $tools) {
            $tools->append(new Grid\Selectable\BrowserBtn());
        });

        return $this->grid;
    }

    protected function appendRemoveBtn($hide = true)
    {
        $hide = $hide ? 'd-none' : '';
        $key = $this->key;

        $this->column('__remove__', ' ')->display(function () use ($hide, $key) {
            return <<<HTML
<a href="javascript:void(0);" class="grid-row-remove {$hide}" data-key="{$this->getAttribute($key)}">
    <i class="icon-trash"></i>
</a>
HTML;
        });
    }

    protected function initGrid()
    {
        if (!class_exists($this->model) || !is_subclass_of($this->model, Model::class)) {
            throw new \InvalidArgumentException("Invalid model [{$this->model}]");
        }

        /** @var Model $model */
        $model = new $this->model();

        $this->grid = new Grid(new $model());
        $this->grid->fixedFooter(false);

        if (!$this->key) {
            $this->key = $model->getKeyName();
        }
    }

    public static function display()
    {
        return function ($value) {
            if (is_array($value)) {
                return implode(self::$seperator, array_map(function ($item) {
                    return "<span data-key=\"{$item[self::$display_field]}\" class='".self::$labelClass."'>{$item[self::$display_field]}</span>";
                }, $value));
            } else {
                return "<span data-key=\"{$value}\" class='".self::$labelClass."'>{$value}</span>";
            }
        };
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments = [])
    {
        return $this->grid->{$method}(...$arguments);
    }
}
