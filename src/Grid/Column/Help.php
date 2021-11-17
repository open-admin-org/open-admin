<?php

namespace OpenAdmin\Admin\Grid\Column;

use Illuminate\Contracts\Support\Renderable;

class Help implements Renderable
{
    /**
     * @var string
     */
    protected $message = '';

    /**
     * Help constructor.
     *
     * @param string $message
     */
    public function __construct($message = '')
    {
        $this->message = $message;
    }

    /**
     * Render help  header.
     *
     * @return string
     */
    public function render()
    {
        $data = [
            'data-bs-toggle'    => 'tooltip',
            'data-bs-placement' => 'top',
            'data-bs-html'      => 'true',
            'title'             => $this->message,
        ];

        $data = collect($data)->map(function ($val, $key) {
            return "{$key}=\"{$val}\"";
        })->implode(' ');

        return <<<HELP
<a href="javascript:void(0);" class="grid-column-help" {$data}>
    <i class="icon-question-circle"></i>
</a>
HELP;
    }
}
