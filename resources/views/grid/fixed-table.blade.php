@include("admin::grid.table-header")

    <!-- /.box-header -->
    <div class="card-body table-responsive no-padding">
        <div class="tables-container">
            <div class="table-wrap table-main">
                <table class="table grid-table select-table" id="{{ $grid->tableID }}">
                    <thead>
                        <tr>
                            @foreach($grid->visibleColumns() as $column)
                            <th {!! $column->formatHtmlAttributes() !!}>{{$column->getLabel()}}{!! $column->renderHeader() !!}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($grid->rows() as $row)
                        <tr {!! $row->getRowAttributes() !!}>
                            @foreach($grid->visibleColumnNames() as $name)
                            <td {!! $row->getColumnAttributes($name) !!} class="column-{!! $name !!}">
                                {!! $row->column($name) !!}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>

                    {!! $grid->renderTotalRow() !!}

                </table>
            </div>

            @if($grid->leftVisibleColumns()->isNotEmpty())
            <div class="table-wrap table-fixed table-fixed-left">
                <table class="table grid-table select-table">
                    <thead>
                    <tr>
                        @foreach($grid->leftVisibleColumns() as $column)
                            <th {!! $column->formatHtmlAttributes() !!}>{{$column->getLabel()}}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($grid->rows() as $row)
                        <tr {!! $row->getRowAttributes() !!}>
                            @foreach($grid->leftVisibleColumns() as $column)
                                @php
                                    $name = $column->getName()
                                @endphp
                                <td {!! $row->getColumnAttributes($name) !!} class="column-{!! $name !!}">
                                    {!! $row->column($name) !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>

                    {!! $grid->renderTotalRow($grid->leftVisibleColumns()) !!}

                </table>
            </div>
            @endif

            @if($grid->rightVisibleColumns()->isNotEmpty())
            <div class="table-wrap table-fixed table-fixed-right">
                <table class="table grid-table select-table">
                    <thead>
                    <tr>
                        @foreach($grid->rightVisibleColumns() as $column)
                            <th {!! $column->formatHtmlAttributes() !!}>{{$column->getLabel()}}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($grid->rows() as $row)
                        <tr {!! $row->getRowAttributes() !!}>
                            @foreach($grid->rightVisibleColumns() as $column)
                                @php
                                $name = $column->getName()
                                @endphp
                                <td {!! $row->getColumnAttributes($name) !!} class="column-{!! $name !!}">
                                    {!! $row->column($name) !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>

                    {!! $grid->renderTotalRow($grid->rightVisibleColumns()) !!}

                </table>
            </div>
            @endif
        </div>
    </div>

    {!! $grid->renderFooter() !!}

    <div class="card-footer clearfix">
        {!! $grid->paginator() !!}
    </div>
    <!-- /.box-body -->
</div>

<script>
    //var theadHeight = getOuterHeigt(document.querySelector('.table-main thead tr'));
    var tableMain = document.querySelector('.table-main');
    var theadHeight = tableMain.querySelector('thead tr').clientHeight;
    document.querySelectorAll('.table-fixed thead tr').forEach(tr=>{
        tr.style.height = theadHeight+"px";
    })

    let tfoot = tableMain.querySelector('tfoot tr');
    if (tfoot){
        var tfootHeight = tfoot.clientHeight;
        document.querySelectorAll('.table-fixed tfoot tr').forEach(tr=>{
            tr.style.height = tfootHeight+"px";
        })
    }


    let left_trs = document.querySelectorAll('.table-fixed-left tbody tr');
    let right_trs = document.querySelectorAll('.table-fixed-right tbody tr');
    tableMain.querySelectorAll('tbody tr').forEach((tr,i)=>{
        var height = tr.clientHeight;
        left_trs[i].style.height = height+"px";
        right_trs[i].style.height = height+"px";
    });

    if (tableMain.clientWidth >= tableMain.scrollWidth) {
        hide(document.querySelectorAll('.table-fixed'));
    }

</script>

<style>
    .tables-container {
        position:relative;
    }

    .tables-container table {
        margin-bottom: 0px !important;
    }

    .tables-container table th, .tables-container table td {
        white-space:nowrap;
    }

    .table-wrap table tr .active {
        background: #f5f5f5;
    }

    .table-main {
        overflow-x: auto;
        width: 100%;
    }

    .table-fixed {
        position:absolute;
        top: 0px;
        background:#ffffff;
        z-index:10;
    }

    .table-fixed-left {
        left:0;
        box-shadow: 7px 0 5px -5px rgba(0,0,0,.12);
    }

    .table-fixed-right {
        right:0;
        box-shadow: -5px 0 5px -5px rgba(0,0,0,.12);
    }
</style>