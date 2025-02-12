@include("admin::form._header")

        <textarea class="form-control {{$class}}" id="{{$id}}" name="{{$name}}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($name, $value) }}</textarea>

@include("admin::form._footer")
