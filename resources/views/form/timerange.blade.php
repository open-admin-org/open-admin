@include("admin::form._header")

        <div class="row">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="icon-clock"></i></span>
                    <input type="text" name="{{$name['start']}}" value="{{ old($column['start'], $value['start'] ?? null) }}" class="form-control {{$class['start']}}" autocomplete="off" {!! $attributes !!} />
                </div>
            </div>

            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="icon-clock"></i></span>
                    <input type="text" name="{{$name['end']}}" value="{{ old($column['end'], $value['end'] ?? null) }}" class="form-control {{$class['end']}}" autocomplete="off" {!! $attributes !!} />
                </div>
            </div>
        </div>
@include("admin::form._footer")
