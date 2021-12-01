@extends('admin::grid.inline-edit.comm')

@section('field')
    <select name='select-{{ $name }}' class="form-select ie-input">
    @foreach($options as $opt_value => $opt_label)
        <option name='select-{{ $name }}' value="{{ $opt_value }}" data-label="{{ $opt_label }}">{{$opt_label}}</option>
    @endforeach
    </select>
@endsection

@section('assert')
    <script>
        admin.grid.inline_edit.functions['{{ $trigger }}'] = {
            content : function(trigger,content){
                content.querySelector('select').value = trigger.dataset.value;
            },
            shown : function(trigger,content){
            },
            returnValue : function(trigger,content){
                var field = content.querySelector('select');
                return {'val':field.value,'label':field.options[field.selectedIndex].text};
            }
        }
    </script>

@endsection

