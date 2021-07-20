<?php

namespace OpenAdmin\Admin\Grid\Filter\Presenter;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Admin;

class DateTime extends Presenter
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $format = 'YYYY-MM-DD HH:mm:ss';

    /**
     * DateTime constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = $this->getOptions($options);
    }

    /**
     * @param array $options
     *
     * @return mixed
     */
    protected function getOptions(array $options): array
    {
        $options['format'] = Arr::get($options, 'format', $this->format);
        $options['locale'] = Arr::get($options, 'locale', config('app.locale'));
        $options['weekNumbers'] = Arr::get($options, 'weekNumbers', true);
        $options['time_24hr'] = Arr::get($options, 'time_24hr', true);
        $options['enableSeconds'] = Arr::get($options, 'enableSeconds', true);
        $options['enableTime'] = Arr::get($options, 'enableTime', true);
        $options['allowInput'] = Arr::get($options, 'allowInput', true);

        return $options;
    }

    protected function prepare()
    {
        $script = "flatpickr('#{$this->filter->getId()}',".json_encode($this->options).');';
        Admin::script($script);
    }

    public function variables(): array
    {
        $this->prepare();

        return [
            'group' => $this->filter->group,
        ];
    }
}
