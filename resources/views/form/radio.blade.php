@include("admin::form._header")

        @foreach($options as $option => $label)

            <div class="form-check @if(!$stacked)form-check-inline @endif">
                <input class="form-check-input {{$class}}" type="radio" id="{{$name}}-{{$option}}" name="{{$name}}" value="{{$option}}" {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />
                <label class="form-check-label" for="{{$name}}-{{$option}}">{{$label}}</label>
            </div>

        @endforeach

@include("admin::form._footer")