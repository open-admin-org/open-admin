@include("admin::form._header")

<div class="form-control" id="map_{{$name['lat'].$name['lng']}}" style="width: 100%;height: 300px"></div>
<input type="hidden" id="{{$name['lat']}}" name="{{$name['lat']}}" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />
<input type="hidden" id="{{$name['lng']}}" name="{{$name['lng']}}" value="{{ old($column['lng'], $value['lng']) }}" {!! $attributes !!} />

@include("admin::form._footer")
