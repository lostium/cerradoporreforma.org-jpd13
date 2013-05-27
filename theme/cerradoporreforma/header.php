<?php
header("Cache-Control: no-transform");
?><!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="utf-8" />
    <meta http-equiv="Cache-Control" content="no-transform" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0 maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<?php wp_head(); ?>

    <link href='http://fonts.googleapis.com/css?family=Oswald:400,700|Anton|Lobster|Electrolize' rel='stylesheet' type='text/css'>
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <div class="row-fluid">
                    <div class="span3 brand-wrapper">
                        <a class="brand" href="<?php echo site_url('/') ?>" title="Ir a portada">
                            <img src="<?php echo get_template_directory_uri() ?>/img/logo-cerrado.png" alt="Logo Cerrado">
                            Cerrado <i>por</i> Reforma <span>Documentando la destrucción de empresas en España</span>
                        </a>
                    </div>
                    <form class="span6 province-search-wrapper">
                        <input id="province-search" class="span10" type="text" data-toggle="tooltip" data-provider="typeahead" autocomplete="off" placeholder="España" value="<?php the_province()?>" > 
                    </form>
                    <a class="cpr-btn btn-upload span3" data-toggle="modal" data-target="#cerradoModal" href="#cerradoModal" title="Sube tu foto al mapa colaborativo"><i class="icon-camera-retro"></i> Sube tu foto al mapa</a>
                </div>
            </div>
        </div>
    </nav>