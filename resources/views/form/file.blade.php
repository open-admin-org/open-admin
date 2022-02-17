@include("admin::form._header")

       <div class="input-group">
              <input type="file" class="form-control {{$class}}" name="{{$name}}" {!! $attributes !!} />
              <span class="input-group-btn">
              @isset($btn){!! $btn !!}@endisset
            </span>
        </div>

@include("admin::form._footer")
