@include("admin::form._header")

@if ($show_inputs)
    <div class="input-group mb-3">
        <span class="input-group-text"><i class="icon-map-marker-alt"></i> Lat</span>
        <input class="form-control" id="{{$name['lat']}}" name="{{$name['lat']}}" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />
        <span class="input-group-text"><i class="icon-map-marker-alt"></i> Lng</span>
        <input class="form-control" id="{{$name['lng']}}" name="{{$name['lng']}}" value="{{ old($column['lng'], $value['lng']) }}" {!! $attributes !!} />
    </div>
@endif

<div class="form-control" id="map_{{$name['lat'].$name['lng']}}" style="width: 100%;height: 300px;position:relative;"></div>

@if (!$show_inputs)
    <input type="hidden" id="{{$name['lat']}}" name="{{$name['lat']}}" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />
    <input type="hidden" id="{{$name['lng']}}" name="{{$name['lng']}}" value="{{ old($column['lng'], $value['lng']) }}" {!! $attributes !!} />
@endif

@include("admin::form._footer")
