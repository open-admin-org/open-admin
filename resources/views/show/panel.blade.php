<div class="card box-{{ $style }}">
    <div class="card-header with-border">
        <h3 class="card-title">{{ $title }}</h3>

        <div class="card-tools">
            {!! $tools !!}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">

        <div class="card-body">

            <div class="fields-group">

                @foreach($fields as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>

        </div>
        <!-- /.box-body -->
    </div>
</div>