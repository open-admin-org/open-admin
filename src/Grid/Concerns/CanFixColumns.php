<?php

namespace OpenAdmin\Admin\Grid\Concerns;

use Illuminate\Support\Collection;
use OpenAdmin\Admin\Grid\Tools\FixColumns;

trait CanFixColumns
{
    /**
     * @var FixColumns
     */
    protected $fixColumns;

    /**
     * @param int $head
     * @param int $tail
     */
    public function fixColumns(int $head, int $tail = -1)
    {
        $this->fixColumns = new FixColumns($this, $head, $tail);

        $this->rendering($this->fixColumns->apply());
    }

    /**
     * @return Collection
     */
    public function leftVisibleColumns()
    {
        return $this->fixColumns->leftColumns();
    }

    /**
     * @return Collection
     */
    public function rightVisibleColumns()
    {
        return $this->fixColumns->rightColumns();
    }
}
