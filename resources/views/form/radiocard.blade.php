@include("admin::form._header")

        <div class="card-group radio-group-toggle">
            @foreach($options as $option => $label)
                <label class="panel panel-default {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'active':'' }}">
                    <div class="panel-body">
                    <input type="radio" name="{{$name}}" value="{{$option}}" class="hide minimal {{$class}}" {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
                    </div>
                </label>
            @endforeach
        </div>

@include("admin::form._footer")
