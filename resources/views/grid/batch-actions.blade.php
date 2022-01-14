@if(!$holdAll)
    <div class="btn-group {{ $all }}-holder show-on-rows-selected d-none me-1">
    <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="selected hidden-xs" data="{{ trans('admin.grid_items_selected') }}"></span>
      <span class="visually-hidden">Toggle Dropdown</span>
    </button>
    @if(!$actions->isEmpty())
    <ul class="dropdown-menu" role="menu">
        @foreach($actions as $action)
            <li>{!! $action->render() !!}</li>

            @if($action instanceof \OpenAdmin\Admin\Actions\BatchAction)

            @elseif (1==2)
                <li><a href="#" class="{{ $action->getElementClass(false) }} dropdown-item"><i class="{{$action->icon}}"></i>{!! $action->render() !!} </a></li>
            @endif
        @endforeach
    </ul>
    @endif
  </div>
@endif