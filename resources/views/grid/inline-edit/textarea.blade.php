@extends('admin::grid.inline-edit.comm')
@php
    $type = "textarea";
@endphp

@section('field')
    <textarea class="form-control ie-input" rows="{{ $rows }}">{{$value}}</textarea>
@endsection

@section('assert')
    <script>
       admin.grid.inline_edit.functions['{{ $trigger }}'] = {
            content : function(trigger,content){
                //content.querySelector('select').value = trigger.dataset.value;
            },
            shown : function(trigger,content){
            },
            returnValue : function(trigger,content){
            }
        }
    </script>

    {{--after submit--}}
    <script>
    @component('admin::grid.inline-edit.partials.submit', compact('resource', 'name'))
        $popover.data('display').html(val);
    @endcomponent
    </script>
@endsection


