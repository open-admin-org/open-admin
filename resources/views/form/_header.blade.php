@if(!empty($inline))
<div class="col-auto">
@else
@if (!empty($showAsSection))
    <div class="row has-many-head">
        <h4>{{ $label }}</h4>
    </div>
    <hr class="form-border">
@endif

<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$id}}" class="{{$viewClass['label']}} form-label">@if (empty($showAsSection)){{$label}}@endif</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
@endif
