<div {!! $attributes !!}>
    <div class="card-body d-flex align-items-center">
        <div class="icon float-start">
            <i class="icon-{{ $icon }}"></i>
        </div>
        <div class="inner">
            <h3>{{ $info }}</h3>
            <p>{{ $name }}</p>
        </div>

    </div>
    <a href="{{ $link }}" class="card-footer text-{{$color}}">
        {{ $link_text }}
        <i class="icon-arrow-circle-right"></i>
    </a>
</div>