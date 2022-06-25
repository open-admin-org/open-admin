<span class="grid-selector" data-bs-toggle="modal" data-bs-target="#{{ $modal }}" id="{{ $display_field }}-{{$key}}" key="{{ $key }}" data-display_field="{{ $display_field }}" data-val="{{ $original }}">
   <a href="javascript:void(0)" class="text-muted text-dotted">
       <i class="icon-check-square"></i>&nbsp;
       <span class="text">{!! $value !!}</span>
   </a>
</span>

<style>
    .belongsto.modal tr {
        cursor: pointer;
    }

    .belongsto.modal .box {
        border-top: none;
        margin-bottom: 0;
        box-shadow: none;
    }
    .belongsto.modal .loading {
        margin: 50px;
    }
</style>

<template render="true">
    <div class="modal fade belongsto" id="{{ $modal }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 5px;">
                <div class="modal-header">
                    <h4 class="modal-title">{{ admin_trans('admin.choose') }}</h4>
                    <button type="button" class="btn btn-light close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loading text-center">
                        <div class="icon-spin">
                            <i class="icon-spinner icon-3x"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ admin_trans('admin.cancel') }}</button>
                    <button type="button" class="btn btn-primary submit">{{ admin_trans('admin.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    var related;
    var rows;
    var values;
    var labelClass = "{{ $labelClass }}";
    var seperator = "{{ $seperator }}";

    var update = function (callback) {
        var url = "{{ $resource }}/" + related.getAttribute('key');
        @if($relation == \OpenAdmin\Admin\Grid\Displayers\BelongsTo::class)
            var value = values.length ? values[0] : '';
        @else
            var value = values.length ? values : ['']
        @endif
        var data = {
                '{{ $name }}': value,
                _method: 'PUT',
                'after-save': 'exit'
            };
        admin.ajax.post(url,data,callback);
    };

    var updateFunction = function(setValues,setRows,setRelated){

        rows = setRows;
        related = setRelated;
        values = setValues;
        update(resultFunction);
    }

    var resultFunction = function(data){

        admin.toastr.success(data.data);

        var text = related.querySelector(".text");
        var labels = [];
        var sep = "";

        for(i in values){
            var value = values[i];
            if (!text.querySelector('span[data-key="'+value+'"]')){
                var row = rows[value];
                var key = row.dataset.key;
                var value = row.querySelector(".column-"+related.dataset.display_field).innerText;
                var label = sep+"<span data-key=\""+key+"\" class=\""+labelClass+"\">"+value+"</span>";
                text.innerHTML += label;
            }
            sep = seperator;
        }
        text.querySelectorAll("span").forEach(span=>{
            var check = (new String(span.dataset.key));
            if (!arr_includes(values,check)){
                span.remove();
            }
        })

        @if($relation == \OpenAdmin\Admin\Grid\Displayers\BelongsTo::class)
            related.dataset.val = values[0];
        @else
            related.dataset.val = JSON.stringify(values);
        @endif

        text.classList.add("text-success");

        setTimeout(function () {
            var text = related.querySelector(".text");
            text.classList.remove("text-success");

        }, 2000);
    }

    var valueFunction = function(related){
        @if($relation == \OpenAdmin\Admin\Grid\Displayers\BelongsTo::class)
        return related.dataset.val;
        @else
        return JSON.parse(related.dataset.val);
        @endif
    }

    //var modalTrigger = '.{$this->relation_prefix}{$column} .select-relation';
    var config = {
        modal_elm : document.querySelector('#{{$modal}}'),
        url : "{!! $url !!}",
        update : updateFunction, //for setting value
        value : valueFunction
    }

    admin.selectable.init(config);

</script>
