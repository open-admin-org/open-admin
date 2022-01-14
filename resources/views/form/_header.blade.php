@if(!empty($inline))
<div class="col-auto">
@else
<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$id}}" class="{{$viewClass['label']}} form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
@endif
