<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <meta name="robots" content="noindex"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Japon Endroit Cool</title>
    <style>
        html, body {
            height: 100%;
            margin: 0 auto;
            padding: 0 auto;
        }

        #map-canvas {
            height: 100%;
            margin: 0 auto;
            padding: 0 auto;
        }

        #header {
            position: absolute;
            z-index: 5;
            right: 0;
            margin: 0 auto;
            padding: 0 auto;
        }

        #waitLoad {
            width: 100%;
            height: 100%;
            opacity: 0.5;
            z-index: 100;
            display: none;
            position: fixed;
            background-size: 100px;
            background: #0f0f0f url(./loading.gif) no-repeat fixed center;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBLZBdIEwq1_EECHFA6Owy5ctkFXQ0xd_I"></script>
</head>
<body>
<div id="waitLoad"></div>
<div id="modalAddMarker" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ajout d'un Maker</h4>
            </div>
            <div class="modal-body">
                <p>titre : <input type="text" id="titre"></p>
                <p>desciption : <input type="text" id="desciption"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <button id="addMarker" type="button" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </div>
</div>
<div id="header"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalAddMarker">ajouter un Marker</button></div>
<div id="map-canvas"></div>
<script>
    var infowindow;
    var map;

    $( document ).ready(function() {
        $("#waitLoad").show();

        $("#addMarker").click(function() {
            var titre = $("#desciption").val();
            var desciption = $("#desciption").val();
            geolocaliser(titre, desciption);
        });

        var mapOptions = {
            zoom: 6,
            center: new google.maps.LatLng(35.6730185,139.4302008)
        };

        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        map.addListener("click", function(){
            if (typeof( window.infoopened ) != 'undefined') infowindow.close();
        });


        jQuery.ajax({
            type: 'GET',
            url: "./ajax.php",
            data: {
                action: "listingGeoPoint"
            },
            success: function(data){
                try{
                    var listeGeopoint = JSON.parse(data);

                    listeGeopoint.forEach(function(unGeopoint){
                        ajoutMarker(unGeopoint);
                    });

                    $("#waitLoad").hide();
                }catch (e){
                    console.error("error listingMarker ajax : " + e);
                }
            },
            error: function(){
                console.log("error listingMarker ajax");
            }
        });
    });

    function ajoutMarker(unGeopoint){
        var marker = "";

        if (unGeopoint.latitude != "" && unGeopoint.longitude != ""){
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(unGeopoint.latitude, unGeopoint.longitude),
                map: map,
                title: unGeopoint.titre,
                zIndex: 999
            });

            marker.addListener('click', function(){
                infowindow = new google.maps.InfoWindow({
                    content: unGeopoint.titre + "<br>" + unGeopoint.description
                });

                if (typeof(window.markerOpen) != "undefined") markerOpen.close();
                infowindow.open(map, marker);
                markerOpen = infowindow;
            });
        }
    }

    function geolocaliser(titre, description){
        if (window.navigator.geolocation){
            var options = {
                enableHighAccuracy: true,
                timeout: 1000,
                maximumAge: 0
            };

            function showPosition(position){
                "use strict";
                $.ajax({
                    type: 'GET',
                    url: "./ajax.php",
                    data: {
                        action: "addGeoPoint",
                        titre: titre,
                        description: description,
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    },
                    success: function(data){
                        if (data != ""){
                            console.log("error geolocaliser ajax : " + data);
                        }else{
                            ajoutMarker({titre:titre, description:description, latitude:position.coords.latitude, longitude:position.coords.longitude});
                            $('#modalAddMarker').modal('hide');
                        }
                    },
                    error: function(){
                        console.log("error geolocaliser ajax");
                    }
                });
            }

            function errorCallback(error){
                switch (error.code){
                    case error.PERMISSION_DENIED:
                        console.log("L'utilisateur n'a pas autorisé l'accès à sa position");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        console.log("L'emplacement de l'utilisateur n'a pas pu être déterminé");
                        break;
                    case error.TIMEOUT:
                        console.log("Le service n'a pas répondu à temps");
                        break;
                }
            }

            function stopWatch(watchId){
                navigator.geolocation.clearWatch(watchId);
            }

//            var watchId = window.navigator.geolocation.watchPosition(showPosition, errorCallback, {enableHighAccuracy: true});
//            stopWatch(watchId);
            window.navigator.geolocation.getCurrentPosition(showPosition, errorCallback, options);
        }else{
            console.log("Geolocation is not supported by this browser.");
        }
    }
</script>
</body>
</html>