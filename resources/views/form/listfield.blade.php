
@php($listErrorKey = "$column")
@include("admin::form._header")

        <table class="table table-with-fields">

            <tbody class="list-{{$column}}-table">

            @foreach(old("{$column}", ($value ?: [])) as $k => $v)

                @php($itemErrorKey = "{$column}.{$loop->index}")

                <tr>
                    @if(!empty($options['sortable']))
                        <td width="20"><span class="icon-arrows-alt-v btn btn-light handle"></span></td>
                    @endif
                    <td>
                        <div class="form-group {{ $errors->has($itemErrorKey) ? 'has-error' : '' }}">
                            <div class="col-sm-12">
                                <input name="{{ $column }}[]" value="{{ old("{$column}.{$k}", $v) }}" class="form-control" />
                                @if($errors->has($itemErrorKey))
                                    @foreach($errors->get($itemErrorKey) as $message)
                                        <label class="form-label" for="inputError"><i class="icon-times-circle-o"></i> {{$message}}</label><br/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </td>

                    <td style="width: 75px;">
                        <div class="{{$column}}-remove btn btn-danger btn-sm pull-right">
                            <i class="icon-trash">&nbsp;</i>{{ __('admin.remove') }}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="{{ $column }}-add btn btn-success btn-sm pull-right">
            <i class="icon-plus"></i>&nbsp;{{ __('admin.new') }}
        </div>

        <template class="{{$column}}-tpl">
            <tr>
                @if(!empty($options['sortable']))
                    <td width="20"><span class="icon-arrows-alt-v btn btn-light handle"></span></td>
                @endif
                <td>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input name="{{ $column }}[]" class="form-control" />
                        </div>
                    </div>
                </td>

                <td style="width: 75px;">
                    <div class="{{$column}}-remove btn btn-danger btn-sm pull-right">
                        <i class="icon-trash">&nbsp;</i>{{ __('admin.remove') }}
                    </div>
                </td>
            </tr>
        </template>

@include("admin::form._footer")
