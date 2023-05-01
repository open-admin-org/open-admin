@include('admin::form._header')

    <select class="form-select {{ $class }} d-none" style="width: 100%;" name="{{ $name }}[]" multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!}>
        @foreach ($options as $select => $option)
            <option value="{{ $select }}" {{ in_array($select, (array) old($column, $value)) ? 'selected' : '' }}>
                {{ $option }}</option>
        @endforeach
    </select>

    <div class="{{$relation_prefix}}{{ $class }} form-grid form-grid-rows">
        @if ($grid_message)
            <div class="alert alert-warning">{{$grid_message}}</div>
        @else
        <div class="grid-holder" data-refresh_url="{{$refresh_url}}">
            {!! $grid->render() !!}
        </div>
        <template class="empty">
            @include('admin::grid.empty-grid')
        </template>
        @endif
    </div>

@include('admin::form._footer')
