<div {!! $attributes !!}>
    @foreach($items as $key => $item)
    <div class="card" style="margin-bottom: 0px">
        <a class="card-header with-border" data-bs-toggle="collapse" data-parent="#{{$id}}" href="#collapse{{ $key }}">
            <h4 class="card-title">
                {{ $item['title'] }}
            </h4>
        </a>
        <div id="collapse{{ $key }}" class="panel-collapse collapse {{ $key == 0 ? 'in' : '' }}">
            <div class="card-body">
                {!! $item['content'] !!}
            </div>
        </div>
    </div>
    @endforeach

</div>
