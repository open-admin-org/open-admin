<div class="form-group row">
    <label class="col-sm-{{ $cols_label }} form-label">{{ $label }}</label>
    <div class="col-sm-{{ $cols_field }}" style="width: 390px">
        <div class="input-group">
            <div class="input-group-text">
                <i class="icon-calendar"></i>
            </div>
            <input type="text" class="form-control" id="{{ $id['start'] }}" placeholder="{{ $label }}"
                name="{{ $name['start'] }}"
                value="{{ request()->input("{$column}.start", \Illuminate\Support\Arr::get($value, 'start')) }}"
                autocomplete="off" />

            <span class="input-group-text" style="border-left: 0; border-right: 0;">-</span>

            <input type="text" class="form-control" id="{{ $id['end'] }}" placeholder="{{ $label }}"
                name="{{ $name['end'] }}"
                value="{{ request()->input("{$column}.end", \Illuminate\Support\Arr::get($value, 'end')) }}"
                autocomplete="off" />
        </div>
    </div>
</div>
