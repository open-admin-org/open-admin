
<div class="card p-0">
    @if(isset($title))
        <div class="card-header">
            <h3 class="card-title"> {{ $title }}</h3>
        </div>
    @endif

	<div class="container-fluid card-header no-border">
        @if ( $grid->showTools() || $grid->showExportBtn() || $grid->showCreateBtn() )
        <div class="row">
            <div class="col-auto me-auto">
                {!! $grid->renderCreateButton() !!}
                @if ( $grid->showTools() )
                {!! $grid->renderHeaderTools() !!}
                @endif
            </div>
            <div class="col-auto">
                {!! $grid->renderExportButton() !!}
                {!! $grid->renderColumnSelector() !!}
            </div>
        </div>
        @endif
    </div>
    {!! $grid->renderFilter() !!}
    {!! $grid->renderHeader() !!}