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
    <div class="card-body" style="display: block;">
        {!! $content !!}
    </div><!-- /.box-body -->
    @if($footer)
        <div class="card-footer">
            {!! $footer !!}
        </div><!-- /.box-footer-->
    @endif
</div>
<script>
    {!! $script !!}
</script>