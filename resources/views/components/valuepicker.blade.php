
<template render="true">
    <div class="modal fade picker" id="{{ $modal }}" tabindex="-1" role="dialog">
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
                        <div class="icon-pulse">
                            <i class="icon-spinner icon-3x icon-fw"></i>
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

var pickInput = document.querySelector("{{ $selector }}");
var separator = '{{ $separator }}';
var value;
var refresh = function () {};

@if($multiple)

    var getValue = function () {
        let value = (new String(pickInput.value)).split(separator).filter(function (val) {
            return val != '';
        });
        return value;

    };
    var setValue = function (values,rows) {
        pickInput.value = values.join(separator);
    };

@else

    var getValue = function () {
        value = pickInput.value;
    };
    var setValue = function (values,rows) {
        console.log(values);
        pickInput.value = values[0];
    };

@endif

var config = {
    modal_elm : document.querySelector('#{{ $modal }}'),
    url : "{!! $url !!}",
    update : setValue,
    value : getValue
}

admin.selectable.init(config);

getValue();

/*
$('.picker-file-preview').on('click', 'a.remove', function () {
    var preview = $(this).parents('.file-preview-frame');
    var current = preview.data('val');

    preview.addClass('hide');

    var input = pickInput.val().split(separator);

    var index = input.indexOf(current);value
    if (index !== -1) {
        input.splice(index, 1);
    }

    pickInput.val(input.join(separator));

    updateValue();

    if (input.length === 0) {
        $(this).parents('.picker-file-preview').addClass('hide');
    }
});
*/

@if($is_file)
refresh = function () {

    var values = (typeof value == 'string') ? [value] : value;
    var preview = pickInput.parent().siblings('.picker-file-preview');
    var url_tpl = '{{ $url_tpl }}';

    @if($is_image)
    var template = $('template#image-preview')
    @else
    var template = $('template#file-preview')
    @endif

    preview.empty();

    values.forEach(function (item) {
        var url = url_tpl.replace('__URL__', item);
        preview.append(
            template.html()
                .replace(/_url_/g, url)
                .replace(/_val_/g, item)
                .replace(/_name_/g, url.split('/').pop())
        );
    });

    if (values.length > 0) {
        preview.removeClass('hide');
    }
};
@endif

</script>

<style>
    .picker.modal tr {
        cursor: pointer;
    }

    .picker.modal .box {
        border-top: none;
        margin-bottom: 0;
        box-shadow: none;
    }

    @if($is_file)
    .picker-file-preview {
        overflow: hidden;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 8px;
        width: 100%;
        margin-bottom: 5px;
    }

    .picker-file-preview .file-preview-frame {
        margin: 8px;
        border: 1px solid rgba(0, 0, 0, .2);
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, .2);
        padding: 6px;
        float: left;
        text-align: center;
        width: 213px;
    }

    .picker-file-preview .file-content {
        font-size: 6em;
    }

    .picker-file-preview .file-caption-info {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 160px;
        height: 20px;
        margin: auto;
        font-size: 11px;
        color: #777;
    }

    .picker-file-preview .file-actions {
        text-align: right;
        margin-top: 20px;
    }

    .picker-file-preview img {
        max-width: 160px;
        max-height: 160px;
    }
    @endif
</style>
