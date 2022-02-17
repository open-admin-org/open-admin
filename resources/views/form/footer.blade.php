
<footer class="navbar form-footer navbar-light bg-white py-3 px-4 @if (!empty($fixedFooter))shadow fixed-bottom @endif">
    <div class="row">
    {{ csrf_field() }}

    <div class="col-md-{{$width['label']}}">
    </div>

    <div class="col-md-{{$width['field']}} d-flex align-items-center ">
        @if(in_array('reset', $buttons))
        <div class="flex-grow-1 ">
            <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
        </div>
        @endif

        @if(in_array('submit', $buttons))

        <div class="btn-group">
        @foreach($submit_redirects as $value => $redirect)
            @if(in_array($redirect, $checkboxes))
            <div class="form-check form-check-inline">
                <input type="checkbox" class="form-check-input after-submit" id="after-save-{{$redirect}}" name="after-save" value="{{ $value }}" {{ ($default_check == $redirect) ? 'checked' : '' }}>
                <label class="form-check-label" for="after-save-{{$redirect}}">{{ trans("admin.{$redirect}") }}</label>
            </div>
            @endif
        @endforeach
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
        </div>

        @endif


    </div>
</div>
</footer>