<div {!! $attributes !!}>
    <div class="modal-dialog  modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="btn btn-light close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                @if ($body)
                    {!! $body !!}
                @endif
                @if ($show_loader)
                    <div class="loading text-center">
                        <div class="icon-spin">
                            <i class="icon-spinner icon-spin icon-3x icon-fw"></i>
                        </div>
                    </div>
                @endif
            </div>
            @if (!empty($footer))
                <div class="modal-footer">
                    {!! $footer !!}
                </div>
            @endif
        </div>
    </div>
</div>
