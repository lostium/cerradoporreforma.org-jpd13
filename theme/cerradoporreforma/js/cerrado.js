(function($) {
    "use strict";
    $(function() {
        /**
         * Definición de estilos para los mapas
         *
         * @type Array
         */
        var cprStyleMap =
[
  {
    "featureType": "water",
    "elementType": "geometry",
    "stylers": [
      { "visibility": "on" },
      { "lightness": -57 },
      { "saturation": -85 }
    ]
  },{
    "featureType": "water",
    "elementType": "labels.text.stroke",
    "stylers": [
      { "visibility": "on" },
      { "color": "#969696" }
    ]
  },{
    "featureType": "water",
    "elementType": "labels.text.fill",
    "stylers": [
      { "color": "#313131" }
    ]
  },{
    "featureType": "landscape.natural",
    "elementType": "geometry.fill",
    "stylers": [
      { "color": "#808080" },
      { "visibility": "on" }
    ]
  },{
    "featureType": "road",
    "elementType": "geometry.fill",
    "stylers": [
      { "visibility": "on" },
      { "color": "#808080" },
      { "hue": "#ff0000" },
      { "saturation": -100 },
      { "lightness": 23 },
      { "gamma": 0.96 }
    ]
  },{
    "featureType": "landscape.man_made",
    "stylers": [
      { "visibility": "on" },
      { "saturation": -100 },
      { "lightness": -15 }
    ]
  },{
    "featureType": "poi",
    "stylers": [
      { "saturation": -100 },
      { "visibility": "simplified" }
    ]
  },{
  },{
    "featureType": "road",
    "elementType": "labels.text.stroke",
    "stylers": [
      { "visibility": "on" },
      { "color": "#dcdcdc" }
    ]
  },{
    "featureType": "road",
    "elementType": "labels.icon",
    "stylers": [
      { "visibility": "on" },
      { "saturation": -100 },
      { "lightness": -22 }
    ]
  },{
    "elementType": "geometry.stroke",
    "stylers": [
      { "saturation": -100 }
    ]
  }
];

        /**
         * Controlador para post individual
         */
        lostium.cerradopor.SinglePostController = function() {
            //llamamos al constructor padre
            lostium.jsMVC.Controller.call(this);
            this.resetModel();

        };
        //Extendemos del controlador
        lostium.cerradopor.SinglePostController.prototype = Object.create(lostium.jsMVC.Controller.prototype);

        /**
         * Reseteamos el modelo
         */
        lostium.cerradopor.SinglePostController.prototype.resetModel = function() {
            this.model.picture = {lat: null, lng: null, address: null, province: null, id: null};
        }

        lostium.cerradopor.SinglePostController.prototype.setPictureLocation = function(lat, lng) {
            this.model.picture.lat = parseFloat(lat);
            this.model.picture.lng = parseFloat(lng);
        }


        /**
         * Vista correspondiente a un elemento individual
         */
        lostium.cerradopor.SinglePostView = function() {
            //llamamos al constructor padre
            lostium.jsMVC.View.call(this);

            //mapa de la vista
            this.map = null;
            //marker de la foto que está subiendo el usuario
            this.marker = null;
        };

        //Extendemos de la vista genérica
        lostium.cerradopor.SinglePostView.prototype = Object.create(lostium.jsMVC.View.prototype);

        /**
         * Inicialización de la vista
         */
        lostium.cerradopor.SinglePostView.prototype.init = function() {
            this.initMap();
        }

        /**
         * Inicializa el mapa de la vista
         */
        lostium.cerradopor.SinglePostView.prototype.initMap = function() {
            if (this.marker != null) {
                this.marker.setMap(null);
                this.marker = null;
            }
            if (this.map == null) {
                var latitud = this.controller.model.picture.lat;
                var longitud = this.controller.model.picture.lng;
                var latlng = new google.maps.LatLng(latitud, longitud);

                var myOptions = {
                    zoom: 17,
                    minZoom: 3, //Zoom mínimo para el mapa (no podrá alejar más)
                    center: latlng,
                    scrollwheel: false, //desactivamos el scroll en base a la rueda del ratón para permitir hacer scroll a lo largo de la página
                    mapTypeControl: false, //desactivamos el tipo de mapa, por defecto solo road
                    streetViewControl: true,
                    panControl: false, //controles de panning desactivados
                    zoomControl: true,
                    zoomControlOptions: {
                        //      style: google.maps.ZoomControlStyle.LARGE
                        position: google.maps.ControlPosition.LEFT_CENTER //posicionamos el control de zoom en el centro a la izquierda
                    },
                    scaleControl: false,
                    mapTypeId: 'cerradoporreforma',
                    overviewMapControl: true
                };
                //instanciamos el mapa
                this.map = new google.maps.Map(document.getElementById("map"), myOptions);
                //creamos el estilo personalizado asociado
                var styledMapType = new google.maps.StyledMapType(cprStyleMap, {name: 'cerradoporreforma'});
                //asignamos el estilo al mapa
                this.map.mapTypes.set('cerradoporreforma', styledMapType);
                this.setMarker(latlng);
            }

        }

        /**
         * Establece el marker en el mapa
         *
         * @param google.maps.LatLng latLng
         */
        lostium.cerradopor.SinglePostView.prototype.setMarker = function(latLng) {
            var instance = this;
            if (instance.marker == null) {
                instance.marker = new google.maps.Marker({
                    icon: cprVars.siteUrl + 'wp-content/themes/cerradoporreforma/img/cpr-poi.png',
                    position: latLng,
                    map: instance.map
                });
                google.maps.event.addListener(instance.marker, 'dragend', function() {
                    instance.controller.moveLocation(instance.marker.getPosition());
                });
            } else {
                instance.marker.setPosition(latLng);

                instance.checkMarkerVisibility();
            }
        }
        /**
         * Comprueba que el marker se encuentra visible en el mapa, si no lo centra
         * en base a su posición
         */
        lostium.cerradopor.SinglePostView.prototype.checkMarkerVisibility = function() {
            var instance = this;
            if (instance.marker) {
                var bounds = this.map.getBounds();
                //si el marker no se ve, centramos.
                if (!bounds.contains(instance.marker.getPosition())) {
                    instance.map.panTo(instance.marker.getPosition());
                }
            }
        }

        /**
         * Controlador para post individual
         */
        lostium.cerradopor.ArchiveController = function() {
            //llamamos al constructor padre
            lostium.jsMVC.Controller.call(this);
            this.resetModel();

        };
        //Extendemos del controlador
        lostium.cerradopor.ArchiveController.prototype = Object.create(lostium.jsMVC.Controller.prototype);

        /**
         * Reseteamos el modelo
         */
        lostium.cerradopor.ArchiveController.prototype.resetModel = function() {
            this.model.markers = null;
        }

        /**
         * Función que se encarga de recuperar los negocios cerrados, los itera
         * y crea los markers correspondientes
         */
        lostium.cerradopor.ArchiveController.prototype.getBusiness = function() {

            var instance = this;
            $.getJSON(this.siteUrl + 'api/negocios.json', function(data) {
                instance.view.render("initMap", data);
            });
        }


        /**
         * Vista correspondiente al listado de negocios
         */
        lostium.cerradopor.ArchiveView = function() {
            //llamamos al constructor padre
            lostium.jsMVC.View.call(this);

            //mapa de la vista
            this.map = null;
            //cluster de elementos
            this.markerClusterer = null;
            //infowindow
            this.infoWindow = null;

        };

        //Extendemos de la vista genérica
        lostium.cerradopor.ArchiveView.prototype = Object.create(lostium.jsMVC.View.prototype);

        /**
         * Inicialización de la vista
         */
        lostium.cerradopor.ArchiveView.prototype.init = function() {
            var instance = this;
            //obtenemos los negocios para el mapa, para ello pedimos al controlador
            //que se encargue ya que la llamada es asíncrona
            instance.controller.getBusiness();
        }

        /**
         * Inicializa el mapa de la vista
         */
        lostium.cerradopor.ArchiveView.prototype.initMap = function(instance, data) {

            var cprStyleCluster = [[{
                        url: cprVars.siteUrl + 'wp-content/themes/cerradoporreforma/img/cluster35.png',
                        height: 35,
                        width: 35,
                        anchor: [38, 0],
                        textColor: '#eeeeee',
                        textSize: 14
                    }, {
                        url: cprVars.siteUrl + 'wp-content/themes/cerradoporreforma/img/cluster45.png',
                        height: 45,
                        width: 45,
                        anchor: [48, 0],
                        textColor: '#eeeeee',
                        textSize: 16
                    }, {
                        url: cprVars.siteUrl + 'wp-content/themes/cerradoporreforma/img/cluster55.png',
                        height: 55,
                        width: 55,
                        anchor: [58, 0],
                        textColor: '#eeeeee',
                        textSize: 18
                    }]];


            var latlng = new google.maps.LatLng(40.335553, -3.725159);

            var myOptions = {
                zoom: 5,
                minZoom: 3, //Zoom mínimo para el mapa (no podrá alejar más)
                center: latlng,
                scrollwheel: false, //desactivamos el scroll en base a la rueda del ratón para permitir hacer scroll a lo largo de la página
                mapTypeControl: false, //desactivamos el tipo de mapa, por defecto solo road
                streetViewControl: true,
                panControl: false, //controles de panning desactivados
                zoomControl: true,
                zoomControlOptions: {
                    //      style: google.maps.ZoomControlStyle.LARGE
                    position: google.maps.ControlPosition.LEFT_CENTER //posicionamos el control de zoom en el centro a la izquierda
                },
                scaleControl: false,
                mapTypeId: 'cerradoporreforma',
                overviewMapControl: true
            };
            //instanciamos el mapa
            instance.map = new google.maps.Map(document.getElementById("map"), myOptions);
            //creamos el estilo personalizado asociado
            var styledMapType = new google.maps.StyledMapType(cprStyleMap, {name: 'cerradoporreforma'});
            //asignamos el estilo al mapa
            instance.map.mapTypes.set('cerradoporreforma', styledMapType);

            var markers = [];
            $.each(data, function(index, business) {

                var latLng = new google.maps.LatLng(business.lat, business.lng);
                var marker = new google.maps.Marker({
                    title: business.title,
                    icon: cprVars.siteUrl + 'wp-content/themes/cerradoporreforma/img/cpr-poi.png',
                    'position': latLng,
                });
                marker.data = business;
                business.marker = marker;

                google.maps.event.addListener(marker, 'click', function() {
                    var infoWindow = instance.getInfoWindow();
                    infoWindow.setContent('<p>'+marker.data.title+'</p><a class="photo-infowindow" href="' +marker.data.permalink+'"><img src="' + marker.data.image + '" style="width:300px;height:300px;"/></a>');
                    infoWindow.open(instance.map, marker);
                });
                markers.push(marker);
            });

            instance.markerClusterer = new MarkerClusterer(instance.map, markers, {
                maxZoom: 12,
                styles: cprStyleCluster[0]
            });
        }
        /**
         * Devuelve la instancia de un infowindow
         * @returns google.maps.InfoWindow
         */
        lostium.cerradopor.ArchiveView.prototype.getInfoWindow = function() {
            var instance = this;
            if (instance.infoWindow == null) {
                instance.infoWindow = new google.maps.InfoWindow({
                    content: ''
                });
            }
            return instance.infoWindow;
        }



        $(document).ready(function() {

            //elementos comunes

            // Activamos typeahead para provincias
            $('#province-search').typeahead({
                source: ['Álava', 'Albacete', 'Alicante', 'Almería', 'Asturias', 'Ávila', 'Badajoz', 'Barcelona', 'Burgos', 'Cáceres', 'Cádiz', 'Cantabria', 'Castellón', 'Ciudad Real', 'Córdoba', 'A Coruña', 'Cuenca', 'Girona', 'Granada', 'Guadalajara', 'Guipuzcoa', 'Huelva', 'Huesca', 'Islas Baleares', 'Jaén', 'León', 'Lleida', 'Lugo', 'Madrid', 'Málaga', 'Murcia', 'Navarra', 'Ourense', 'Palencia', 'Las Palmas', 'Pontevedra', 'La Rioja', 'Salamanca', 'Segovia', 'Sevilla', 'Soria', 'Tarragona', 'Santa Cruz de Tenerife', 'Teruel', 'Toledo', 'Valencia', 'Valladolid', 'Vizcaya', 'Zamora', 'Zaragoza']
                //{"Álava":"alava","Albacete":"albacete","Alicante":"alicante","Almería":"almeria","Asturias":"asturias","Ávila":"avila","Badajoz":"badajoz","Barcelona":"barcelona","Burgos":"burgos","Cáceres":"caceres","Cádiz":"cadiz","Cantabria":"cantabria","Castellón":"castellon","Ceuta":"ceuta","Ciudad Real":"ciudad-real","Córdoba":"cordoba","A Coruña":"a-coruna","Cuenca":"cuenca","Girona":"girona","Granada":"granada","Guadalajara":"guadalajara","Guipuzcoa":"guipuzcoa","Huelva":"huelva","Huesca":"huesca","Islas Baleares":"islas-baleares","Jaén":"jaen","León":"leon","Lleida":"lleida","Lugo":"lugo","Madrid":"madrid","Málaga":"malaga","Melilla":"melilla","Murcia":"murcia","Navarra":"navarra","Ourense":"ourense","Palencia":"palencia","Las Palmas":"las-palmas","Pontevedra":"pontevedra","La Rioja":"la-rioja","Salamanca":"salamanca","Segovia":"segovia","Sevilla":"sevilla","Soria":"soria","Tarragona":"tarragona","Santa Cruz de Tenerife":"santa-cruz-de-tenerife","Teruel":"teruel","Toledo":"toledo","Valencia":"valencia","Valladolid":"valladolid","Vizcaya":"vizcaya","Zamora":"zamora","Zaragoza":"zaragoza"}
            });

            if (cprVars.is_single == '1') {
                //controlador individual
                lostium.cerradopor.singlePostController = new lostium.cerradopor.SinglePostController();
                //recuperamos la posición del negocio y lo establecemos en el controlador
                var business = $("article.business");
                lostium.cerradopor.singlePostController.setPictureLocation($(business).data('lat'), $(business).data('lng'));

                //inicializamos la vista
                lostium.cerradopor.singlePostController.init(lostium.cerradopor.singlePostController, new lostium.cerradopor.SinglePostView());
            } else if (cprVars.is_archive == '1') {
                //controlador individual
                lostium.cerradopor.archiveController = new lostium.cerradopor.ArchiveController();
                //pasamos al controlador la url de acceso
                lostium.cerradopor.archiveController.siteUrl = cprVars.siteUrl;

                //inicializamos la vista
                lostium.cerradopor.archiveController.init(lostium.cerradopor.archiveController, new lostium.cerradopor.ArchiveView());
            }
            $('.footer-subir').click(function() {
                $("html, body").animate({scrollTop: 0}, 600);
                return false;
            });
        });

    });

}(jQuery));