@extends('admin::grid.inline-edit.comm')

@section('field')
    @foreach($options as $option => $label)
        <div class="radio icheck">
            <label>
                <input type="radio" name='radio-{{ $name }}' class="minimal ie-input" value="{{ $option }}" data-label="{{ $label }}"/>&nbsp;{{$label}}&nbsp;&nbsp;
            </label>
        </div>
    @endforeach
@endsection

@section('assert')
    <style>
        .icheck.radio {
            margin: 0 0 10px 0px;
        }

        .ie-content-{{ $name }} .ie-container  {
            width: 150px;
            position: relative;
        }
    </style>

    <script>
     admin.grid.inline_edit.functions['{{ $trigger }}'] = {
            content : function(trigger,content){
                let fields = content.querySelectorAll('input');
                fields.forEach(el=>{
                    if (trigger.dataset.value == el.value){
                        el.checked = true;
                    }
                })
            },
            shown : function(trigger,content){
            },
            returnValue : function(trigger,content){
                let field = content.querySelector('input:checked');
                console.log(field);
                return  {'val':field.value,'label':field.dataset.label}
            }
        }

    </script>


@endsection

