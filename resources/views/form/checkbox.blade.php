@include("admin::form._header")

    @foreach($options as $option => $label)

        <div class="form-check @if(!$stacked)form-check-inline @endif">
            <input type="checkbox" class="form-check-input {{$class}}" id="{{$id}}-{{$option}}" name="{{$name}}[]" value="{{$option}}" {{ false !== array_search($option, array_filter(old($column, $value ?? []))) || ($value === null && in_array($option, $checked)) ?'checked':'' }} {!! $attributes !!} />
            <label class="form-check-label" for="{{$id}}-{{$option}}">{{$label}}</label>
        </div>

    @endforeach

@include("admin::form._footer")
