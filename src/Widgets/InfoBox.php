<?php

namespace OpenAdmin\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class InfoBox extends Widget implements Renderable
{
    /**
     * @var string
     */
    protected $view = 'admin::widgets.info-box';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * InfoBox constructor.
     *
     * @param string $name
     * @param string $icon
     * @param string $color
     * @param string $link
     * @param string $info
     */
    public function __construct($name, $icon, $color, $link, $info)
    {
        $this->data = [
            'name'      => $name,
            'icon'      => $icon,
            'link'      => $link,
            'color'     => $color,
            'info'      => $info,
            'link_text' => trans('admin.more'),
        ];
        $this->id = uniqid('info-box-');

        $this->class("card info-box alert alert-$color");
    }

    /**
     * Set box id.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function name($name)
    {
        $this->data['name'] = $name;

        return $this;
    }

    public function icon($icon)
    {
        $this->data['icon'] = $icon;

        return $this;
    }

    public function link($link)
    {
        $this->data['link'] = $link;

        return $this;
    }

    public function link_text($link_text)
    {
        $this->data['link_text'] = $link_text;

        return $this;
    }

    public function info($info)
    {
        $this->data['info'] = $info;

        return $this;
    }

    public function color($color)
    {
        $this->data['color'] = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $variables = array_merge($this->data, ['attributes' => $this->formatAttributes()]);

        return view($this->view, $variables)->render();
    }
}
