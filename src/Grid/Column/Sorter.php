<?php

namespace OpenAdmin\Admin\Grid\Column;

use Illuminate\Contracts\Support\Renderable;

class Sorter implements Renderable
{
    /**
     * Sort arguments.
     *
     * @var array
     */
    public $sort;

    /**
     * Cast Name.
     *
     * @var array
     */
    protected $cast;

    /**
     * @var string
     */
    protected $sortName;

    /**
     * @var string
     */
    public $columnName;

    /**
     * Sorter constructor.
     *
     * @param string $sortName
     * @param string $columnName
     * @param string $cast
     */
    public function __construct($sortName, $columnName, $cast)
    {
        $this->sortName   = $sortName;
        $this->columnName = $columnName;
        $this->cast       = $cast;
    }

    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * Determine if this column is currently sorted.
     *
     * @return bool
     */
    protected function isSorted()
    {
        $this->sort = \request()->get($this->sortName);

        if (empty($this->sort)) {
            return false;
        }

        return isset($this->sort['column']) && $this->sort['column'] == $this->columnName;
    }

    /**
     * @return string
     */
    public function render()
    {
        $icon = 'icon-sort';
        $type = 'desc';

        if ($this->isSorted()) {
            $type      = $this->sort['type'] == 'desc' ? 'asc' : 'desc';
            $icon_type = $this->sort['type'] == 'desc' ? 'down' : 'up';
            $icon .= "-amount-{$icon_type}";
        }

        // set sort value
        $sort = ['column' => $this->columnName, 'type' => $type];

        if ($this->cast) {
            $sort['cast'] = $this->cast;
        }

        $query = \request()->all();
        $query = array_merge($query, [$this->sortName => $sort]);

        $url = url()->current().'?'.http_build_query($query);

        return "<a class=\"icon-fw $icon\" href=\"$url\"></a>";
    }
}
