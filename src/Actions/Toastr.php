<?php

namespace OpenAdmin\Admin\Actions;

use Illuminate\Support\Arr;

class Toastr
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param string $type
     * @param string $content
     *
     * @return $this
     */
    public function show($type, $content = '')
    {
        $this->type = $type;
        $this->content = $content;

        return $this;
    }

    /**
     * @param $option
     * @param $value
     *
     * @return $this
     */
    protected function options($option, $value)
    {
        Arr::set($this->options, $option, $value);

        return $this;
    }

    /**
     * @param $position
     *
     * @return Toastr
     */
    protected function position($position)
    {
        return $this->options('position', $position);
    }

    /**
     * @param $position
     *
     * @return Toastr
     */
    protected function gravity($position)
    {
        return $this->options('gravity', $position);
    }

    /**
     * @param $style
     *
     * @return Toastr
     */
    protected function style($style)
    {
        return $this->options('style', $style);
    }

    /**
     * @return Toastr
     */
    public function topCenter()
    {
        return $this->position('center')->gravity('top');
    }

    /**
     * @return Toastr
     */
    public function topLeft()
    {
        return $this->position('left')->gravity('top');
    }

    /**
     * @return Toastr
     */
    public function topRight()
    {
        return $this->position('right')->gravity('top');
    }

    /**
     * @return Toastr
     */
    public function bottomLeft()
    {
        return $this->position('left')->gravity('bottom');
    }

    /**
     * @return Toastr
     */
    public function bottomCenter()
    {
        return $this->position('center')->gravity('bottom');
    }

    /**
     * @return Toastr
     */
    public function bottomRight()
    {
        return $this->position('right')->gravity('bottom');
    }

    /**
     * @return Toastr
     */
    public function topFullWidth()
    {
        return $this->position('full')->gravity('top');
    }

    /**
     * @return Toastr
     */
    public function bottomFullWidth()
    {
        return $this->position('full')->gravity('bottom');
    }

    /**
     * @return Toastr
     */
    public function timeout($timeout = 5000)
    {
        return $this->options('duration', $timeout);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        if (!isset($this->options['position'])) {
            $this->topCenter();
        }

        return [
            'toastr' => [
                'type'    => $this->type,
                'content' => $this->content,
                'options' => $this->options,
            ],
        ];
    }
}
