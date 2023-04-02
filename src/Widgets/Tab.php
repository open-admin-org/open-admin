<?php

namespace OpenAdmin\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Tab extends Widget implements Renderable
{
    use ContainsForms;

    public const TYPE_CONTENT = 1;
    public const TYPE_LINK    = 2;

    /**
     * @var string
     */
    protected $view = 'admin::widgets.tab';

    /**
     * @var array
     */
    protected $data = [
        'id'       => '',
        'title'    => '',
        'tabs'     => [],
        'dropDown' => [],
        'active'   => 0,
    ];

    public function __construct()
    {
        $this->class('nav-tabs-custom');
    }

    /**
     * Add a tab and its contents.
     *
     * @param string            $title
     * @param string|Renderable $content
     * @param bool              $active
     * @param string|null       $id
     *
     * @return $this
     */
    public function add($title, $content, $active = false, $id = null)
    {
        if ($active) {
            $this->data['active'] = count($this->data['tabs']);
        }

        $this->data['tabs'][] = [
            'id'      => $id ?: mt_rand(),
            'ref'     => is_numeric($title) ? '_'.$title : $title,
            'title'   => $title,
            'content' => $content,
            'type'    => static::TYPE_CONTENT,
        ];

        return $this;
    }

    /**
     * Add a link on tab.
     *
     * @param string $title
     * @param string $href
     * @param bool   $active
     *
     * @return $this
     */
    public function addLink($title, $href, $active = false)
    {
        if ($active) {
            $this->data['active'] = count($this->data['tabs']);
        }
        $this->data['tabs'][] = [
            'id'    => mt_rand(),
            'title' => $title,
            'href'  => $href,
            'type'  => static::TYPE_LINK,
        ];

        return $this;
    }

    /**
     * Set title.
     *
     * @param string $title
     */
    public function title($title = '')
    {
        $this->data['title'] = $title;
    }

    /**
     * Set drop-down items.
     *
     * @param array $links
     *
     * @return $this
     */
    public function dropDown(array $links)
    {
        if (is_array($links[0])) {
            foreach ($links as $link) {
                call_user_func([$this, 'dropDown'], $link);
            }

            return $this;
        }

        $this->data['dropDown'][] = [
            'name' => $links[0],
            'href' => $links[1],
        ];

        return $this;
    }

    /**
     * Render Tab.
     *
     * @return string
     */
    public function render()
    {
        $data = array_merge(
            $this->data,
            ['attributes' => $this->formatAttributes()]
        );

        return view($this->view, $data)->render();
    }
}
