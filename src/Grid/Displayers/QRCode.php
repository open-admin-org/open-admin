<?php

namespace OpenAdmin\Admin\Grid\Displayers;

/**
 * Class QRCode.
 */
class QRCode extends AbstractDisplayer
{
    public function display($formatter = null, $width = 150, $height = 150)
    {
        $content = $this->getColumn()->getOriginal();

        if ($formatter instanceof \Closure) {
            $content = call_user_func($formatter, $content, $this->row);
        }

        $img = sprintf(
            "<img src='https://api.qrserver.com/v1/create-qr-code/?size=%sx%s&data=%s' style='height:%spx;width:%spx;'/>",
            $width,
            $height,
            $content,
            $height,
            $width
        );
        $value = $this->getValue();
        if (empty($value)) {
            return '';
        }

        return <<<HTML
<a href="javascript:void(0);" class="grid-column-qrcode text-muted" data-bs-content="{$img}" data-bs-html="true" data-bs-toggle='popover' data-bs-trigger="focus" tabindex='0'>
    <i class="icon-qrcode"></i>
</a>&nbsp;{$value}
HTML;
    }
}
