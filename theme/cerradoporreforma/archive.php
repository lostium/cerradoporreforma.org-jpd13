<?php
get_header();
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
<section id='business-list' class="container-fluid archive-photos">
    <div class="container">
        <div class="row-fluid filter-stories clearfix">
            <div class="span11">
                <a class="cpr-btn full-story-btn" href="<?php echo get_tag_link(get_history_tag_id()); ?>" title="Mostrar sólo negocios con historia"><i class="icon-ellipsis-horizontal"></i> Mostrar sólo negocios con historia</a>
            </div>
        </div>    
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="row-fluid">
            <article class="last-story clearfix">
                <?php edit_post_link(); ?>
                <div class="span3 offset1">
                    <a href="<?php echo get_permalink(); ?>" title=""><?php the_post_thumbnail('medium'); ?></a>
                </div>
                <div class="span7 last-story-text">
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
                        <div class="last-story-excerpt">
                            <?php echo the_excerpt(); ?>
                            <a class="cpr-btn full-story-btn" href="<?php echo get_permalink(); ?>" title="Ver foto y leer toda la historia"><i class="icon-ellipsis-horizontal"></i> Ver historia completa</a>
                        </div>
                    <?php } ?>    
                </div>
            </article>
        </div>
        <?php endwhile;?>
        <?php previous_posts_link()?> <?php next_posts_link()?>
        <?php else: ?>

        <p>No hemos encontrado negocios con este criterio de búsqueda</p>

        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>