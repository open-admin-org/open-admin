<span data-bs-toggle="modal" data-bs-target="#grid-modal-{{ $name }}" data-key="{{ $key }}">
   <a href="javascript:void(0)"><i class="icon-clone"></i>&nbsp;&nbsp;{{ $value }}</a>
</span>

<div class="modal grid-modal fade" id="grid-modal-{{ $name }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <button type="button" class="btn close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ $title }}</h4>
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
    var modal = document.querSelector('#grid-modal-{{ $name }}');
    var modalBody = modal.querSelector('.modal-body');

    var load = function (url) {

        modalBody.innerHTML = "<div class='loading text-center' style='height:200px;'>\
                <i class='icon-spinner fa-pulse fa-3x fa-fw' style='margin-top: 80px;'></i>\
            </div>";

        axios.get(url)
        .then(function (response) {
            modalBody.innerHTML = response.data;
        }).catch(function (error) {
            console.log(error);
        });

    };

    modal.on('show.bs.modal', function (e) {
        var key = e.relatedTarget.dataset.key;
        load('{{ $url }}'+'&key='+key);
    })
    console.log('needs work');
    /*.on('click', '.page-item a, .filter-box a', function (e) {
        load($(this).attr('href'));
        e.preventDefault();
    }).on('submit', '.box-header form', function (e) {
        load($(this).attr('action')+'&'+$(this).serialize());
        return false;
    });*/
</script>
@endif
