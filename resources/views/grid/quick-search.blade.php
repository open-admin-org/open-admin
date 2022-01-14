<form action="{!! $action !!}" pjax-container style="display: inline-flex;vertical-align:middle;">
    <div class="input-group input-group-sm">
        <input type="text" name="{{ $key }}" class="form-control grid-quick-search" value="{{ $value }}" placeholder="{{ $placeholder }}">
        <button type="submit" class="btn btn-light"><i class="icon-search"></i></button>
    </div>
</form>