<!-- breadcrumb start -->
<nav aria-label="breadcrumb" class="breadcrumb-nav">
@if ($breadcrumb)
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ admin_url('/') }}"><i class="icon-home"></i> {{__('Home')}}</a></li>
    @foreach($breadcrumb as $item)
        @if($loop->last)
        <li class="breadcrumb-item active">
                @if (\Illuminate\Support\Arr::has($item, 'icon'))
                    <i class="icon-{{ $item['icon'] }}"></i>
                @endif
                {{ $item['text'] }}
            </li>
        @else
        <li class="breadcrumb-item">
            @if (\Illuminate\Support\Arr::has($item, 'url'))
                <a href="{{ admin_url(\Illuminate\Support\Arr::get($item, 'url')) }}">
                    @if (\Illuminate\Support\Arr::has($item, 'icon'))
                        <i class="icon-{{ $item['icon'] }}"></i>
                    @endif
                    {{ $item['text'] }}
                </a>
            @else
                @if (\Illuminate\Support\Arr::has($item, 'icon'))
                    <i class="icon-{{ $item['icon'] }}"></i>
                @endif
                {{ $item['text'] }}
            @endif
        </li>
        @endif
    @endforeach
</ol>
@elseif(config('admin.enable_default_breadcrumb'))
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ admin_url('/') }}"><i class="icon-home"></i>Home</a></li>
    @for($i = 2; $i <= count(Request::segments()); $i++)
    <li class="breadcrumb-item">
            <a href="{{ admin_url(implode('/',array_slice(Request::segments(),1,$i-1))) }}">
                {{ucfirst(Request::segment($i))}}
            </a>
        </li>
    @endfor
</ol>
@endif
</nav>