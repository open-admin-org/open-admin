@include("admin::form._header")

        <input type="text" class="{{$class}}" name="{{$name}}" data-from="{{ old($column, $value) }}" {!! $attributes !!} />

@include("admin::form._footer")
