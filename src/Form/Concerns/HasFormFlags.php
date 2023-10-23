<?php

namespace OpenAdmin\Admin\Form\Concerns;

trait HasFormFlags
{
    public const NEW_KEY_NAME = '__NEW_KEY__';
    public const DEFAULT_KEY_NAME = '__INDEX_KEY__';
    public const REMOVE_FLAG_NAME = '__REMOVE_KEY__';
    public const REMOVE_FLAG_CLASS = 'fom-removed';
}
