<?php

namespace OpenAdmin\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Form\Field\Traits\HasValuePicker;
use OpenAdmin\Admin\Form\Field\Traits\PlainInput;

class Text extends Field
{
    use PlainInput;
    use HasValuePicker;

    /**
     * @var string
     */
    protected $icon = 'icon-pencil-alt';

    /**
     * @var bool
     */
    protected $withoutIcon = false;

    /**
     * Set custom fa-icon.
     *
     * @param string $icon
     *
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $this->initPlainInput();

        if (!$this->withoutIcon) {
            $this->prepend('<i class="'.$this->icon.'"></i>');
        }
        $this->defaultAttribute('type', 'text')
            ->defaultAttribute('id', $this->id)
            ->defaultAttribute('name', $this->elementName ?: $this->formatName($this->column))
            ->defaultAttribute('value', old($this->elementName ?: $this->column, $this->value()))
            ->defaultAttribute('class', 'form-control '.$this->getElementClassString())
            ->defaultAttribute('placeholder', $this->getPlaceholder())
            ->mountPicker()
            ->addVariables([
                'prepend' => $this->prepend,
                'append'  => $this->append,
            ]);

        return parent::render();
    }

    /**
     * Add inputmask to an elements.
     *
     * @param array $options
     *
     * @return $this
     */
    public function inputmask($options)
    {
        $options = json_encode_options($options);

        //$this->script = "$('{$this->getElementClassSelector()}').inputmask($options);";
        $this->script = "Inputmask({$options}).mask(document.querySelector(\"{$this->getElementClassSelector()}\"));";

        return $this;
    }

    /**
     * Add datalist element to Text input.
     *
     * @param array $entries
     *
     * @return $this
     */
    public function datalist($entries = [])
    {
        $this->defaultAttribute('list', "list-{$this->id}");

        $datalist = "<datalist id=\"list-{$this->id}\">";
        foreach ($entries as $k => $v) {
            $datalist .= "<option value=\"{$k}\">{$v}</option>";
        }
        $datalist .= '</datalist>';

        return $this->append($datalist);
    }

    /**
     * show no icon in font of input.
     *
     * @return $this
     */
    public function withoutIcon()
    {
        $this->withoutIcon = true;

        return $this;
    }
}
