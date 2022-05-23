<?php

namespace OpenAdmin\Admin\Form\Field;

use Illuminate\Support\Arr;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Form\Field;
use OpenAdmin\Admin\Form\Field\Traits\HasMediaPicker;
use OpenAdmin\Admin\Form\Field\Traits\UploadField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MultipleFile extends Field
{
    use UploadField;
    use HasMediaPicker;

    protected static $css = [
        '/vendor/open-admin/fields/file-upload/file-upload.css',
    ];

    protected static $js = [
        '/vendor/open-admin/fields/file-upload/file-upload.js',
    ];

    public $must_prepare = true;
    public $type = 'file';
    public $readonly = false;
    public $multiple = true;

    /**
     * Create a new File instance.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->initStorage();
        $this->must_prepare = true;

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
     * {@inheritdoc}
     */
    public function getValidator(array $input)
    {
        if (request()->has(static::FILE_DELETE_FLAG)) {
            return false;
        }

        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        $attributes[$this->column] = $this->label;

        list($rules, $input) = $this->hydrateFiles(Arr::get($input, $this->column, []));

        return \validator($input, $rules, $this->getValidationMessages(), $attributes);
    }

    /**
     * Hydrate the files array.
     *
     * @param array $value
     *
     * @return array
     */
    protected function hydrateFiles(array $value)
    {
        if (empty($value)) {
            return [[$this->column => $this->getRules()], []];
        }

        $rules = $input = [];

        foreach ($value as $key => $file) {
            $rules[$this->column.$key] = $this->getRules();
            $input[$this->column.$key] = $file;
        }

        return [$rules, $input];
    }

    /**
     * Sort files.
     *
     * @param string $order
     *
     * @return array
     */
    protected function sortFiles($order, $updated_files)
    {
        $order = explode(',', trim($order, ','));
        if ($updated_files === false) {
            $updated_files = $this->original();
        }

        usort($updated_files, function ($a, $b) use ($order) {
            $pos_a = array_search($a, $order);
            $pos_b = array_search($b, $order);

            if ($pos_a === false || $pos_b === false) {
                return 0;
            }

            return ($pos_a < $pos_b) ? -1 : 1;
        });

        return $updated_files;
    }

    /**
     * Add files.
     *
     * @param string $files
     *
     * @return array
     */
    protected function addFiles($add, $updated_files)
    {
        $add = explode(',', trim($add, ','));
        if ($updated_files === false) {
            $updated_files = $this->original();
        }

        $updated_files = array_merge($updated_files, $add);

        return $updated_files;
    }

    /**
     * Prepare for saving.
     *
     * @param UploadedFile|array $files
     *
     * @return mixed|string
     */
    public function prepare($files)
    {
        $delete_key = $this->column.Field::FILE_DELETE_FLAG;
        $updated_files = false;
        if (request()->has($delete_key)) {
            if ($this->pathColumn) {
                $updated_files = $this->destroyFromHasMany(request($delete_key));
            } else {
                $updated_files = $this->destroy(request($delete_key));
            }
        }

        if (!empty($this->picker) && request()->has($this->column.Field::FILE_ADD_FLAG)) {
            $updated_files = $this->addFiles(request($this->column.Field::FILE_ADD_FLAG), $updated_files);
        }

        $sort_key = $this->column.static::FILE_SORT_FLAG;
        if (request()->has($sort_key)) {
            if ($this->sortColumn) {
                $updated_files = $this->sortFilesFromHasMany(request($sort_key), $updated_files);
            } else {
                $updated_files = $this->sortFiles(request($sort_key), $updated_files);
            }
        }

        if (!empty($files)) {
            $targets = array_map([$this, 'prepareForeach'], $files);

            // for create or update
            if ($this->pathColumn) {
                $targets = array_map(function ($target) {
                    return [$this->pathColumn => $target];
                }, $targets);
            }

            if ($updated_files === false) {
                $updated_files = $this->original();
            }
            if ($this->sortColumn) {
                foreach ($targets as $key => $value) {
                    $targets[$key][$this->sortColumn] = $key + count($updated_files);
                }
            }

            $updated_files = array_merge($updated_files, $targets);
        }

        return $updated_files;
    }

    /**
     * @return array|mixed
     */
    public function original()
    {
        if (empty($this->original)) {
            return [];
        }
        $this->original = $this->fixIfJsonString($this->original);

        return $this->original;
    }

    /**
     * Prepare for each file.
     *
     * @param UploadedFile $file
     *
     * @return mixed|string
     */
    protected function prepareForeach(UploadedFile $file = null)
    {
        $this->name = $this->getStoreName($file);

        return tap($this->upload($file), function () {
            $this->name = null;
        });
    }

    /**
     * Preview html for file-upload plugin.
     *
     * @return array
     */
    protected function preview()
    {
        $files = $this->value ?: [];
        $files = $this->fixIfJsonString($files);

        if (!empty($files[0]) && is_array($files[0]) && $this->pathColumn) {
            if ($this->sortColumn) {
                array_multisort(array_column($files, $this->sortColumn), SORT_ASC, $files);
            }
            $files_preview = [];
            foreach ($files as $index => $file) {
                $files_preview[] = Arr::get($file, $this->pathColumn);
            }
            $files = $files_preview;
        }

        return implode(',', array_values($files));
    }

    public function fixIfJsonString($arr)
    {
        if (!empty($arr) && !is_array($arr)) {
            $arr = json_decode($arr);
        }

        return $arr;
    }

    /**
     * Initialize the caption.
     *
     * @param array $caption
     *
     * @return string
     */
    protected function initialCaption($caption)
    {
        if (empty($caption)) {
            return '';
        }

        $caption = array_map('basename', $caption);

        return implode(',', $caption);
    }

    /**
     * @return array
     */
    protected function initialPreviewConfig()
    {
        $files = $this->value ?: [];
        $files = $this->fixIfJsonString($files);

        $config = [];

        foreach ($files as $index => $file) {
            if (is_array($file) && $this->pathColumn) {
                $index = Arr::get($file, $this->getRelatedKeyName(), $index);
                $file = Arr::get($file, $this->pathColumn);
            }

            $preview = array_merge([
                'caption' => basename($file),
                'key'     => $index,
            ], $this->guessPreviewType($file));

            $config[] = $preview;
        }

        return $config;
    }

    /**
     * Get related model key name.
     *
     * @return string
     */
    protected function getRelatedKeyName()
    {
        if (is_null($this->form)) {
            return;
        }

        return $this->form->model()->{$this->column}()->getRelated()->getKeyName();
    }

    /**
     * Allow to sort files.
     *
     * @return $this
     */
    public function sortable()
    {
        $this->fileActionSettings['showDrag'] = true;

        return $this;
    }

    protected function setType($type = 'file')
    {
        $this->options['type'] = $type;
    }

    /**
     * Destroy original files.
     *
     * @param string $key
     *
     * @return array
     */
    public function destroy($remove_me)
    {
        $remove_me = explode(',', trim($remove_me, ','));

        $files = $this->original() ?: [];

        foreach ($remove_me as $file) {
            $this->destroyFile($file);

            $files = array_diff($files, [$file]);
        }

        return array_values($files);
    }

    public function destroyFile($file)
    {
        if (!$this->retainable && $this->storage->exists($file)) {
            /* If this field class is using ImageField trait i.e MultipleImage field,
            we loop through the thumbnails to delete them as well. */
            if (isset($this->thumbnails) && method_exists($this, 'destroyThumbnailFile')) {
                foreach ($this->thumbnails as $name => $_) {
                    $this->destroyThumbnailFile($file, $name);
                }
            }
            $this->storage->delete($file);
        }
    }

    /**
     * Destroy original files from hasmany related model.
     *
     * @param int $key
     *
     * @return array
     */
    public function destroyFromHasMany($remove_me)
    {
        $remove_me = explode(',', trim($remove_me, ','));

        $files = collect($this->original ?: [])->keyBy($this->getRelatedKeyName())->toArray();

        foreach ($files as $key => $file_obj) {
            $file = $file_obj[$this->pathColumn];
            if (in_array($file, $remove_me)) {
                $this->destroyFile($file);
                $files[$key][Form::REMOVE_FLAG_NAME] = 1;
            }
        }

        return $files;
    }

    /**
     * Sort files.
     *
     * @param string $order
     * @param array  $files
     *
     * @return array
     */
    protected function sortFilesFromHasmany($order, $files)
    {
        $order = explode(',', trim($order, ','));
        if ($files === false) {
            $files = collect($this->original ?: [])->keyBy($this->getRelatedKeyName())->toArray();
        }

        foreach ($files as $key => $file_obj) {
            $file = $file_obj[$this->pathColumn];
            $files[$key][$this->sortColumn] = array_search($file, $order);
        }

        return $files;
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
        $json_options = json_encode($this->options);
        $this->script = <<<JS
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
        $this->attribute('multiple', true);
        $this->setupDefaultOptions();

        if (empty($this->value)) {
            $this->value = [];
        }

        if ($this->picker) {
            $this->renderMediaPicker();
        }

        if (!is_array($this->value)) {
            //try decoding json
            $this->value = json_decode($this->value);
            if (!is_array($this->value)) {
                throw new \Exception('Column: '.$this->column.' with Label: '.$this->label.'; value is not empty and not a valid Array');
            }
        }

        if (!empty($this->value)) {
            $this->attribute('data-files', $this->preview());
            $this->setupPreviewOptions();
        }

        $options = json_encode($this->options);

        $this->setupScripts($options);

        return parent::render();
    }
}
