@include("admin::form._header")

        <div class="btn-group checkbox-group-toggle">
        @foreach($options as $option => $label)
            <label class="btn btn-light {{ false !== array_search($option, array_filter(old($column, $value ?? []))) || ($value === null && in_array($option, $checked)) ?'active':'' }}">
                <input type="checkbox" name="{{$name}}[]" value="{{$option}}" class="hide {{$class}}" {{ false !== array_search($option, array_filter(old($column, $value ?? []))) || ($value === null && in_array($option, $checked)) ?'checked':'' }} {!! $attributes !!} />&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        @endforeach
        </div>

        <input type="hidden" name="{{$name}}[]">

@include("admin::form._footer")
