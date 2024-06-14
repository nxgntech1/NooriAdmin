@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{ trans('lang.edit_zone') }}</h3>
            </div>

            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href= "{!! route('zone') !!}">{{ trans('lang.zone') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('lang.edit_zone') }}</li>
                </ol>
            </div>
        </div>


        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card pb-4">
                        <form action="{{ route('zone.update', $zone->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                            <div class="card-body">
                                <div id="data-table_processing" class="dataTables_processing panel panel-default"
                                    style="display: none;">
                                    {{ trans('lang.processing') }}
                                </div>

                                <div class="error_top"></div>
                                
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <div class="row restaurant_payout_create">
                                    <div class="restaurant_payout_create-inner">
                                    <fieldset>
                                            <legend>{{ trans('lang.edit_zone') }}</legend>
                                            <div class="form-group row width-50">
                                                <label class="col-3 control-label">{{ trans('lang.name') }}</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control libelle" name="name"
                                                        value="{{ $zone->name }}">

                                                </div>
                                            </div>
                                            <div class="form-group row width-100">
                                                <div class="form-check">
                                                    @if ($zone->status === 'yes')
                                                        <input type="checkbox" class="user_active" id="status"
                                                            name="status" checked="checked">
                                                    @else
                                                        <input type="checkbox" class="user_active" id="status"
                                                            name="status">
                                                    @endif
                                                    <label class="col-3 control-label"
                                                        for="status">{{ trans('lang.status') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-sm-5">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h4>{{trans('lang.instructions')}}</h4>
                                                <p>{{trans('lang.instructions_help')}}</p>
                                                <p><i class="fa fa-hand-pointer-o map_icons"></i>{{trans('lang.instructions_hand_tool')}}</p>
                                                <p><i class="fa fa-plus-circle map_icons"></i>{{trans('lang.instructions_shape_tool')}}</p>
                                                <p><i class="fa fa-trash map_icons"></i>{{trans('lang.instructions_trash_tool')}}</p>
                                            </div>
                                            <div class="col-sm-12">
                                                <img src="{{asset('images/zone_info.gif')}}" alt="GIF" width="100%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" placeholder="{{ trans('lang.search_location') }}" id="search-box"
                                            class="form-control controls" />
                                        <div id="map"></div>
                                    </div>

                                    <div class="col-sm-2">
                                        <ul style="list-style: none;padding:0">
                                            <li>
                                                <a id="select-button" href="javascript:void(0)"
                                                    onclick="drawingManager.setDrawingMode(null)"
                                                    class="btn-floating zone-add-btn btn-large waves-effect waves-light tooltipped"
                                                    title="Use this tool to drag the map and select your desired location">
                                                    <i class="fa fa-hand-pointer-o map_icons"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a id="add-button" href="javascript:void(0)"
                                                    onclick="drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON)"
                                                    class="btn-floating zone-add-btn btn-large waves-effect waves-light tooltipped"
                                                    title="Use this tool to highlight areas and connect the dots">
                                                    <i class="fa fa-plus-circle map_icons"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a id="delete-all-button" href="javascript:void(0)" onclick="clearMap()"
                                                    class="btn-floating zone-delete-all-btn btn-large waves-effect waves-light tooltipped"
                                                    title="Use this tool to delete all selected areas">
                                                    <i class="fa fa-trash map_icons"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                                
                            </div>

                            <div class="form-group col-12 text-center btm-btn">
                                <button type="submit" class="btn btn-primary  create_user_btn"><i class="fa fa-save"></i>
                                    {{ trans('lang.save') }}</button>
                                <a href="{!! route('zone') !!}" class="btn btn-default"><i
                                        class="fa fa-undo"></i>{{ trans('lang.cancel') }}</a>
                            </div>

                            <input type="hidden" id="coordinates" name="coordinates" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<style>
    #map {
        height: 500px;
        width: 100%;
    }

    #panel {
        width: 200px;
        font-family: Arial, sans-serif;
        font-size: 13px;
        float: right;
        margin: 10px;
        margin-top: 100px;
    }

    #delete-button,
    #add-button,
    #delete-all-button,
    #save-button {
        margin-top: 5px;
    }

    #search-box {
        background-color: #f7f7f7;
        font-size: 15px;
        font-weight: 300;
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        height: 25px;
        border: 1px solid #c7c7c7;
    }

    .map_icons {
        font-size: 24px;
        color: white;
        padding: 10px;
        background-color: {{ isset($_COOKIE['admin_panel_color']) ? $_COOKIE['admin_panel_color'] : '#072750' }};
        margin: 5px;
    }
