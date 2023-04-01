<div {!! $attributes !!}>
    <ul class="nav nav-tabs">

        @foreach ($tabs as $i => $tab)
            @if ($tab['type'] == \OpenAdmin\Admin\Widgets\Tab::TYPE_CONTENT)
                <li class="nav-item"><a class="nav-link {{ $active === $i ? 'active' : '' }}" href="#{{ $tab['ref'] }}"
                        data-bs-toggle="tab">{{ $tab['title'] }}</a></li>
            @elseif($tab['type'] == \OpenAdmin\Admin\Widgets\Tab::TYPE_LINK)
                <li class="nav-item"><a class="nav-link {{ $active === $i ? 'active' : '' }}"
                        href="{{ $tab['href'] }}">{{ $tab['title'] }}</a></li>
            @endif
        @endforeach

        @if (!empty($dropDown))
            <li class="dropdown">
                <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#">
                    Dropdown <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach ($dropDown as $link)
                        <li role="presentation"><a role="menuitem" tabindex="-1"
                                href="{{ $link['href'] }}">{{ $link['name'] }}</a></li>
                    @endforeach
                </ul>
            </li>
        @endif
        <li class="pull-right header">{{ $title }}</li>
    </ul>
    <div class="tab-content">
        @foreach ($tabs as $i => $tab)
            <div class="card-body tab-pane {{ $active === $i ? 'active' : '' }}" id="{{ $tab['ref'] }}">
                @php($content = \Illuminate\Support\Arr::get($tab, 'content'))
                @if ($content instanceof \Illuminate\Contracts\Support\Renderable)
                    {!! $content->render() !!}
                @else
                    {!! $content !!}
                @endif
            </div>
        @endforeach

    </div>
</div>
