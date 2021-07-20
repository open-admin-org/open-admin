<?php

namespace OpenAdmin\Admin\Grid\Tools;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Query\Builder;
use OpenAdmin\Admin\Grid;

class Footer extends AbstractTool
{
    /**
     * @var Builder
     */
    protected $queryBuilder;

    /**
     * Footer constructor.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Get model query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function queryBuilder()
    {
        if (!$this->queryBuilder) {
            $this->queryBuilder = $this->grid->model()->getQueryBuilder();
        }

        return $this->queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $content = call_user_func($this->grid->footer(), $this->queryBuilder());

        if (empty($content)) {
            return '';
        }

        if ($content instanceof Renderable) {
            $content = $content->render();
        }

        if ($content instanceof Htmlable) {
            $content = $content->toHtml();
        }

        return <<<HTML
    <div class="card-footer clearfix">
        {$content}
    </div>
HTML;
    }
}
