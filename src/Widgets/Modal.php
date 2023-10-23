<?php

namespace OpenAdmin\Admin\Widgets;

use Illuminate\Contracts\Support\Renderable;

class Modal extends Widget implements Renderable
{
    /**
     * @var string
     */
    protected $view = 'admin::widgets.modal';
    public $data;

    /**
     * Modal constructor.
     *
     * @param string $options
     */
    public function __construct($options = [])
    {
        $this->default = [
            'visiable'    => true,
            'show_loader' => true,
            'body'        => '',
            'title'       => '',
            'footer'      => '',
            'id'          => uniqid('modal-'),
        ];

        $this->data = array_merge($this->default, $options);

        $this->attributes = [
            'tabindex' => '-1',
            'dialog'   => 'dialog',
        ];

        $this->class('modal fade resource-modal');
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
        $this->data['id'] = $id;

        return $this;
    }

    public function title($title)
    {
        $this->data['title'] = $title;

        return $this;
    }

    public function footer($footer)
    {
        $this->data['footer'] = $footer;

        return $this;
    }

    public function body($body)
    {
        $this->data['body'] = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->data['show_loader'] = !empty($this->data['body']);
        $this->id                  = $this->data['id'];

        $variables = array_merge($this->data, ['attributes' => $this->formatAttributes()]);

        return view($this->view, $variables)->render();
    }
}
