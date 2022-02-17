<span data-bs-toggle="modal" data-bs-target="#grid-modal-{{ $name }}" data-key="{{ $key }}">
   <a href="javascript:void(0)"><i class="icon-clone"></i>&nbsp;&nbsp;{{ $value }}</a>
</span>

<div class="modal grid-modal fade" id="grid-modal-{{ $name }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title">{{ $title }}</h4>
                <button type="button" class="btn btn-light close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                {!! $html !!}
            </div>
        </div>
    </div>
</div>

@if($grid)
<style>
    .box.grid-box {
        box-shadow: none;
        border-top: none;
    }

    .grid-box .box-header:first-child {
        display: none;
    }
</style>
@endif

@if($async)
<script>
    var modal = document.querySelector('#grid-modal-{{ $name }}');
    var modalBody = modal.querySelector('.modal-body');

    var load = function (url) {

        modalBody.innerHTML = "<div class='loading text-center' style='height:100px;'>\
                <div class='icon-pulse'>\
                    <i class='icon-spinner icon-3x icon-fw'></i>\
                </div>\
            </div>";

        axios.get(url)
        .then(function (response) {
            modalBody.innerHTML = response.data;
        }).catch(function (error) {
            console.log(error);
        });

    };

    modal.addEventListener('show.bs.modal', function (e) {
        var key = e.relatedTarget.dataset.key;
        load('{{ $url }}'+'&key='+key);
    })

</script>
@endif
