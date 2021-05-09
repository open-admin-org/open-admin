@include("admin::form._header")

        <input type="file" class="{{$class}}" name="{{$name}}[]" {!! $attributes !!} />
        @isset($sortable)
        <input type="hidden" class="{{$class}}_sort" name="{{ $sort_flag."[$name]" }}"/>
        @endisset

@include("admin::form._footer")
