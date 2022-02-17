@include("admin::form._header")

        <div class="input-group">

            @if ($prepend)
            <span class="input-group-text with-icon">{!! $prepend !!}</span>
            @endif

            <input {!! $attributes !!} />

            @if ($append)
                <span class="input-group-text clearfix">{!! $append !!}</span>
            @endif

            @isset($btn)
                <span class="input-group-btn">
                  {!! $btn !!}
                </span>
            @endisset

        </div>

@include("admin::form._footer")
