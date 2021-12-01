@extends('admin::grid.inline-edit.comm')
@php
    $type = "checkbox";
@endphp
@section('field')

    @foreach($options as $option => $label)
        <div class="checkbox">
            <label>
                <input type="checkbox" name='radio-{{ $name }}[]' class="minimal ie-input" value="{{ $option }}" data-label="{{ $label }}"/> {{$label}}
            </label>
        </div>
    @endforeach
@endsection

@section('assert')
    <style>
        .icheck.checkbox {
            margin: 5px 0 5px 20px;
        }

        .ie-content-{{ $name }} .ie-container  {
            width: 150px;
            position: relative;
        }
    </style>
    <script>
        admin.grid.inline_edit.functions['{{ $trigger }}'] = {
            content : function(trigger,content){

                try{
                    let valArr = JSON.parse(trigger.dataset.value);
                }
                catch(err){}
                if (typeof(valArr) != 'Array'){
                    valArr = [];
                }
                let fields = content.querySelectorAll('input');
                fields.forEach(el=>{
                    if (valArr.includes(el.value)){
                        el.checked = true;
                    }
                })
            },
            shown : function(trigger,content){
            },
            returnValue : function(trigger,content){
                let fields = content.querySelectorAll('input:checked');
                let obj = {'val':[],'label':[]}
                fields.forEach(el=>{
                    obj.val.push(el.value);
                    obj.label.push(el.dataset.label);
                })
                return obj;
            }
        }
    </script>

@endsection

