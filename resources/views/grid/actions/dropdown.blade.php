<div class="grid-dropdown-actions dropdown">
    <a href="#" style="padding: 0 10px;" class="dropdown-toggle grid-actions-dropdown" data-bs-toggle="dropdown">
        <i class="icon-ellipsis-v"></i>
    </a>
    <ul class="dropdown-menu grid-actions-menu" style="z-index:100;">

        @foreach($default as $action)
            <li>{!! $action->render() !!}</li>
        @endforeach

        @if(!empty($custom))

            @if(!empty($default))
            <li class=""><hr class="dropdown-divider"></li>
            @endif

            @foreach($custom as $action)
                <li>{!! $action->render() !!}</li>
            @endforeach
        @endif
    </ul>
</div>

@yield('child')
