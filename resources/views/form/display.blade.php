<div class="{{$viewClass['form-group']}}">
    <label class="{{$viewClass['label']}} form-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        <div class="card box-solid box-default no-margin">
            <!-- /.box-header -->
            <div class="card-body">
                {!! $value !!}&nbsp;
            </div><!-- /.box-body -->
        </div>

@include("admin::form._footer")