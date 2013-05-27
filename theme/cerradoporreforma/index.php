<?php get_header(); ?>
<section id="map-wrapper" class="container-fluid">
    <div class="row-fluid">
        <div id='map' class="span12">
            <div class="map-loading">
                <p>Cargando mapa...</p>
            </div>
        </div>
    </div>

    <div class="big-anual-figure">
        <p>En <em>2011</em> se <strong>destruyeron</strong>
            <span>0</span> <strong>empresas</strong> en España</p>
    </div>

    <ul class="year-timeline">
        <p>Año</p>
        <li>
            <a class="selected" href="javascript:void(0);" title="">2011</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2010</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2009</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2008</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2007</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2006</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2005</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2004</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2003</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2002</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2001</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">2000</a>
        </li>
        <li>
            <a href="javascript:void(0);" title="">1999</a>
        </li>
    </ul>

</section>

<section class="container-fluid portada-photos">
    <div class="container">
        <?php $query_slider = new WP_Query('category_name=cerrado&posts_per_page=4');?>
        <header>
            <h1>
                <span><?php echo $query_slider->found_posts ?></span> fotos en el mapa colaborativo
            </h1>
        </header>
        <div class="row-fluid">

            <?php
            // The Loop
            while ($query_slider->have_posts()) : $query_slider->the_post();
                ?>
                <div class="span3">
                    <a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title()) ?>"><?php the_post_thumbnail('medium'); ?></a>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        <a class="cpr-btn map-btn" href="<?php echo site_url('/categoria/negocio/cerrado/')?>" title="Ir al mapa colaborativo"><i class="icon-map-marker"></i> Ver mapa colaborativo</a>
    </div>
</section>

<section class="portada-provincias">
    <div class="container">
        <header>
            <h1>
                Empresas destruídas en 2011 por provincia
            </h1>
        </header>
        <img style="padding-bottom:20px;" src="<?php echo get_template_directory_uri() ?>/img/spain-provinces-data.png" alt="Mapa de cantorcete que debería ser interactivo" />
    </div>
</section>

<section class="portada-graficos">
    <div class="container">
        <header>
            <h1>
                Destrucción de empresas durante la crisis en España <small>de 1999 a 2011</small>
            </h1>
        </header>
        <div id="business-graph"></div>
    </div>
</section>

<section class="portada-last-stories">
    <div class="container">
        <div class="row-fluid">
            <header>
                <h1>
                    Últimas historias contadas
                </h1>
            </header>
            <div id="last-stories-slider" class="carousel slide">
                <div class="carousel-inner">
                    <?php
                    $query_slider = new WP_Query('tag=destacado&posts_per_page=4');
                    // The Loop
                    $countposts = 0;while ($query_slider->have_posts()) : $query_slider->the_post();$countposts++;
                        ?>

                        <article class="last-story item <?php if($countposts == 1) {echo 'active';}?>">
                            <div class="span3 offset1">
                                <a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title()) ?>"><?php the_post_thumbnail('medium'); ?></a>
                            </div>
                            <div class="span7 last-story-text">
                                <h1><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title()) ?>"><i class="icon-quote-left icon-2x pull-left"></i><?php the_title()?></a></h1>
                                <a class="cpr-avatar" href="" title=""><?php echo get_avatar( get_the_author_meta('ID')); ?></a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
                <!-- Carousel nav -->
                <a class="carousel-control left" href="#last-stories-slider" data-slide="prev">&lsaquo;</a>
                <a class="carousel-control right" href="#last-stories-slider" data-slide="next">&rsaquo;</a>
            </div>
        </div>
    </div>
</section>


<?php get_footer(); ?>

