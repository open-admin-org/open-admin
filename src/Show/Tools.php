<?php

namespace OpenAdmin\Admin\Show;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Tools implements Renderable
{
    /**
     * The panel that holds this tool.
     *
     * @var Panel
     */
    protected $panel;

    /**
     * @var string
     */
    protected $resource;

    /**
     * Default tools.
     *
     * @var array
     */
    protected $tools = ['delete', 'edit', 'list'];

    /**
     * Tools should be appends to default tools.
     *
     * @var Collection
     */
    protected $appends;

    /**
     * Tools should be prepends to default tools.
     *
     * @var Collection
     */
    protected $prepends;

    /**
     * Tools constructor.
     *
     * @param Panel $panel
     */
    public function __construct(Panel $panel)
    {
        $this->panel = $panel;

        $this->appends = new Collection();
        $this->prepends = new Collection();
    }

    /**
     * Append a tools.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function append($tool)
    {
        $this->appends->push($tool);

        return $this;
    }

    /**
     * Prepend a tool.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function prepend($tool)
    {
        $this->prepends->push($tool);

        return $this;
    }

    /**
     * Get resource path.
     *
     * @return string
     */
    public function getResource()
    {
        if (is_null($this->resource)) {
            $this->resource = $this->panel->getParent()->getResourcePath();
        }

        return $this->resource;
    }

    /**
     * Disable `list` tool.
     *
     * @return $this
     */
    public function disableList(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->tools, 'list');
        } elseif (!in_array('list', $this->tools)) {
            array_push($this->tools, 'list');
        }

        return $this;
    }

    /**
     * Disable `delete` tool.
     *
     * @return $this
     */
    public function disableDelete(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->tools, 'delete');
        } elseif (!in_array('delete', $this->tools)) {
            array_push($this->tools, 'delete');
        }

        return $this;
    }

    /**
     * Disable `edit` tool.
     *
     * @return $this
     */
    public function disableEdit(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->tools, 'edit');
        } elseif (!in_array('edit', $this->tools)) {
            array_push($this->tools, 'edit');
        }

        return $this;
    }

    /**
     * Get request path for resource list.
     *
     * @return string
     */
    protected function getListPath()
    {
        return ltrim($this->getResource(), '/');
    }

    /**
     * Get request path for edit.
     *
     * @return string
     */
    protected function getEditPath()
    {
        $key = $this->panel->getParent()->getModel()->getKey();

        return $this->getListPath().'/'.$key.'/edit';
    }

    /**
     * Get request path for delete.
     *
     * @return string
     */
    protected function getDeletePath()
    {
        $key = $this->panel->getParent()->getModel()->getKey();

        return $this->getListPath().'/'.$key;
    }

    /**
     * Render `list` tool.
     *
     * @return string
     */
    protected function renderList()
    {
        $list = trans('admin.list');

        return <<<HTML
<div class="btn-group pull-right">
    <a href="{$this->getListPath()}" class="btn btn-sm btn-light " title="{$list}">
        <i class="icon-list"></i><span class="hidden-xs"> {$list}</span>
    </a>
</div>
HTML;
    }

    /**
     * Render `edit` tool.
     *
     * @return string
     */
    protected function renderEdit()
    {
        $edit = trans('admin.edit');

        return <<<HTML
<div class="btn-group pull-right me-2">
    <a href="{$this->getEditPath()}" class="btn btn-sm btn-primary" title="{$edit}">
        <i class="icon-edit"></i><span class="hidden-xs"> {$edit}</span>
    </a>
</div>
HTML;
    }

    /**
     * Render `delete` tool.
     *
     * @return string
     */
    protected function renderDelete()
    {
        $trans = [
            'delete'         => trans('admin.delete'),
        ];

        return <<<HTML
<div class="btn-group pull-right me-2">
    <a onclick="admin.resource.delete(event,this)" data-url="{$this->getDeletePath()}" data-list_url="{$this->getListPath()}"  class="btn btn-sm btn-danger delete" title="{$trans['delete']}">
        <i class="icon-trash"></i><span class="hidden-xs">  {$trans['delete']}</span>
    </a>
</div>
HTML;
    }

    /**
     * Render custom tools.
     *
     * @param Collection $tools
     *
     * @return mixed
     */
    protected function renderCustomTools($tools)
    {
        return $tools->map(function ($tool) {
            if ($tool instanceof Renderable) {
                return $tool->render();
            }

            if ($tool instanceof Htmlable) {
                return $tool->toHtml();
            }

            return (string) $tool;
        })->implode(' ');
    }

    /**
     * Render tools.
     *
     * @return string
     */
    public function render()
    {
        $output = $this->renderCustomTools($this->prepends);

        foreach ($this->tools as $tool) {
            $renderMethod = 'render'.ucfirst($tool);
            $output .= $this->$renderMethod();
        }

        return $output.$this->renderCustomTools($this->appends);
    }
}
