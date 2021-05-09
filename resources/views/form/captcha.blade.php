@include("admin::form._header")

        <div class="input-group" style="width: 250px;">

            <input {!! $attributes !!} />

            <span class="input-group-text clearfix" style="padding: 1px;"><img id="{{$column}}-captcha" src="{{ captcha_src() }}" style="height:30px;cursor: pointer;"  title="Click to refresh"/></span>

        </div>

@include("admin::form._footer")