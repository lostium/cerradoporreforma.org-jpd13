<?php
get_header();

if (have_posts())
    the_post();

$businessLocation = get_field('ubicacion');
$businessCoords = split(',',$businessLocation['coordinates']);

?>
<section id="map-wrapper" class="container-fluid">
    <div class="row-fluid">
        <div id='map' class="span12">
            <div class="map-loading">
                <p>Cargando mapa...</p>
            </div>
        </div>
    </div>
</section>

<article class="business" data-lat="<?php echo esc_attr($businessCoords[0])?>" data-lng="<?php echo esc_attr($businessCoords[1])?>">
    <div class="container-fluid single-photo">
        <?php edit_post_link(); ?>
        <div class="container">
            <div class="row-fluid">
                <div class="span10 offset1 last-story-text">
                    <div class="business-photo">
                        <?php the_post_thumbnail('large'); ?>
                        <address>
                            <i class="icon-map-marker"></i> <?php echo $businessLocation['address']; ?>
                        </address>
                    </div>
                    <h1><a href="<?php echo get_permalink(); ?>" title=""><i class="<?php if (!strncmp(get_the_title(),"Negocio cerca de", 16)){ echo "icon-map-marker"; } else { echo "icon-quote-left"; } ?> icon-2x pull-left"></i><?php the_title() ?></a></h1>
                    <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                        <div class="flipper">
                            <div class="front">
                                <a class="cpr-avatar" href="<?php the_author_url(); ?>" title="Foto e historia de <?php echo get_the_author_firstname(); ?>"><?php echo get_avatar( get_the_author_meta('ID')); ?></a>
                            </div>
                            <div class="back">
                                <a href="<?php the_author_url(); ?>" title="Foto e historia de <?php echo get_the_author_firstname(); ?>" ><span>Por:</span> <?php echo get_the_author(); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php $content = get_the_content(); ?>
                    <?php if (!empty($content)) { ?>
                        <div class="last-story-full">
                            <?php echo $content ?>
                        </div>
                    <?php } ?>    
                </div>
            </div>
        </div>
     
    </div>
</article>

<?php get_footer(); ?> 