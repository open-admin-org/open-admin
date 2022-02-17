
@php($listErrorKey = "$column.values")
@include("admin::form._header")

        <table class="table table-with-fields">

            <tbody class="list-{{$column}}-table">

            @foreach(old("{$column}.values", ($value ?: [])) as $k => $v)

                @php($itemErrorKey = "{$column}.values.{$loop->index}")

                <tr>
                    <td>
                        <div class="form-group {{ $errors->has($itemErrorKey) ? 'has-error' : '' }}">
                            <div class="col-sm-12">
                                <input name="{{ $column }}[values][]" value="{{ old("{$column}.values.{$k}", $v) }}" class="form-control" />
                                @if($errors->has($itemErrorKey))
                                    @foreach($errors->get($itemErrorKey) as $message)
                                        <label class="form-label" for="inputError"><i class="icon-times-circle-o"></i> {{$message}}</label><br/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </td>

                    <td style="width: 75px;">
                        <div class="{{$column}}-remove btn btn-warning btn-sm pull-right">
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
                <td>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input name="{{ $column }}[values][]" class="form-control" />
                        </div>
                    </div>
                </td>

                <td style="width: 75px;">
                    <div class="{{$column}}-remove btn btn-warning btn-sm pull-right">
                        <i class="icon-trash">&nbsp;</i>{{ __('admin.remove') }}
                    </div>
                </td>
            </tr>
        </template>

@include("admin::form._footer")
