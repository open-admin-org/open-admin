@include("admin::form._header")

    <div class="input-group">
        <button type="button" id="{{$id}}-button-min" class="input-group-text btn btn-light minus with-icon"><i class="icon-minus"></i></button>
        <input {!! $attributes !!} />
        <button type="button" id="{{$id}}-button-plus" class="input-group-text btn btn-light plus with-icon"><i class="icon-plus"></i></button>
    </div>

@include("admin::form._footer")