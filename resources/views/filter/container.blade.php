<div class="with-border collapse {{ $expand?'show':'' }} filter-box" id="{{ $filterID }}">
    <form action="{!! $action !!}" class="form pt-0 form-horizontal" pjax-container method="get" autocomplete="off">

        <div class="row mb-0">
            @foreach($layout->columns() as $column)
            <div class="col-md-{{ $column->width() }}">
                <div class="card-body">
                    <div class="fields-group">
                        @foreach($column->filters() as $filter)
                            {!! $filter->render() !!}
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- /.box-body -->

        <div class="card-footer">
            <div class="row">
                <div class="col-md-{{ $layout->columns()->first()->width() }}">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="btn-group pull-left">
                                <button class="btn btn-primary submit btn-sm"><i
                                            class="icon-search"></i>&nbsp;&nbsp;{{ trans('admin.search') }}</button>
                            </div>
                            <div class="btn-group pull-left " style="margin-left: 10px;">
                                <a href="{!! $action !!}" class="btn btn-light btn-sm"><i
                                            class="icon-undo"></i>&nbsp;&nbsp;{{ trans('admin.reset') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>