<script>
    $(document).ready(function() {

        var cerradoporreformaStyleMap =
                [
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [
                            {"visibility": "on"},
                            {"lightness": -57},
                            {"saturation": -85}
                        ]
                    }, {
                        "featureType": "water",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {"visibility": "on"},
                            {"color": "#969696"}
                        ]
                    }, {
                        "featureType": "water",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {"color": "#313131"}
                        ]
                    }, {
                        "featureType": "landscape.natural",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {"color": "#808080"},
                            {"visibility": "on"}
                        ]
                    }, {
                        "featureType": "road",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {"visibility": "on"},
                            {"color": "#808080"},
                            {"hue": "#ff0000"},
                            {"saturation": -100},
                            {"lightness": 23},
                            {"gamma": 0.96}
                        ]
                    }, {
                        "featureType": "landscape.man_made",
                        "stylers": [
                            {"visibility": "on"},
                            {"saturation": -100},
                            {"lightness": -15}
                        ]
                    }, {
                        "featureType": "poi",
                        "stylers": [
                            {"saturation": -100},
                            {"visibility": "simplified"}
                        ]
                    }, {
                    }, {
                        "featureType": "road",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {"visibility": "on"},
                            {"color": "#dcdcdc"}
                        ]
                    }, {
                        "featureType": "road",
                        "elementType": "labels.icon",
                        "stylers": [
                            {"visibility": "on"},
                            {"saturation": -100},
                            {"lightness": -22}
                        ]
                    }, {
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {"saturation": -100}
                        ]
                    }
                ]

        var cerradoporreformaStyleCluster = [[{
                    url: '/wp-content/themes/tuitrafico/img/niveles/clustergas35.png',
                    height: 35,
                    width: 35,
                    anchor: [38, 0],
                    textColor: '#cddeea',
                    textSize: 14
                }, {
                    url: '/wp-content/themes/tuitrafico/img/niveles/clustergas45.png',
                    height: 45,
                    width: 45,
                    anchor: [48, 0],
                    textColor: '#cddeea',
                    textSize: 16
                }, {
                    url: '/wp-content/themes/tuitrafico/img/niveles/clustergas55.png',
                    height: 55,
                    width: 55,
                    anchor: [58, 0],
                    textColor: '#cddeea',
                    textSize: 18
                }]];

        var latitud = 40.429962;
        var longitud = -3.707939;
        var latlng = new google.maps.LatLng(latitud, longitud);

        var myOptions = {
            zoom: 12,
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
        map = new google.maps.Map(document.getElementById("map"), myOptions);
        //creamos el estilo personalizado asociado
        var styledMapType = new google.maps.StyledMapType(cerradoporreformaStyleMap, {name: 'cerradoporreforma'});
        //asignamos el estilo al mapa
        map.mapTypes.set('cerradoporreforma', styledMapType);

        $('.footer-subir').click(function() {
            $("html, body").animate({scrollTop: 0}, 600);
            return false;
        });

        // Carrusel de últimas historias
        $('#last-stories-slider').carousel({interval: 15000});

        // Mostramos el tooltip de provincias
        $('#province-search').tooltip({
            placement: 'bottom',
            title: 'Busca en tu provincia',
            trigger: 'hover'
        });

        // Desactivamos tooltip y placeholder al pinchar
        $('#province-search').click(function() {
            $('#province-search').tooltip('hide');
            $('#province-search').attr('placeholder', '').attr('value', '');
        });

        // Para el componente central de la portada. De momento, datos hardcoded
        var yearData = {"1999": 76591, "2000": 49925, "2001": 65083, "2002": 102759, "2003": 129424, "2004": 121546, "2005": 110264, "2006": 162264, "2007": 85582, "2008": -66409, "2009": -64567, "2010": -40687, "2011": -50959};

        // Animamos el componente central por primera vez
        $('.big-anual-figure').animate({opacity: 1}, 2000, function() {
            printYearData("2011");
        });
        // Navegador de años
        $('.year-timeline li a').click(function() {
            $('.year-timeline li a').removeClass("selected");
            $(this).addClass("selected");
            $('.big-anual-figure em').text($(this).text());
            printYearData($(this).text());
        });
        // Aquí actualizamos los datos para cada año
        function printYearData(year) {

            var currentValue = parseFloat($('.big-anual-figure span').text());
            var thisYearData = parseFloat(yearData[year]);

            // Nice function from Joss Crowcroft to animate increment/decrement
            $.fn.increment = function(from, to, duration, easing, complete) {
                var params = $.speed(duration, easing, complete);
                return this.each(function() {
                    var self = this;
                    params.step = function(now) {
                        self.innerText = ((now > 0 ? '+' : '') + (now << 0)).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
                    };

                    $({number: from}).animate({number: to}, params);
                });
            };
            $('.big-anual-figure span').increment(currentValue, thisYearData);
            //Aplicamos estilos en función de las cifras
            $('.big-anual-figure span').removeClass("green red");
            if (thisYearData > 0) {
                $('.big-anual-figure strong:first').text("crearon");
                $('.big-anual-figure span').addClass("green");
            } else {
                $('.big-anual-figure strong:first').text("destruyeron");
                $('.big-anual-figure span').addClass("red");
            }

        }

    });

</script>
<script type="text/javascript">

    google.load('visualization', '1', {packages: ['annotatedtimeline']});
    function drawVisualization() {
       var wrap = new google.visualization.ChartWrapper({
           'chartType':'ColumnChart',
           'dataSourceUrl':'http://www.google.com/fusiontables/gvizdata?tq=',
           'containerId':'business-graph',
           'query':"SELECT anio, total_dif from 1-ZvFQu33fdYKQuOLCStNzZNDqgppcWyEvix1vKk where provincia = 'Total Nacional'",
           'options': {'title':'Creación/destrucción de empresas en España 2000-2012', 'legend':'none'}
           });
         wrap.draw();
    }

    google.setOnLoadCallback(drawVisualization);
</script>