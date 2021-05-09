@include("admin::form._header")

    <select class="form-select {{$class}}" style="width: 100%;" name="{{$name}}[]" multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!} >

        @foreach($options as $key => $option)
            <option value="{{ $keyAsValue ? $key : $option}}" {{ in_array($option, $value) ? 'selected' : '' }}>{{$option}}</option>
        @endforeach

    </select>
    <input type="hidden" name="{{$name}}[]" />

@include("admin::form._footer")
