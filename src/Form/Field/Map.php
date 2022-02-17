<?php

namespace OpenAdmin\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;

class Map extends Field
{
    public $default = [
        'lat' => 52.378900135815,
        'lng' => 4.9005728960037,
    ];

    /**
     * Column name.
     *
     * @var array
     */
    protected $column = [];

    /**
     * Get assets required by this field.
     *
     * @return array
     */
    public static function getAssets()
    {
        switch (config('admin.map_provider')) {
            case 'tencent':
                $css = '';
                $js = '//map.qq.com/api/js?v=2.exp&key='.env('TENCENT_MAP_API_KEY');
                break;
            case 'google':
                $css = '';
                $js = '//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key='.env('GOOGLE_API_KEY');
                break;
            case 'yandex':
                $css = '';
                $js = '//api-maps.yandex.ru/2.1/?lang=ru_RU';
                break;
            case 'openstreetmaps':
            default:
                $css = ['/vendor/open-admin/leaflet/leaflet.css', '/vendor/open-admin/leaflet/leaflet-geosearch.css'];
                $js = ['/vendor/open-admin/leaflet/leaflet.js', '/vendor/open-admin/leaflet/leaflet-geosearch.js'];
        }

        return compact('js', 'css');
    }

    public function __construct($column, $arguments)
    {
        $this->column['lat'] = (string) $column;
        $this->column['lng'] = (string) $arguments[0];

        array_shift($arguments);

        $this->label = $this->formatLabel($arguments);

        $this->name = $this->formatId($this->column);
        $this->id = $this->formatId($column);

        /*
         * Google map is blocked in mainland China
         * people in China can use Tencent map instead(;
         */
        switch (config('admin.map_provider')) {
            case 'tencent':
                $this->useTencentMap();
                break;
            case 'google':
                $this->useGoogleMap();
                break;
            case 'yandex':
                $this->useYandexMap();
                break;
            case 'openstreetmaps':
            default:
                $this->useOpenstreetmap();
        }
    }

    public function useOpenstreetmap()
    {
        $this->script = <<<JS
        (function() {
            function initOpenstreetMap(name) {

                var lat = document.querySelector('#{$this->name['lat']}');
                var lng = document.querySelector('#{$this->name['lng']}');

                console.log(lat.value);
                console.log(lng.value);

                var map = L.map(name).setView([lat.value, lng.value], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var marker = L.marker([lat.value, lng.value], {draggable:'true'}).addTo(map)
                marker.on('dragend', function(event){
                    var marker = event.target;
                    var position = marker.getLatLng();
                    lat.value = position.lat;
                    lng.value = position.lng;
                });

                const provider = new window.GeoSearch.OpenStreetMapProvider();
                const search = new GeoSearch.GeoSearchControl({
                    provider: provider,
                    style: 'bar',
                    showMarker: true,
                    updateMap: true,
                    autoClose: true
                });
                map.on('geosearch/showlocation', function(e){
                    lat.value = e.location.y;
                    lng.value = e.location.x;
                    marker.setLatLng([lat.value, lng.value]).update();

                });

                map.addControl(search);
            }

            initOpenstreetMap('map_{$this->name['lat']}{$this->name['lng']}');
        })();
JS;
    }

    public function useGoogleMap()
    {
        $this->script = <<<JS
        (function() {
            function initGoogleMap(name) {
                var lat = document.querySelector('#{$this->name['lat']}');
                var lng = document.querySelector('#{$this->name['lng']}');

                var LatLng = new google.maps.LatLng(lat.value, lng.value);

                var options = {
                    zoom: 13,
                    center: LatLng,
                    panControl: false,
                    zoomControl: true,
                    scaleControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }

                var container = document.getElementById("map_"+name);
                var map = new google.maps.Map(container, options);

                var marker = new google.maps.Marker({
                    position: LatLng,
                    map: map,
                    title: 'Drag Me!',
                    draggable: true
                });

                google.maps.event.addListener(marker, 'dragend', function (event) {
                    lat.value = event.latLng.lat();
                    lng.value = event.latLng.lng();
                });
            }

            initGoogleMap('{$this->name['lat']}{$this->name['lng']}');
        })();
JS;
    }

    public function useTencentMap()
    {
        $this->script = <<<JS
        (function() {
            function initTencentMap(name) {
                var lat = document.querySelector('#{$this->name['lat']}');
                var lng = document.querySelector('#{$this->name['lng']}');

                var center = new qq.maps.LatLng(lat.val(), lng.val());

                var container = document.getElementById("map_"+name);
                var map = new qq.maps.Map(container, {
                    center: center,
                    zoom: 13
                });

                var marker = new qq.maps.Marker({
                    position: center,
                    draggable: true,
                    map: map
                });

                if( ! lat.val() || ! lng.val()) {
                    var citylocation = new qq.maps.CityService({
                        complete : function(result){
                            map.setCenter(result.detail.latLng);
                            marker.setPosition(result.detail.latLng);
                        }
                    });

                    citylocation.searchLocalCity();
                }

                qq.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
                });

                qq.maps.event.addListener(marker, 'position_changed', function(event) {
                    var position = marker.getPosition();
                    lat.value = position.getLat();
                    lng.value = position.getLng();
                });
            }

            initTencentMap('{$this->name['lat']}{$this->name['lng']}');
        })();
JS;
    }

    public function useYandexMap()
    {
        $this->script = <<<JS
        (function() {
            function initYandexMap(name) {
                ymaps.ready(function(){

                    var lat = document.querySelector('#{$this->name['lat']}');
                    var lng = document.querySelector('#{$this->name['lng']}');

                    var myMap = new ymaps.Map("map_"+name, {
                        center: [lat.val(), lng.val()],
                        zoom: 18
                    });

                    var myPlacemark = new ymaps.Placemark([lat.val(), lng.val()], {
                    }, {
                        preset: 'islands#redDotIcon',
                        draggable: true
                    });

                    myPlacemark.events.add(['dragend'], function (e) {
                        lat.val(myPlacemark.geometry.getCoordinates()[0]);
                        lng.val(myPlacemark.geometry.getCoordinates()[1]);
                    });

                    myMap.geoObjects.add(myPlacemark);
                });

            }

            initYandexMap('{$this->name['lat']}{$this->name['lng']}');
        })();
JS;
    }

    public function render()
    {
        if (empty($this->value['lat'])) {
            $this->value['lat'] = $this->default['lat'];
        }
        if (empty($this->value['lng'])) {
            $this->value['lng'] = $this->default['lng'];
        }

        return parent::render();
    }
}
