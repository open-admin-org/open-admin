<div {!! $attributes !!}>
    <div class="inner">
        <h3>{{ $info }}</h3>

        <p>{{ $name }}</p>
    </div>
    <div class="icon">
        <i class="icon-{{ $icon }}"></i>
    </div>
    <a href="{{ $link }}" class="small-box-footer">
        {{ trans('admin.more') }}&nbsp;
        <i class="icon-arrow-circle-right"></i>
    </a>
</div>