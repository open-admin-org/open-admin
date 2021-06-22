<div class="card box-default">
    <div class="card-header with-border">
        <h3 class="card-title">Dependencies</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-box-tool" data-bs-toggle="collapse" href="#dependencies" role="button" aria-expanded="true" aria-controls="dependencies">
                <i class="icon-minus"></i>
            </button>
        </div>
    </div>

    <!-- /.box-header -->
    <div class="card-body dependencies collapse show" id="dependencies">
        <div class="table-responsive">
            <table class="table table-striped">
                @foreach($dependencies as $dependency => $version)
                <tr>
                    <td width="240px">{{ $dependency }}</td>
                    <td><span class="badge bg-primary">{{ $version }}</span></td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>
