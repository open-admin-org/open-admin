<div {!! $attributes !!}>
    @if($title || $tools)
        <div class="card-header with-border">
            <h3 class="card-title">{{ $title }}</h3>
            <div class="card-tools pull-right">
                @foreach($tools as $tool)
                    {!! $tool !!}
                @endforeach
            </div><!-- /.box-tools -->
        </div><!-- /.box-header -->
    @endif
    <div id="{{$id}}-body" class="card-body collapse show">
        {!! $content !!}
    </div><!-- /.box-body -->
    @if($footer)
        <div class="card-footer">
            <div class="row">
            {!! $footer !!}
            </div>
        </div><!-- /.box-footer-->
    @endif
</div>
<script>
    {!! $script !!}
</script>