@include("admin::form._header")

        <div class="btn-group radio-group-toggle">
            @foreach($options as $option => $label)
                <label class="btn btn-light {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'active':'' }}">
                    <input type="radio" name="{{$name}}" value="{{$option}}" class="hide minimal {{$class}}" {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
                </label>
            @endforeach
        </div>

@include("admin::form._footer")
