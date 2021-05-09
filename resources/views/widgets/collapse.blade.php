<div {!! $attributes !!}>
    @foreach($items as $key => $item)
    <div class="panel box box-primary" style="margin-bottom: 0px">
        <div class="card-header with-border">
            <h4 class="card-title">
                <a data-bs-toggle="collapse" data-parent="#{{$id}}" href="#collapse{{ $key }}">
                    {{ $item['title'] }}
                </a>
            </h4>
        </div>
        <div id="collapse{{ $key }}" class="panel-collapse collapse {{ $key == 0 ? 'in' : '' }}">
            <div class="card-body">
                {!! $item['content'] !!}
            </div>
        </div>
    </div>
    @endforeach

</div>
