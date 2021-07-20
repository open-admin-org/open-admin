<?php

namespace OpenAdmin\Admin\Form\Concerns;

use Illuminate\Support\Arr;

trait HasFormAttributes
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var array
     */
    protected $form_classes = [];

    /**
     * @var bool
     */
    protected $validateClientSide = false;

    /**
     * Initialize the form attributes.
     */
    protected function initFormAttributes()
    {
        $this->attributes = [
            'id'             => 'form-'.uniqid(),
            'method'         => 'POST',
            'action'         => '',
            'autocomplete'   => 'off',
            'class'          => '',
            'accept-charset' => 'UTF-8',
            'pjax-container' => true,
        ];
        $this->form_classes = ['form-horizontal', 'form', 'default-valid'];
    }

    /**
     * Add form attributes.
     *
     * @param string|array $attr
     * @param string       $value
     *
     * @return $this
     */
    public function attribute($attr, $value = '')
    {
        if (is_array($attr)) {
            foreach ($attr as $key => $value) {
                $this->attribute($key, $value);
            }
        } else {
            $this->attributes[$attr] = $value;
        }

        return $this;
    }

    /**
     * Add form removeAttribute.
     *
     * @param string $attr
     *
     * @return $this
     */
    public function removeAttribute($attr)
    {
        unset($this->attributes[$attr]);

        return $this;
    }

    /**
     * Format form attributes form array to html.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function formatAttribute($attributes = [])
    {
        $attributes = array_merge($this->attributes, $attributes);

        if (method_exists($this, 'hasFile') && $this->hasFile()) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        if (!empty($attributes['class'])) {
            $this->form_classes[] = $attributes['class'];
        }
        $attributes['class'] = implode(' ', $this->form_classes);

        $html = [];
        foreach ($attributes as $key => $val) {
            $html[] = "$key=\"$val\"";
        }

        return implode(' ', $html) ?: '';
    }

    public function addFormClass($class)
    {
        $this->form_classes[] = $class;

        return $this;
    }

    public function removeFormClass($class)
    {
        $this->form_classes = Arr::except($this->form_classes, [$class]);

        return $this;
    }

    /**
     * Enable client side validation.
     *
     * @return $this
     */
    public function enableValidate()
    {
        $this->validateClientSide = true;
        $this->attribute('novalidate', true);
        $this->addFormClass('needs-validation');

        return $this;
    }

    /**
     * disable client side validation.
     *
     * @return $this
     */
    public function disableValidate()
    {
        $this->validateClientSide = false;
        $this->removeAttribute('novalidate');
        $this->removeFormClass('needs-validation');

        return $this;
    }

    /**
     * Action uri of the form.
     *
     * @param string $action
     *
     * @return $this
     */
    public function action($action)
    {
        return $this->attribute('action', $action);
    }

    /**
     * Method of the form.
     *
     * @param string $method
     *
     * @return $this
     */
    public function method($method = 'POST')
    {
        if (strtolower($method) == 'put') {
            $this->hidden('_method')->default($method);

            return $this;
        }

        return $this->attribute('method', strtoupper($method));
    }

    /**
     * Disable Pjax.
     *
     * @return $this
     */
    public function disablePjax()
    {
        Arr::forget($this->attributes, 'pjax-container');

        return $this;
    }
}
