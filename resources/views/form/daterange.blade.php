<div class="{{$viewClass['form-group']}} {!! ($errors->has($errorKey['start'].'start') || $errors->has($errorKey['end'].'end')) ? 'has-error' : ''  !!}">

    <label for="{{$id['start']}}" class="{{$viewClass['label']}} form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="row">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="icon-calendar"></i></span>
                    <input type="text" name="{{$name['start']}}" id="{{$id['start']}}" value="{{ old($column['start'], $value['start'] ?? null) }}" class="form-control {{$class['start']}}" autocomplete="off" {!! $attributes !!} />
                </div>
            </div>

            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="icon-calendar"></i></span>
                    <input type="text" name="{{$name['end']}}" id="{{$id['end']}}" value="{{ old($column['end'], $value['end'] ?? null) }}" class="form-control {{$class['end']}}" autocomplete="off" {!! $attributes !!} />
                </div>
            </div>
        </div>

@include("admin::form._footer")
