<?php

namespace OpenAdmin\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Box extends Widget implements Renderable
{
    /**
     * @var string
     */
    protected $view = 'admin::widgets.box';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $content = 'here is the box content.';

    /**
     * @var string
     */
    protected $footer = '';

    /**
     * @var array
     */
    protected $tools = [];

    /**
     * @var array
     */
    protected $styles = [];

    /**
     * @var string
     */
    protected $script;

    /**
     * Box constructor.
     *
     * @param string $title
     * @param string $content
     */
    public function __construct($title = '', $content = '', $footer = '')
    {
        if ($title) {
            $this->title($title);
        }

        if ($content) {
            $this->content($content);
        }

        if ($footer) {
            $this->footer($footer);
        }

        $this->id = uniqid('box-');
        $this->class('card');
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

    /**
     * Set box content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content($content)
    {
        if ($content instanceof Renderable) {
            $this->content = $content->render();
        } else {
            $this->content = (string) $content;
        }

        return $this;
    }

    /**
     * Set box footer.
     *
     * @param string $footer
     *
     * @return $this
     */
    public function footer($footer)
    {
        if ($footer instanceof Renderable) {
            $this->footer = $footer->render();
        } else {
            $this->footer = (string) $footer;
        }

        return $this;
    }

    /**
     * Set box title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set box as collapsable.
     *
     * @return $this
     */
    public function collapsable()
    {
        $this->tools[] =
            '<button class="btn btn-box-tool box-tool-minimize" data-bs-toggle="collapse" data-bs-target="#'.$this->id.'-body"><i class="icon-minus"></i></button>';

        return $this;
    }

    /**
     * Set box as removable.
     *
     * @return $this
     */
    public function removable()
    {
        $this->tools[] =
            '<button class="btn btn-box-tool box-tool-remove" onclick="document.getElementById(\''.$this->id.'\').remove();"><i class="icon-times"></i></button>';

        return $this;
    }

    /**
     * Set box style.
     *
     * @param array $styles
     *
     * @return $this|Box
     */
    public function styles($styles)
    {
        $this->styles = array_merge($this->styles, $styles);

        return $this;
    }

    /**
     * Set styles as attibute.
     */
    public function setStyles()
    {
        $style = urldecode(http_build_query($this->styles));
        $style = str_replace('&', ';', $style);
        $style = str_replace('=', ':', $style);
        $this->style = $style;
    }

    /**
     * Variables in view.
     *
     * @return array
     */
    protected function variables()
    {
        $this->setStyles();

        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'content'    => $this->content,
            'footer'     => $this->footer,
            'tools'      => $this->tools,
            'attributes' => $this->formatAttributes(),
            'script'     => $this->script,
        ];
    }

    /**
     * Render box.
     *
     * @return string
     */
    public function render()
    {
        return view($this->view, $this->variables())->render();
    }
}
