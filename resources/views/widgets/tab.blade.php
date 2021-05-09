<div {!! $attributes !!}>
    <ul class="nav nav-tabs nav-tabs">

        @foreach($tabs as $id => $tab)
            @if($tab['type'] == \OpenAdmin\Admin\Widgets\Tab::TYPE_CONTENT)
                <li {{ $id == $active ? 'class=active' : '' }}><a href="#tab_{{ $tab['id'] }}" data-bs-toggle="tab">{{ $tab['title'] }}</a></li>
            @elseif($tab['type'] == \OpenAdmin\Admin\Widgets\Tab::TYPE_LINK)
                <li {{ $id == $active ? 'class=active' : '' }}><a href="{{ $tab['href'] }}">{{ $tab['title'] }}</a></li>
            @endif
        @endforeach

        @if (!empty($dropDown))
        <li class="dropdown">
            <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#">
                Dropdown <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                @foreach($dropDown as $link)
                <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ $link['href'] }}">{{ $link['name'] }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif
        <li class="pull-right header">{{ $title }}</li>
    </ul>
    <div class="tab-content">
        @foreach($tabs as $id => $tab)
        <div class="tab-pane {{ $id == $active ? 'active' : '' }}" id="tab_{{ $tab['id'] }}">
            @php($content = \Illuminate\Support\Arr::get($tab, 'content'))
                @if($content instanceof \Illuminate\Contracts\Support\Renderable)
                    {!! $content->render() !!}
                @else
                    {!! $content !!}
                @endif
        </div>
        @endforeach

    </div>
</div>