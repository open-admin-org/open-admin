@include("admin::form._header")

        <div id="has-many-{{$column}}">
            <table class="table table-with-fields has-many-{{$column}} vertical-align-{{$verticalAlign}}">
                <thead>
                <tr>
                    @if(!empty($options['sortable']))
                        <th></th>
                    @endif

                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach

                    <th class="hidden"></th>

                    @if($options['allowDelete'])
                        <th></th>
                    @endif
                </tr>
                </thead>
                <tbody class="has-many-{{$column}}-forms">
                @foreach($forms as $pk => $form)
                    <tr class="has-many-{{$column}}-form fields-group">

                        @if(!empty($options['sortable']))
                           <td width="20"><span class="icon-arrows-alt-v btn btn-light handle"></span></td>
                        @endif

                        <?php $hidden = ''; ?>

                        @foreach($form->fields() as $field)

                            @if (is_a($field, \OpenAdmin\Admin\Form\Field\Hidden::class))
                                <?php $hidden .= $field->render(); ?>
                                @continue
                            @endif

                            <td>{!! $field->setLabelClass(['hidden'])->setWidth(12, 0)->render() !!}</td>
                        @endforeach

                        <td class="hidden">{!! $hidden !!}</td>

                        @if($options['allowDelete'])
                            <td class="form-group">
                                <div>
                                    <div class="remove btn btn-danger btn-sm pull-right"><i class="icon-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>

            <template class="{{$column}}-tpl">
                <tr class="has-many-{{$column}}-form fields-group">

                    @if(!empty($options['sortable']))
                        <td width="20"><span class="icon-arrows-alt-v btn btn-light handle"></span></td>
                    @endif

                    {!! $template !!}

                    <td class="form-group">
                        <div>
                            <div class="remove btn btn-danger btn-sm pull-right"><i class="icon-trash">&nbsp;</i>{{ trans('admin.remove') }}</div>
                        </div>
                    </td>
                </tr>
            </template>

            @if($options['allowCreate'])
                <div class="form-group">
                    <div class="{{$viewClass['field']}}">
                        <div class="add btn btn-success btn-sm"><i class="icon-plus"></i>&nbsp;{{ trans('admin.new') }}</div>
                    </div>
                </div>
            @endif
        </div>
@include("admin::form._footer")
