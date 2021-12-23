<div class="modal" tabindex="-1" role="dialog" id="{{ $modal_id }}">
    <div class="modal-dialog {{ $modal_size }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-light close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <form class="form form-horizontal" method="{{$method}}" action="{{$url}}" autocomplete="off" @if(!empty($multipart))enctype="multipart/form-data"@endif>
                <input type="hidden" name="_action" value="{{$_action}}">
                <input type="hidden" name="_model" value="{{$_model}}">
                <input type="hidden" name="_key" value="{{$_key}}">
                <div class="modal-body">
                    {!! $field_html !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('admin.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('admin.submit') }}</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->