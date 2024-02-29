<?php

namespace OpenAdmin\Admin\Form\Field\Traits;

use Illuminate\Contracts\Support\Arrayable;

trait HasJavascriptConfig
{
    /**
     * Config options for fields with javascript.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Set the field config.
     *
     * @param array $config
     *
     * @return $this
     */
    public function config($config = [])
    {
        if ($config instanceof Arrayable) {
            $config = $config->toArray();
        }

        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * Set config options key for field.
     *
     * @param string $key
     * @param mixed  $val
     *
     * @return $this
     */
    public function configKey($key, $val)
    {
        $this->config[$key] = $val;

        return $this;
    }

    /**
     * Get config object for
     *
     * @param string $key
     * @param mixed  $val
     *
     * @return $this
     */
    public function getJsConfig($config = false)
    {
        $config     = $config ?? $this->config;
        $str_config = json_encode($config);
        $str_config = str_replace(['"<js>', "'<js>", "<\/js>'", '<\/js>"', "</js>'", '</js>"'], '', $str_config);

        return $str_config;
    }
}
