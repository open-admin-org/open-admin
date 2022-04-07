<div id="has-many-{{$column}}" class="nav-tabs-custom has-many-{{$column}}">
    <div class="row header has-many-head ">
        <h4>{{ $label }}</h4>
    </div>

    <hr style="margin-top: 0px;" class="form-border m-0">

    <ul class="nav nav-tabs">
        @foreach($forms as $pk => $form)
            <li id="tab_{{ $relationName . '_' . $pk }}" class="nav-item">
                <a class="nav-link @if ($form == reset($forms)) active @endif " href="#{{ $relationName . '_' . $pk }}" data-bs-toggle="tab">
                    {{ $pk }} <i class="icon-exclamation-circle text-red hide"></i>
                </a>
            </li>
        @endforeach
        <li class="nav-item add-tab">
            <button type="button" class="btn btn-light btn-sm add"><i class="icon-plus-circle" style="font-size: large;"></i></button>
        </li>

    </ul>

    <div class="tab-content has-many-{{$column}}-forms">

        @foreach($forms as $pk => $form)
            <div class="tab-pane fields-group has-many-{{$column}}-form @if ($form == reset($forms)) active @endif" id="{{ $relationName . '_' . $pk }}">
                @foreach($form->fields() as $field)
                    {!! $field->render() !!}
                @endforeach

                @if($options['allowDelete'])
                <div class="form-group form-delete-group">
                    <label class="{{$viewClass['label']}} form-label"></label>
                    <div class="{{$viewClass['field']}}">
                        <div class="remove btn btn-danger btn-sm pull-right"><i class="icon-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                    </div>
                </div>
                @endif
            </div>
        @endforeach
    </div>

    <template class="{{$column}}-tab-tpl">
        <li class="new nav-item" id="tab_{{ $relationName . '_new_' . \OpenAdmin\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">
            <a class="nav-link" href="#{{ $relationName . '_new_' . \OpenAdmin\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}" data-bs-toggle="tab">
                &nbsp;New {{ \OpenAdmin\Admin\Form\NestedForm::DEFAULT_KEY_NAME }} <i class="icon-exclamation-circle text-red hide"></i>
            </a>
        </li>
    </template>
    <template  class="{{$column}}-tpl">
        <div class="tab-pane fields-group new" id="{{ $relationName . '_new_' . \OpenAdmin\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">
            {!! $template !!}
            @if($options['allowDelete'])
            <div class="form-group form-delete-group">
                <label class="{{$viewClass['label']}} form-label"></label>
                <div class="{{$viewClass['field']}}">
                    <div class="remove btn btn-danger btn-sm pull-right"><i class="icon-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                </div>
            </div>
            @endif
        </div>
    </template>

</div>
