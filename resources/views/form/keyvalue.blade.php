<div class="{{$viewClass['form-group']}}">

    <label class="{{$viewClass['label']}} form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">
        <table class="table table-with-fields">
            <thead>
            <tr>
                @if(!empty($options['sortable']))
                    <th></th>
                @endif
                <th>{{ __('Key') }}</th>
                <th>{{ __('Value') }}</th>
                <th style="width: 75px;"></th>
            </tr>
            </thead>
            <tbody class="kv-{{$column}}-table">

            @foreach(old("{$column}.keys", ($value ?: [])) as $k => $v)

                @php($keysErrorKey = "{$column}.keys.{$loop->index}")
                @php($valsErrorKey = "{$column}.values.{$loop->index}")

                <tr>
                    @if(!empty($options['sortable']))
                        <td width="20"><span class="icon-arrows-alt-v btn btn-light handle"></span></td>
                    @endif
                    <td>
                        <div class="form-group {{ $errors->has($keysErrorKey) ? 'has-error' : '' }}">
                            <div class="col-sm-12">
                                <input name="{{ $name }}[keys][]" value="{{ old("{$column}.keys.{$k}", $k) }}" class="form-control" required/>

                                @if($errors->has($keysErrorKey))
                                    @foreach($errors->get($keysErrorKey) as $message)
                                        <label class="form-label" for="inputError"><i class="icon-times-circle-o"></i> {{$message}}</label><br/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group {{ $errors->has($valsErrorKey) ? 'has-error' : '' }}">
                            <div class="col-sm-12">
                                <input name="{{ $name }}[values][]" value="{{ old("{$column}.values.{$k}", $v) }}" class="form-control" />
                                @if($errors->has($valsErrorKey))
                                    @foreach($errors->get($valsErrorKey) as $message)
                                        <label class="form-label" for="inputError"><i class="icon-times-circle-o"></i> {{$message}}</label><br/>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </td>

                    <td class="form-group">
                        <div>
                            <div class="{{$column}}-remove btn btn-danger btn-sm pull-right">
                                <i class="icon-trash">&nbsp;</i>{{ __('admin.remove') }}
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
        <div class="{{ $column }}-add btn btn-success btn-sm pull-right">
            <i class="icon-plus"></i>&nbsp;{{ __('admin.new') }}
        </div>

    </div>
    <template class="{{$column}}-tpl">
        <tr>
            @if(!empty($options['sortable']))
                <td width="20"><span class="icon-arrows-alt-v btn btn-light handle"></span></td>
            @endif
            <td>
                <div class="form-group  ">
                    <div class="col-sm-12">
                        <input name="{{ $name }}[keys][]" class="form-control" required/>
                    </div>
                </div>
            </td>
            <td>
                <div class="form-group  ">
                    <div class="col-sm-12">
                        <input name="{{ $name }}[values][]" class="form-control" />
                    </div>
                </div>
            </td>

            <td class="form-group">
                <div>
                    <div class="{{$column}}-remove btn btn-danger btn-sm pull-right">
                        <i class="icon-trash">&nbsp;</i>{{ __('admin.remove') }}
                    </div>
                </div>
            </td>
        </tr>
    </template>
</div>
