@extends('layouts.app')

@section('content')

<div class="page-wrapper">

    <div class="row page-titles">

        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{trans('lang.map_view')}}</h3>
        </div>

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{trans('lang.map_view')}}
                </li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">

        <!-- start row -->
        <div class="card mb-3">

            <div class="card-body">

                <div class="row">

                    <div class="col-lg-12">
                        <h3 class="card-title">{{trans('lang.live_tracking')}}</h3>
                    </div>

                    <div class="col-lg-4">

                        <div class="table-responsive ride-list">

                            <div id="overlay" style="display:none">
                                <img src="{{ asset('images/spinner.gif') }}">
                            </div>

                            <div class="live-tracking-list">

                            </div>
                            <div id="load-more-div" style="display:none"><a href="javascript:void(0)"
                                    class="btn btn-primary btn-sm ml-2" id="load-more"
                                    style="color:#fff">{{trans('lang.load_more')}}</a></div>

                        </div>

                    </div>

                    <div class="col-lg-8">

                        <div id="map" style="height:450px"></div>

                        <div id="legend">
                            <h3>Legend</h3>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <style>
        #append_list12 tr {
            cursor: pointer;
        }

        #legend {
            font-family: Arial, sans-serif;
            background: #fff;
            padding: 10px;
            margin: 11px;
            border: 1px solid #000;
        }

        #legend h3 {
            margin-top: 0;
        }

        #legend img {
            vertical-align: middle;
        }
    </style>

    @endsection

    @section('scripts')

    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-storage.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-database.js"></script>
    <script src="https://unpkg.com/geofirestore@5.2.0/dist/geofirestore.js"></script>
    <script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
    <script src="{{ asset('js/crypto-js.js') }}"></script>
    <script src="{{ asset('js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.js') }}"></script>

    <script type="text/javascript">

        var database = firebase.firestore();

        var map;
        var marker;
        var markers = [];
        var map_data = [];

        var default_lat = '23.022505';
        var default_lng = '72.571365';
        var defaultLatLong = JSON.parse('<?php echo json_encode($lat_long); ?>');
        var itemsPerPage = 10;
        var currentPage = 1;
        var dataInfo = '';
        if (defaultLatLong.length != 0) {
            default_lat = parseFloat(defaultLatLong['lat']);
            default_lng = parseFloat(defaultLatLong['lng']);
        }

        var myLatlng = new google.maps.LatLng(default_lat, default_lng);
        var infowindow = new google.maps.InfoWindow();
        var legend = document.getElementById('legend');

        var mapOptions = {
            zoom: 10,
            center: myLatlng,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map"), mapOptions);

        var base_url = '{!! asset('/images/') !!}';

        var fliter_icons = {
            available: {
                name: 'Available',
                icon: base_url + '/available.png'
            },
            ontrip: {
                name: 'OnTrip',
                icon: base_url + '/ontrip.png'
            }
        };

        for (var key in fliter_icons) {
            var type = fliter_icons[key];
            var name = type.name;
            var icon = type.icon;
            var div = document.createElement('div');
            div.innerHTML = '<img src="' + icon + '"> ' + name;
            legend.appendChild(div);
        }

        map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);

        $(document).ready(function () {

            getLiveLocations();

            setTimeout(function () {
                $(".sidebartoggler").click();
            }, 500);

            $(document).on("click", ".ride-list .track-from", function () {
                var lat = $(this).data('lat');
                var lng = $(this).data('lng');
                map.panTo(new google.maps.LatLng(lat, lng));
            });

        });

        function getLiveLocations() {

            var database = firebase.firestore();

                database.collection('driver_location_update').get().then(async function (snapshots) {

                    var drivers = [];
                    if (snapshots.docs.length > 0) {
                        snapshots.docs.forEach((doc) => {
                            var data = doc.data();
                            data.driver_id = doc.id;
                            drivers.push(data);
                        });
                    }

                    if (drivers.length > 0) {
                        $.ajax({
                            url: "map/get_ride_info",
                            method: "POST",
                            dataType: "JSON",
                            beforeSend: function () {
                                jQuery("#overlay").show();
                            },
                            data: {'drivers': drivers, _token: '{{csrf_token()}}', },
                            success: function (resp) {
                                jQuery("#overlay").hide();
                                if (resp) {
                                    loadData(resp, currentPage);
                                    dataInfo = resp;
                                }
                            }
                        });
                    }
                });
        }

        function loadData(data, page) {
            var startIndex = (page - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            var itemsToDisplay = data.slice(startIndex, endIndex);

            itemsToDisplay.forEach(async (item, i) => {

                val = item;
                var html = '';
                html += '<div class="live-tracking-box track-from" data-lat="' + val.driver_latitude + '" data-lng="' + val.driver_longitude + '">';
                html += '<div class="live-tracking-inner">';
                html += '<span class="listicon"></span>';
                if (val.flag == "on_ride") {
                    html += '<a href="/parcel/show/' + val.ride_id + '" target="_blank"><i class="text-dark fs-12 fa-solid fa-circle-info" data-toggle="tooltip"></i></a>';
                }
                html += '<h3 class="drier-name">{{trans("lang.driver_name")}} : ' + val.driver_name + '</h3>';
                if (val.user_name) {
                    html += '<h4 class="user-name">{{trans("lang.user_name")}} : ' + val.user_name + '</h4>';
                }
                if (val.depart_name && val.destination_name) {
                    html += '<div class="location-ride">';
                    html += '<div class="from-ride"><span>' + val.depart_name + '</span></div>';
                    html += '<div class="to-ride"><span>' + val.destination_name + '</span></div>';
                    html += '</div>';
                }
                if (val.flag == "on_ride") {
                    html += '<span class="badge badge-danger">On Ride<span>';
                } else {
                    html += '<span class="badge badge-success">Available<span>';
                }
                html += '</div>';
                html += '</div>';

                $(".live-tracking-list").append(html);

                if (typeof val.driver_latitude != 'undefined' && typeof val.driver_longitude != 'undefined') {

                    let iconImg = '';
                    let position = '';

                    if (val.flag == "available") {
                        iconImg = base_url + '/car_available.png';
                    } else {
                        iconImg = base_url + '/car_on_trip.png';
                    }

                    let marker = new google.maps.Marker({
                        position: new google.maps.LatLng(val.driver_latitude, val.driver_longitude),
                        icon: {
                            url: iconImg,
                            scaledSize: new google.maps.Size(25, 25)
                        },
                        map: map
                    });

                    let content = `
                    <div class="p-2">
                        <h6>{{trans('lang.driver_name')}} : ${val.driver_name ?? '-'} </h6>
                        <h6>{{trans('lang.mobile_no')}} : ${val.driver_mobile ?? '-'} </h6>
                        <h6>{{trans('lang.brand')}} : ${val.vehicle_brand ?? '-'} </h6>
                        <h6>{{trans('lang.car_number')}} : ${val.vehicle_number ?? '-'} </h6>
                        <h6>{{trans('lang.car_model')}} : ${val.vehicle_model ?? '-'} </h6>
                        <h6>{{trans('lang.car_make')}} : ${val.vehicle_make ?? '-'} </h6>
                    </div>`;

                    let infowindow = new google.maps.InfoWindow({
                        content: content
                    });

                    marker.addListener('click', function () {
                        infowindow.open(map, marker);
                    });

                    markers.push(marker);

                    marker.setMap(map);

                    setInterval(function () {
                        locationUpdate(marker, val);
                    }, 10000);
                }
            });

            async function locationUpdate(marker, val) {
                let data = '';
                if (val.flag == "available") {
                    let snapshot = await database.collection('driver_location_update').doc(val.driver_id).get();
                    data = snapshot.data();
                } else {
                    let snapshot = await database.collection('ride_location_update').doc(val.doc_id).get();
                    data = snapshot.data();
                }
                if (data != undefined) {
                    marker.setPosition(new google.maps.LatLng(data.driver_latitude, data.driver_longitude));
                }
            }
            jQuery("#overlay").hide();
            if (endIndex >= data.length) {
                $('#load-more-div').css('display', 'none');
            } else {
                $('#load-more-div').css('display', 'block');

            }

        }
        $('#load-more').on('click', function () {
            currentPage++;
            //let mapdata = dataInfo;
            loadData(dataInfo, currentPage);
        })

    </script>

    @endsection