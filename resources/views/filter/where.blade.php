<div class="form-group row">
    <label class="col-sm-{{ $cols_label }} form-label"> {{ $label }}</label>
    <div class="col-sm-{{ $cols_field }}">
        @include($presenter->view())
    </div>
</div>
