@include("admin::form._header")

    <div class="form-check form-switch">
        <input class="form-check-input {{$class}}" name="{{$name}}" type="checkbox" id="{{$name}}" {{ !empty(old($column, $value)) ? 'checked' : '' }} {!! $attributes !!} />
    </div>

@include("admin::form._footer")
