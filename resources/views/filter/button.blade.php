<div class="btn-group " style="margin-right: 5px">
    <label class="btn btn-sm btn-primary btn-filter {{ $btn_class }} {{ $expand ? '' : 'collapsed' }}" title="{{ trans('admin.filter') }}" data-bs-toggle="collapse" href="#{{ $filter_id }}" role="button" aria-expanded="false" aria-controls="{{ $filter_id }}">
        <i class="icon-filter"></i><span class="hidden-xs">&nbsp;&nbsp;{{ trans('admin.filter') }}</span>@if($scopes->isEmpty())<i class="icon-angle-down"></i>@endif<i class="icon-angle-up"></i>
    </label>

    @if($scopes->isNotEmpty())

    <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
        <span>{{ $label }}</span>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu">
        @foreach($scopes as $scope)
            {!! $scope->render() !!}
        @endforeach
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ $cancel }}">{{ trans('admin.cancel') }}</a></li>
    </ul>

    @endif

</div>