<?php

namespace OpenAdmin\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Form\Field\Traits\ImageField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends File
{
    use ImageField;

    /**
     * @inheritdoc
     */
    protected $view = 'admin::form.file';

    /**
     *  Validation rules.
     *
     * @var string
     */
    protected $rules = 'image';

    protected function setType($type = 'image')
    {
        $this->options['type'] = $type;
    }

    /**
     * @param array|UploadedFile $image
     *
     * @return string
     */
    public function prepare($file)
    {
        if ($this->picker) {
            return parent::prepare($file);
        }

        if (request()->has($this->column.Field::FILE_DELETE_FLAG)) {
            $this->destroy();

            return '';
        }

        if (!empty($file)) {
            if ($this->picker) {
                return parent::prepare($file);
            }
            $this->name = $this->getStoreName($file);

            $this->callInterventionMethods($file->getRealPath());

            $path = $this->uploadAndDeleteOriginal($file);

            $this->uploadAndDeleteOriginalThumbnail($file);

            return $path;
        }

        return false;
    }

    /**
     * force file type to image.
     *
     * @param $file
     *
     * @return array|bool|int[]|string[]
     */
    public function guessPreviewType($file)
    {
        $extra         = parent::guessPreviewType($file);
        $extra['type'] = 'image';

        return $extra;
    }
}
