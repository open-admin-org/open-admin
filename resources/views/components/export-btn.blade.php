<div class="btn-group me-1">
    <a href="{{$grid->getExportUrl('all')}}" target="_blank" class="btn btn-sm btn-primary" title="{{trans('admin.export')}}"><i class="icon-download"></i><span class="hidden-xs"> {{trans('admin.export')}}</span></a>
    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{$grid->getExportUrl('all')}}" target="_blank">{{trans('admin.all')}}</a></li>
        <li><a class="dropdown-item" href="{{$grid->getExportUrl('page', $page)}}" target="_blank">{{trans('admin.current_page')}}</a></li>
        <li><a class="dropdown-item" href="{{$grid->getExportUrl('selected', '__rows__')}}" target="_blank" onclick="admin.grid.export_selected_row(event);" data-no_rows_selected="{{__('admin.no_rows_selected')}}">{{trans('admin.selected_rows')}}</a></li>
    </ul>
</div>
