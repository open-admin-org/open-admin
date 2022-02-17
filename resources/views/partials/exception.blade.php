@if($errors->hasBag('exception') && config('app.debug') == true)
    <?php $error = $errors->getBag('exception');?>
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        <h4>
            <i class="icon icon-exclamation-triangle"></i>
            <i style="border-bottom: 1px dotted #fff;cursor: pointer;" title="{{ $error->first('type') }}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ class_basename($error->first('type')) }}</i>

            In <i title="{{ $error->first('file') }} line {{ $error->first('line') }}" style="border-bottom: 1px dotted #fff;cursor: pointer;" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ basename($error->first('file')) }} line {{ $error->first('line') }}</i> :
        </h4>
        <p><a style="cursor: pointer;" onclick="document.querySelector('#open-admin-exception-trace').classList.toggle('d-none');"><i class="icon-angle-double-down"></i>&nbsp;&nbsp;{!! $error->first('message') !!}</a></p>

        <p class="d-none" id="open-admin-exception-trace"><br>{!! nl2br($error->first('trace')) !!}</p>
    </div>
@endif
