@foreach($options as $option => $label)
    <div class="form-check {{ $inline ? 'form-check-inline' : '' }}">
        <input type="checkbox" class="form-check-input" id="{{$id}}-{{$option}}" name="{{$name}}[]" value="{{$option}}" class="minimal" {{ in_array((string)$option, request($name, is_null($value) ? [] : $value)) ? 'checked' : '' }} />
        <label class="form-check-label" for="{{$id}}-{{$option}}">{{$label}}</label>
    </div>
@endforeach