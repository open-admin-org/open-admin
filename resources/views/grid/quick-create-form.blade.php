<thead>
<tr class="quick-create">
    <td colspan="{{ $columnCount }}" style="height: 60px;padding-left: 50px;background-color: #f9f9f9; vertical-align: middle;">

        <span class="create" style="color: #bdbdbd;cursor: pointer;display: inline-block;">
             <i class="icon-plus"></i>&nbsp;{{ __('admin.quick_create') }}
        </span>

        <form class="row align-items-center gy-2 gx-3 create-form" autocomplete="off" style="display: none;width:calc(100% - 50px);" method="post" action='{{$url}}'>
            @foreach($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <div class="col-auto">
                <button class="btn btn-primary btn-sm">{{ __('admin.submit') }}</button>&nbsp;
                <a href="javascript:void(0);" class="cancel">{{ __('admin.cancel') }}</a>
            </div>
            {{ csrf_field() }}

        </form>
    </td>
</tr>
</thead>