
<div class="card p-0">

    @if(isset($title))
        <div class="card-header">
            <h3 class="card-title"> {{ $title }}</h3>
        </div>
    @endif

	<div class="container-fluid card-header no-border">
        @if ( $grid->showTools() || $grid->showExportBtn() || $grid->showCreateBtn() )
        <div class="row">
            <div class="col-auto  me-auto">
                {!! $grid->renderCreateButton() !!}
                @if ( $grid->showTools() )
                {!! $grid->renderHeaderTools() !!}
                @endif
            </div>
            <div class="col-auto">
                {!! $grid->renderColumnSelector() !!}
                {!! $grid->renderExportButton() !!}
            </div>
        </div>
        @endif
    </div>
    {!! $grid->renderFilter() !!}
    {!! $grid->renderHeader() !!}

        <form class="table-responsive" autocomplete="off">
            <table class="table table-sm table-hover select-table" id="{{ $grid->tableID }}">

                <thead>
                    <tr>
                        @foreach($grid->visibleColumns() as $column)
                        <th {!! $column->formatHtmlAttributes() !!}>{!! $column->getLabel() !!}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                </thead>

                @if ($grid->hasQuickCreate())
                    {!! $grid->renderQuickCreate() !!}
                @endif

                <tbody>

                    @if($grid->rows()->isEmpty() && $grid->showDefineEmptyPage())
                        @include('admin::grid.empty-grid')
                    @endif

                    @foreach($grid->rows() as $row)
                    <tr {!! $row->getRowAttributes() !!}>
                        @foreach($grid->visibleColumnNames() as $name)
                        <td {!! $row->getColumnAttributes($name) !!}>
                            {!! $row->column($name) !!}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>

                {!! $grid->renderTotalRow() !!}

            </table>

        </div>

        {!! $grid->renderFooter() !!}

        {!! $grid->paginator() !!}

    </div>
        <!-- /.box-body -->
</div>
