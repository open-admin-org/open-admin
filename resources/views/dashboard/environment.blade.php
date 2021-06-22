<div class="card box-default">
    <div class="card-header with-border">
        <h3 class="card-title">Environment</h3>

        <div class="card-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-bs-toggle="collapse" href="#environment" role="button" aria-expanded="true" aria-controls="environment">
                <i class="icon-minus"></i>
            </button>
        </div>
    </div>

    <!-- /.box-header -->
    <div class="card-body collapse show" id="environment">
        <div class="table-responsive">
            <table class="table table-striped">

                @foreach($envs as $env)
                <tr>
                    <td width="120px">{{ $env['name'] }}</td>
                    <td>{{ $env['value'] }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>