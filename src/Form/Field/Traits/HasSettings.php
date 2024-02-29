<?php

namespace OpenAdmin\Admin\Form\Field\Traits;

use Illuminate\Contracts\Support\Arrayable;

trait HasSettings
{
    /**
     * Element Settings
     *
     * @var array
     */
    protected $settings;

    /**
     * Set the field settings.
     *
     * @param array $settings
     *
     * @return $this
     */
    public function settings(array $settings = [])
    {
        if ($settings instanceof Arrayable) {
            $settings = $settings->toArray();
        }

        $this->settings = array_merge($this->settings, $settings);

        return $this;
    }

    /**
     * get settings key for field.
     *
     * @param string $key
     *
     * @return $settings[$key]
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set settings key for field.
     *
     * @param string $key
     * @param mixed  $val
     *
     * @return $this
     */
    public function setting($key, $val)
    {
        $this->settings[$key] = $val;

        return $this;
    }

    /**
     * get settings key for field.
     *
     * @param string $key
     *
     * @return $settings[$key]
     */
    public function getSetting($key)
    {
        return $this->settings[$key];
    }
}
