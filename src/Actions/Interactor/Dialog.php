<?php

namespace OpenAdmin\Admin\Actions\Interactor;

class Dialog extends Interactor
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function success($title, $text = '', $options = [])
    {
        return $this->addSettings($title, 'success', $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function error($title, $text = '', $options = [])
    {
        return $this->addSettings($title, 'error', $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return $this
     */
    public function warning($title, $text = '', $options = [])
    {
        return $this->addSettings($title, 'warning', $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function info($title, $text = '', $options = [])
    {
        return $this->addSettings($title, 'info', $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function question($title, $text = '', $options = [])
    {
        return $this->addSettings($title, 'question', $text, $options);
    }

    /**
     * @param string $title
     * @param string $text
     * @param array  $options
     *
     * @return Dialog
     */
    public function confirm($title, $text = '', $options = [])
    {
        return $this->addSettings($title, 'question', $text, $options);
    }

    /**
     * @param string $title
     * @param string $type
     * @param string $text
     * @param array  $options
     *
     * @return $this
     */
    protected function addSettings($title, $type, $text = '', $options = [])
    {
        $this->settings = array_merge(
            compact('title', 'text', 'type'),
            $options
        );

        return $this;
    }

    /**
     * @return array
     */
    protected function defaultSettings()
    {
        $trans = [
            'cancel' => trans('admin.cancel'),
            'submit' => trans('admin.submit'),
        ];

        return [
            'type'                => 'question',
            'showCancelButton'    => true,
            'showLoaderOnConfirm' => true,
            'confirmButtonText'   => $trans['submit'],
            'cancelButtonText'    => $trans['cancel'],
        ];
    }

    /**
     * @return string
     */
    protected function formatSettings()
    {
        if (empty($this->settings)) {
            return '';
        }

        $settings = array_merge($this->defaultSettings(), $this->settings);

        return trim(substr(json_encode($settings, JSON_PRETTY_PRINT), 1, -1));
    }

    public function addScript()
    {
        return '';
    }

    /**
     * @return void
     */
    public function preScript()
    {
        call_user_func([$this->action, 'dialog']);
        $settings = $this->formatSettings();

        return <<<SCRIPT
        Swal.fire({
            {$settings},
        }).then(function (result) {
            if (result.isConfirmed){
                resolve(result);
            }else{
                reject();
            }
        });
        SCRIPT;
    }
}
