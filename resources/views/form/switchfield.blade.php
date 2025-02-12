@include("admin::form._header")

    <div class="form-check form-switch">
        <input type="hidden" name="{{$name}}" id="{{$id}}" value="{{ old($name, $value) }}" />
        <input class="form-check-input {{$class}}" name="{{$name}}_cb" type="checkbox" id="{{$name}}_cb" {{ !empty(old($name, $value)) ? 'checked' : '' }} {!! $attributes !!} onchange="document.querySelector('#{{$id}}').value = (this.checked ? 'on' : 'off')" />
    </div>

@include("admin::form._footer")
