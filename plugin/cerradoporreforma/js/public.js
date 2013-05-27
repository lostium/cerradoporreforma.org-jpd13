(function($) {
    "use strict";

    $(function() {
        //inicializamos la nueva versión de mapas
        google.maps.visualRefresh = true;
        //paquete de la aplicacion
        lostium.cerradopor = {};

        /**
         * Controlador para subir imágenes
         */
        lostium.cerradopor.ImageUploaderController = function() {
            //llamamos al constructor padre
            lostium.jsMVC.Controller.call(this);
            //referencia al geocoder
            this.geocoder = null;
            this.ajaxUrl = null;
            this.resetModel();

        };
        //Extendemos del controlador
        lostium.cerradopor.ImageUploaderController.prototype = Object.create(lostium.jsMVC.Controller.prototype);

        /**
         * Método para obtener el Geocoder de google
         * @returns google.maps.Geocoder
         */
        lostium.cerradopor.ImageUploaderController.prototype.getGeocoder = function() {
            if (this.geocoder == null) {
                this.geocoder = new google.maps.Geocoder();
            }
            return this.geocoder;
        };
        /**
         * Reseteamos el modelo
         */
        lostium.cerradopor.ImageUploaderController.prototype.resetModel = function() {
            this.model.picture = {lat: null, lng: null, address: null, province: null, id: null};
        }

        /**
         * Funcion encargada de determinar si la foto dispone de coordenadas o
         * hay que solicitar al navegador la posición actual del usuario.
         *
         * @param array exif
         */
        lostium.cerradopor.ImageUploaderController.prototype.getLocation = function(exif) {
            var instance = this;
            //comprobamos si disponemos de la información EXIF asociada a la foto
            if (exif && exif.GPSLatitude) {
                var locationString = exif.GPSLatitudeRef + ' ' + exif.GPSLatitude[0] + ' ' + exif.GPSLatitude[1] + "', " + exif.GPSLongitudeRef + ' ' + exif.GPSLongitude[0] + ' ' + exif.GPSLongitude[1] + "'";
                var geocoder = instance.getGeocoder();
                geocoder.geocode({'address': locationString}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {

                        //Obtenemos la dirección y las coordenadas (en la foto están en grados)
                        var address = results[0]['formatted_address'];
                        var location = results[0]['geometry']['location'];
                        var lat = location.lat();
                        var lng = location.lng();



                        //actualizamos el modelo
                        var picture = instance.model.picture;
                        picture.lat = lat;
                        picture.lng = lng;
                        picture.address = address;
                        picture.province = instance.getProvince(results[0]['address_components']);

                        //notificamos a la vista los cambios
                        instance.view.render("viewLocation", {lat: lat, lng: lng, address: address});

                    } else {
                        instance.getDeviceLocation();
                    }
                });
            } else {
                instance.getDeviceLocation();
            }
        }

        /**
         * Busca localización por dirección
         *
         * @param string address
         */
        lostium.cerradopor.ImageUploaderController.prototype.getTextLocation = function(locationString) {
            var instance = this;
            var geocoder = instance.getGeocoder();
            geocoder.geocode({'address': locationString}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    //Obtenemos la dirección y las coordenadas (en la foto están en grados)
                    var address = results[0]['formatted_address'];
                    var location = results[0]['geometry']['location'];
                    var lat = location.lat();
                    var lng = location.lng();

                    //actualizamos el modelo
                    var picture = instance.model.picture;
                    picture.lat = lat;
                    picture.lng = lng;
                    picture.address = address;
                    picture.province = instance.getProvince(results[0]['address_components']);

                    //notificamos a la vista los cambios
                    instance.view.render("viewLocation", {lat: lat, lng: lng, address: address});

                }
            });
        }

        /**
         * Busca localización por dirección
         *
         * @param array addressComponents obtenidos de google maps
         */
        lostium.cerradopor.ImageUploaderController.prototype.getProvince = function(addressComponents) {
            var instance = this;

            var total = addressComponents.length;

            for (var i = 0; i < total; i++) {
                var component = addressComponents[i];
                var found = false;
                if (component['types']) {
                    var types = component['types'];
                    var typeTotal = types.length;
                    for (var j = 0; j < typeTotal; j++) {
                        if (types[j] == 'administrative_area_level_2') {
                            found = true;
                            break;
                        }
                    }
                    if (found)
                        return component['long_name'];
                }
            }
        }

        /**
         * Obtiene la posición en base al GPS del dispositivo
         *
         */
        lostium.cerradopor.ImageUploaderController.prototype.getDeviceLocation = function() {
            var instance = this;
            //En caso contrario utilizamos el GPS del dispositivo para clavar la chincheta
            navigator.geolocation.getCurrentPosition(function(location) {

                if (location.coords) {
                    var locationString = location.coords.latitude + ',' + location.coords.longitude;
                    //obtenemos la resolución inversa de la localización para mostrar la calle
                    var geocoder = instance.getGeocoder();
                    geocoder.geocode({'address': locationString}, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            //Obtenemos la dirección y las coordenadas (en la foto están en grados)
                            var address = results[0]['formatted_address'];
                            var location = results[0]['geometry']['location'];
                            var lat = location.lat();
                            var lng = location.lng();

                            //actualizamos el modelo
                            var picture = instance.model.picture;
                            picture.lat = lat;
                            picture.lng = lng;
                            picture.address = address;
                            picture.province = instance.getProvince(results[0]['address_components']);

                            //notificamos a la vista los cambios
                            instance.view.render("viewLocation", {lat: lat, lng: lng, address: address});
                        } else {
                            //No hemos podido obtener la calle pero igualmente actualizamos el modelo y notificamos a la vista

                            //actualizamos el modelo
                            var picture = instance.model.picture;
                            picture.lat = location.coords.latitude;
                            picture.lng = location.coords.longitude;
                            picture.address = 'localización desconocida';
                            picture.province = null;
                            //notificamos a la vista los cambios
                            instance.view.render("viewLocation", {lat: lat, lng: lng, address: address});
                        }
                    });
                }
            }, function(error) {
                switch (error.code)
                {
                    case error.PERMISSION_DENIED:
                        alert("No hemos podido conocer tu posición actual");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("No hemos podido detectar tu posición");
                        break;
                    case error.TIMEOUT:
                        alert("No hemos podido detectar tu posición");
                        break;
                    default:
                        alert("Lo sentimos, se ha producido un error");
                        break;
                }
            });
        }


        /**
         * Función encargada de actualizar la posición actual de un marker
         *
         * @param array exif
         */
        lostium.cerradopor.ImageUploaderController.prototype.moveLocation = function(latLng) {
            var instance = this;
            //comprobamos si disponemos de la información EXIF asociada a la foto

            var locationString = latLng.lat() + "," + latLng.lng();
            var geocoder = instance.getGeocoder();
            geocoder.geocode({'address': locationString}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {

                    //Obtenemos la dirección y las coordenadas (en la foto están en grados)
                    var address = results[0]['formatted_address'];
                    var lat = latLng.lat();
                    var lng = latLng.lng();

                    //actualizamos el modelo
                    var picture = instance.model.picture;
                    picture.lat = lat;
                    picture.lng = lng;
                    picture.address = address;
                    picture.province = instance.getProvince(results[0]['address_components']);


                    //notificamos a la vista los cambios
                    instance.view.render("moveLocation", {lat: lat, lng: lng, address: address});

                } else {
                    instance.getDeviceLocation();
                }
            });
        }

        /**
         * Se encarga de enviar el formulario con la foto y metadatos
         *
         * @param Object data
         *
         */
        lostium.cerradopor.ImageUploaderController.prototype.uploadPicture = function(data) {
            var instance = this;
            var formData = new FormData();

            var picture = instance.model.picture;
            formData.append('action', 'cpr_upload');
            formData.append('image', data.img, 'image.jpg');
            formData.append('title', data.title);
            formData.append('content', data.content);
            formData.append('lat', picture.lat);
            formData.append('lng', picture.lng);
            formData.append('address', picture.address);
            formData.append('province', picture.province);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', instance.ajaxUrl, true);
            xhr.onload = function(e) {
                var response = jQuery.parseJSON(this.responseText);
                if(response.permalink)
                    window.location = response.permalink;
            };

            xhr.send(formData);  // multipart/form-data
        }

        /**
         * Vista correspondiente al upload de imágenes
         */
        lostium.cerradopor.ImageUploaderView = function() {
            //llamamos al constructor padre
            lostium.jsMVC.View.call(this);

            //mapa de la vista
            this.map = null;
            //marker de la foto que está subiendo el usuario
            this.marker = null;
        };

        //Extendemos de la vista genérica
        lostium.cerradopor.ImageUploaderView.prototype = Object.create(lostium.jsMVC.View.prototype);

        /**
         * Inicialización de la vista
         */
        lostium.cerradopor.ImageUploaderView.prototype.init = function() {
            var instance = this;

            $('#cerradoModal').on('hide', function () {
                $('#cerradoModal .modal-body').scrollTop(0);
                $('#cerradoModal .upload-step-2').hide();
                instance.controller.resetModel();
                $('#droppedimage').empty();
                $('#dropbox').show();
                $('#cerradoModal input, #cerradoModal textarea').val('');
            });


            //captura el enter en la caja de dirección, eso hace que se dispare el geocoder
            $('#cpr-address').keypress(function(e) {
                if (e.which === 13) {
                    event.preventDefault();
                    instance.controller.getTextLocation($(this).val());
                }
            });

            //inicializa la infraestructura de upload
            //la librería original ha sido modificada para que no haga el upload directamente
            //lo almacenamos en uploaderSettings para su posterior upload con el formulario
            var uploaderSettings = $(".media-drop").html5Uploader({
                cropRatio: 1,
                /**
                 * File dropped / selected.
                 */
                onDropped: function(success) {
                    if (!success) {
                        $('.errormessages').text('Only allowed are jpg, png or gif images.');
                    } else {
                        $('.errormessages').empty();
                        $('.media-drop-placeholder > *').hide();
                        $('.media-drop-placeholder').toggleClass('busyloading', true).css('cursor', 'progress');
                    }
                },
                /**
                 * Image cropped and scaled.
                 */
                onProcessed: function(canvas, exif) {

                    if (canvas) {

                        // Remove possible previously loaded image.
                        var url = canvas.toDataURL();
                        var newImg = document.createElement("img");
                        newImg.src = url;

                        // Show new image.
                        $('#droppedimage').empty().append(newImg);

                        // Hide dropbox.
                        $('#dropbox').hide();

                        // Reset dropbox for reuse.
                        $('.errormessages').empty();
                        $('.media-drop-placeholder > *').show();
                        $('.media-drop-placeholder').toggleClass('busyloading', false).css('cursor', 'auto');

                        $('.upload-step-2').show('fast', function() {
                            instance.initMap();
                            instance.controller.getLocation(exif);
                        });
                    } else {
                        //no hemos detectado canvas
                    }
                }
            });
            //captura el enter en la caja de dirección, eso hace que se dispare el geocoder
            $('#upload-picture').click(function() {
                $('#upload-picture').html($('#upload-picture').data('label-uploading'));
                instance.controller.uploadPicture({
                    title: $('#cpr-title').val(),
                    content: $('#cpr-history').val(),
                    img: uploaderSettings.blob
                });
            });

        }



        /**
         * Inicializa el mapa de la vista
         */
        lostium.cerradopor.ImageUploaderView.prototype.initMap = function() {
            if (this.marker != null) {
                this.marker.setMap(null);
                this.marker = null;
            }
            if (this.map == null) {
                var latLng = new google.maps.LatLng(40.3448849, -3.6660599);
                this.map = new google.maps.Map(document.getElementById('map-upload-canvas'), {
                    zoom: 4,
                    center: latLng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: false, //desactivamos el scroll en base a la rueda del ratón para permitir hacer scroll a lo largo de la página
                });
            }

        }
        /**
         * Visualiza una posición en el mapa
         *
         * @param lostium.cerradopor.ImageUploaderView instance
         * @param mixed data
         */
        lostium.cerradopor.ImageUploaderView.prototype.viewLocation = function(instance, data) {
            var latLng = new google.maps.LatLng(data.lat, data.lng);
            instance.map.setCenter(latLng);
            instance.map.setZoom(17);

            //establecemos la direccion
            $('#cpr-address').val(data.address);

            this.getMarker();

        }
        /**
         * Función que muestra en el mapa el marker asociado
         *
         * @param lostium.cerradopor.ImageUploaderView instance
         * @param mixed data
         */
        lostium.cerradopor.ImageUploaderView.prototype.viewLocation = function(instance, data) {
            var latLng = new google.maps.LatLng(data.lat, data.lng);
            instance.setMarker(latLng);
            instance.map.panTo(latLng);
            instance.map.setZoom(17);
            //establecemos la direccion
            $('#cpr-address').val(data.address);
        }

        /**
         * Actualiza un marker con los datos de la nueva posición
         *
         * @param lostium.cerradopor.ImageUploaderView instance
         * @param mixed data
         *
         */
        lostium.cerradopor.ImageUploaderView.prototype.moveLocation = function(instance, data) {
            instance.checkMarkerVisibility();
            $('#cpr-address').val(data.address);
        }

        /**
         * Establece el marker en el mapa
         *
         * @param google.maps.LatLng latLng
         */
        lostium.cerradopor.ImageUploaderView.prototype.setMarker = function(latLng) {
            var instance = this;
            if (instance.marker == null) {
                instance.marker = new google.maps.Marker({
                    position: latLng,
                    map: instance.map,
                    draggable: true
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
        lostium.cerradopor.ImageUploaderView.prototype.checkMarkerVisibility = function() {
            var instance = this;
            if (instance.marker) {
                var bounds = this.map.getBounds();
                //si el marker no se ve, centramos.
                if (!bounds.contains(instance.marker.getPosition())) {
                    instance.map.panTo(instance.marker.getPosition());
                }
            }
        }


        $(document).ready(function() {
            lostium.cerradopor.controlador = new lostium.cerradopor.ImageUploaderController();
            //actualizamos la url ajax
            lostium.cerradopor.controlador.ajaxUrl = cprImageUploadVars.ajaxUrl;
            //inicializamos la vista
            lostium.cerradopor.controlador.init(lostium.cerradopor.controlador, new lostium.cerradopor.ImageUploaderView());
        });
    });

}(jQuery));