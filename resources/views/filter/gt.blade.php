<div class="form-group row">
    <label class="col-sm-{{ $cols_label }} form-label">{{ $label }}&nbsp;(&gt;)</label>
    <div class="col-sm-{{ $cols_field }}">
        @include($presenter->view())
    </div>
</div>
