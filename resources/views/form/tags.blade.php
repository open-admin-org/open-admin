@include("admin::form._header")

    <input class="form-control {{$class}}" name="{{$name}}[]" data-placeholder="{{ $placeholder }}" {!! $attributes !!} value="{{implode(",",$value)}}" />

@include("admin::form._footer")