</style>

@section('scripts')

    <script type="text/javascript">
        var map;
        var drawingManager;
        var selectedShape;
        var selectedKernel;
        var gmarkers = [];
        var coordinates = [];
        var infowindow = new google.maps.InfoWindow({
            size: new google.maps.Size(150, 50)
        })
        var allShapes = [];
        var sendable_coordinates = [];
        var shapeColor = "#007cff";
        var kernelColor = "#000";
        var default_lat = '{{$default_lat}}';
        var default_lng = '{{$default_lng}}';
        var data = '{{$coordinates}}';
        let zones = JSON.parse(data.replace(/&quot;/g,'"'));

        google.maps.event.addDomListener(window, 'load', initMap);

        function addNewPolys(newPoly) {
            google.maps.event.addListener(newPoly, 'click', function() {
                setSelection(newPoly);
            });
        }

        function setMapOnAll(map) {
            for (var i = 0; i < gmarkers.length; i++) {
                gmarkers[i].setMap(map);
            }
        }

        function clearMarkers() {
            setMapOnAll(null);
        }

        function deleteMarkers() {
            clearMarkers();
            gmarkers = [];
        }

        function deleteSelectedShape() {
            if (selectedShape) {
                selectedShape.setMap(null);
                var index = allShapes.indexOf(selectedShape);
                if (index > -1) {
                    allShapes.splice(index, 1);
                }
            }
            if (selectedKernel) {
                selectedKernel.setMap(null);
            }

            let lat_lng = [];
            allShapes.forEach(function(data, index) {
                lat_lng[index] = getCoordinates(data);
            });

            if (lat_lng.length == 0) {
                document.getElementById('coordinates').value = '';
            } else {
                document.getElementById('coordinates').value = JSON.stringify(lat_lng);
            }
        }

        function clearMap() {
            if (allShapes.length > 0) {
                for (var i = 0; i < allShapes.length; i++) {
                    allShapes[i].setMap(null);
                }
                allShapes = [];
                deleteMarkers();
                document.getElementById('coordinates').value = null;
            }
        }

        function clearSelection() {
            if (selectedShape) {
                if (selectedShape.type !== 'marker') {
                    selectedShape.setEditable(false);
                }
                selectedShape = null;
            }

            if (selectedKernel) {
                if (selectedKernel.type !== 'marker') {
                    selectedKernel.setEditable(false);
                }
                selectedKernel = null;
            }
        }

        function setSelection(shape, check) {
            clearSelection();
            shape.setEditable(true);
            shape.setDraggable(true);
            if (check) {
                selectedKernel = shape;
            } else {
                selectedShape = shape;
            }
        }

        function getCoordinates(polygon) {
            var path = polygon.getPath();
            coordinates = [];
            for (var i = 0; i < path.length; i++) {
                coordinates.push({
                    lat: path.getAt(i).lat(),
                    lng: path.getAt(i).lng()
                });
            }
            return coordinates;
        }

        function createMarker(coord, nr, map) {
            var mesaj = "<h6>Vârf " + nr + "</h6><br>" + "Lat: " + coord.lat + "<br>" + "Lng: " + coord.lng;
            var marker = new google.maps.Marker({
                position: coord,
                map: map,
                //zIndex: Math.round(coord.lat * -100000) << 5
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(mesaj);
                infowindow.open(map, marker);
            });
            google.maps.event.addListener(marker, 'dblclick', function() {
                marker.setMap(null);
            });
            return marker;
        }

        function searchBox() {
            var input = document.getElementById('search-box');
            var searchBox = new google.maps.places.SearchBox(input);
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });

            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        return;
                    }
                    var icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };
                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });

        }

        function initMap() {

            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: new google.maps.LatLng(default_lat, default_lng),
                mapTypeControl: false,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                    position: google.maps.ControlPosition.LEFT_CENTER
                },
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scaleControl: false,
                scaleControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                streetViewControl: false,
                fullscreenControl: false
            });

            var i;
            var polygon;
            for (i = 0; i < zones.length; i++) {

                polygon = new google.maps.Polygon({
                    paths: zones[i],
                    strokeWeight: 1,
                    strokeColor:'#007cf',
                    fillColor: '#007cff',
                    fillOpacity: 0.4,
                });
                polygon.setMap(map);
                addNewPolys(polygon);
                allShapes.push(polygon);
                    google.maps.event.addListener(polygon, 'click', function(e) { getCoordinates(polygon); });
                    google.maps.event.addListener(polygon, "dragend", function(e) {
                    for (i=0; i < allShapes.length; i++) {
                        if (polygon.getPath() == allShapes[i].getPath()) {
                            allShapes.splice(i, 1);
                        }
                    }
                    allShapes.push(polygon);
                    let lat_lng = [];
                    allShapes.forEach(function(data, index) {
                        lat_lng[index] = getCoordinates(data);
                    });

                    document.getElementById('info').value = JSON.stringify(lat_lng);
                });
                    
                google.maps.event.addListener(polygon.getPath(), "insert_at", function(e) {
                    for (i=0; i < allShapes.length; i++) {   // Clear out the old allShapes entry
                        if (polygon.getPath() == allShapes[i].getPath()) {
                            allShapes.splice(i, 1);
                        }
                    }
                    allShapes.push(polygon);
                    let lat_lng = [];
                    allShapes.forEach(function(data, index) {
                        lat_lng[index] = getCoordinates(data);
                    });

                    document.getElementById('info').value = JSON.stringify(lat_lng);

                });
                
                google.maps.event.addListener(polygon.getPath(), "remove_at", function(e) { getCoordinates(polygon); });
                google.maps.event.addListener(polygon.getPath(), "set_at", function(e) { getCoordinates(polygon); });

            }

            let lat_lng = [];
            allShapes.forEach(function(data, index) {
                lat_lng[index] = getCoordinates(data);
            });

            document.getElementById('coordinates').value = JSON.stringify(lat_lng);

            searchBox();

            var shapeOptions = {
                strokeWeight: 1,
                fillOpacity: 0.4,
                editable: true,
                draggable: true
            };

            drawingManager = new google.maps.drawing.DrawingManager({
                // direct polygon drawing setting
                // drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingMode: null,
                drawingControl: false,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER,
                    drawingModes: ['polygon']
                },
                polygonOptions: shapeOptions,
                map: map
            });

            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {

                var newShape = e.overlay;
                allShapes.push(newShape);
                let lat_lng = [];
                allShapes.forEach(function(data, index) {
                    lat_lng[index] = getCoordinates(data);
                });
                document.getElementById('coordinates').value = JSON.stringify(lat_lng);

                newShape.setOptions({
                    fillColor: shapeColor
                });

                getCoordinates(newShape);
                drawingManager.setDrawingMode(null);
                setSelection(newShape, 0);

                google.maps.event.addListener(newShape, 'click', function(e) {
                    if (e.vertex !== undefined) {
                        var path = newShape.getPaths().getAt(e.path);
                        path.removeAt(e.vertex);
                        getCoordinates(newShape);
                        if (path.length < 3) {
                            newShape.setMap(null);
                        }
                    }
                    setSelection(newShape, 0);
                });

                //update coordinates
                google.maps.event.addListener(newShape, 'click', function(e) {
                    getCoordinates(newShape);
                });
                google.maps.event.addListener(newShape, "dragend", function(e) {
                    getCoordinates(newShape);
                });
                google.maps.event.addListener(newShape.getPath(), "insert_at", function(e) {
                    getCoordinates(newShape);
                });
                google.maps.event.addListener(newShape.getPath(), "remove_at", function(e) {
                    getCoordinates(newShape);
                });
                google.maps.event.addListener(newShape.getPath(), "set_at", function(e) {
                    getCoordinates(newShape);
                });
            });

            google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
            google.maps.event.addListener(map, 'click', clearSelection);
        }
    </script>
@endsection
