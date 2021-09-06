@include("admin::form._header")

        <input type="file" class="form-control {{$class}}" name="{{$name}}" {!! $attributes !!} />

@include("admin::form._footer")
