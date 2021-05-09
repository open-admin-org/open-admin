@include("admin::form._header")

        <div class="input-group" style="width: 150px">
            <input type="text" id="{{$id}}" name="{{$name}}" value="{{ old($column, $value) }}" class="form-control {{$class}}" placeholder="0" style="text-align:right;" {!! $attributes !!} />
            <span class="input-group-text">%</span>
        </div>

@include("admin::form._footer")
