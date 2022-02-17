<div class="row ">
    <label class="col-sm-{{$width['label']}} form-label">{{ $label }}</label>
    <div class="col-sm-{{$width['field']}} show-value">
        @if($wrapped)
        <div class="card">
            <!-- /.box-header -->
            <div class="card-body">
                @if($escape)
                    {{ $content }}&nbsp;
                @else
                    {!! $content !!}&nbsp;
                @endif
            </div><!-- /.box-body -->
        </div>
        @else
            @if($escape)
                {{ $content }}
            @else
                {!! $content !!}
            @endif
        @endif
    </div>
</div>