<div class="card box-info">
    <div class="card-header with-border">
        <h3 class="card-title">{{ $form->title() }}</h3>

        <div class="card-tools">
            {!! $form->renderTools() !!}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! $form->open() !!}

    <div class="card-body p-0">

        @if(!$tabObj->isEmpty())
            @include('admin::form.tab', compact('tabObj'))
        @else
            <div class="row fields-group">

                @if($form->hasRows())
                    @foreach($form->getRows() as $row)
                        {!! $row->render() !!}
                    @endforeach
                @else
                    @foreach($layout->columns() as $column)
                        <div class="col-md-{{ $column->width() }}">
                            @foreach($column->fields() as $field)
                                {!! $field->render() !!}
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        @endif

    </div>
    <!-- /.box-body -->

    {!! $form->renderFooter() !!}

    @foreach($form->getHiddenFields() as $field)
        {!! $field->render() !!}
    @endforeach

<!-- /.box-footer -->
    {!! $form->close() !!}

</div>
