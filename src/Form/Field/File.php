<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Form\Field\Traits\HasMediaPicker;
use OpenAdmin\Admin\Form\Field\Traits\UploadField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File extends Field
{
    use UploadField;
    use HasMediaPicker;

    protected static $css = [
        '/vendor/open-admin/fields/file-upload/file-upload.css',
    ];

    protected static $js = [
        '/vendor/open-admin/fields/file-upload/file-upload.js',
    ];

    public $type     = 'file';
    public $readonly = false;

    /**
     * Create a new File instance.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->initStorage();

        parent::__construct($column, $arguments);
    }

    /**
     * Default directory for file to upload.
     *
     * @return mixed
     */
    public function defaultDirectory()
    {
        return config('admin.upload.directory.file');
    }

    /**
     * @inheritdoc
     */
    public function getValidator(array $input)
    {
        if (request()->has(static::FILE_DELETE_FLAG)) {
            return false;
        }

        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        /*
         * If has original value, means the form is in edit mode,
         * then remove required rule from rules.
         */
        if ($this->original()) {
            $this->removeRule('required');
        }

        /*
         * Make input data validatable if the column data is `null`.
         */
        if (Arr::has($input, $this->column) && is_null(Arr::get($input, $this->column))) {
            $input[$this->column] = '';
        }

        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        $rules[$this->column]      = $fieldRules;
        $attributes[$this->column] = $this->label;

        return \validator($input, $rules, $this->getValidationMessages(), $attributes);
    }

    /**
     * Prepare for saving.
     *
     * @param UploadedFile|array $file
     *
     * @return mixed|string
     */
    public function prepare($file)
    {
        if (request()->has($this->column.Field::FILE_DELETE_FLAG)) {
            $this->destroy();

            return '';
        }

        if (!empty($this->picker) && request()->has($this->column.Field::FILE_ADD_FLAG)) {
            return request($this->column.Field::FILE_ADD_FLAG);
        }

        if (!empty($file)) {
            $this->name = $this->getStoreName($file);

            return $this->uploadAndDeleteOriginal($file);
        }

        return false;
    }

    /**
     * Upload file and delete original file.
     *
     * @param UploadedFile $file
     *
     * @return mixed
     */
    protected function uploadAndDeleteOriginal(UploadedFile $file)
    {
        $this->renameIfExists($file);

        $path = null;

        if (!is_null($this->storagePermission)) {
            $path = $this->storage->putFileAs($this->getDirectory(), $file, $this->name, $this->storagePermission);
        } else {
            $path = $this->storage->putFileAs($this->getDirectory(), $file, $this->name);
        }

        $this->destroy();

        return $path;
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return string
     */
    protected function preview()
    {
        return $this->objectUrl($this->value);
    }

    /**
     * Hides the file preview.
     *
     * @return $this
     */
    public function hidePreview()
    {
        return $this->options([
            'showPreview' => false,
        ]);
    }

    /**
     * Initialize the caption.
     *
     * @param string $caption
     *
     * @return string
     */
    protected function initialCaption($caption)
    {
        return basename($caption);
    }

    /**
     * @return array
     */
    protected function initialPreviewConfig()
    {
        $config = ['caption' => basename($this->value), 'key' => 0];

        $config = array_merge($config, $this->guessPreviewType($this->value));

        return [$config];
    }

    protected function setType($type = 'file')
    {
        $this->options['type'] = $type;
    }

    protected function getFieldId()
    {
        if (!empty($this->elementName)) {
            $id = $this->elementName;
        } else {
            $id = $this->id;
        }
        $id = str_replace(']', '_', $id);
        $id = str_replace('[', '_', $id);

        return $id;
    }

    /**
     * Setupscript.
     *
     * @return nothing
     */
    protected function setupScripts()
    {
        $id = $this->getFieldId();
        $this->setType();
        $this->attribute('id', $id);
        $this->options['storageUrl'] = $this->storageUrl();
        $json_options                = json_encode($this->options);
        $this->script                = <<<JS
        var FileUpload_{$id} = new FileUpload(document.querySelector('#{$id}'),{$json_options});
        JS;
    }

    /**
     * Render file upload field.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        if ($this->picker) {
            $this->renderMediaPicker();
        }

        $this->options(['overwriteInitial' => true, 'msgPlaceholder' => trans('admin.choose_file')]);

        $this->setupDefaultOptions();

        if (!empty($this->value)) {
            $this->attribute('data-files', $this->value);
            $this->attribute('data-file-captions', $this->initialCaption($this->value));

            $this->setupPreviewOptions();
            /*
             * If has original value, means the form is in edit mode,
             * then remove required rule from rules.
             */
            unset($this->attributes['required']);
        }

        $this->setupScripts();

        return parent::render();
    }
}
