@include("admin::form._header")

    <input type="range" class="{{$class}} form-range" name="{{$name}}" data-from="{{ old($name, $value) }}" {!! $attributes !!} />

@include("admin::form._footer")
