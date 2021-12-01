@extends('admin::grid.inline-edit.comm')

@section('field')
    <input class="form-control ie-input"/>
@endsection

@section('assert')
    <style>
        .ie-content-{{ $name }} .ie-input {
            display: none;
        }
        .ie-content-{{ $name }} {
            width:310px;
        }
    </style>
    <script>
        admin.grid.inline_edit.functions['{{ $trigger }}'] = {
            content : function(trigger,content){
                content.querySelector('input').value = trigger.dataset.value;
            },
            shown : function(trigger,content){
                let field = content.querySelector('input');
                flatpickr(field,{!!$options!!});
            },
        }
    </script>
@endsection