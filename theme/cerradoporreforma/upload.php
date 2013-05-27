<?php
/**
 * Vista correspondiente al formulario encargado de subir una imagen
 *
 * @package   cerrado-por-reforma-theme
 * @author    Lostium Project <found@lostium.com>
 * @license   GPL-2.0+
 * @link      http://cerradoporreforma.com
 * @copyright 2013 Lostium Project
 */
?>
<?php if(is_user_logged_in()){?>
<p>¿Tienes una foto de un negocio cerrado? ¿Conoces su historia? Este es tu sitio:</p>
<!-- Drop media element. -->
<div class="media-drop upload-step-1">
    <!-- Image placeholder. -->
    <div id="droppedimage"></div>
    <div id="dropbox" class="media-drop-placeholder">
        <span class="media-drop-placeholder-title"><?php _e('Arrastra una imagen aquí') ?></span>
        <span class="media-drop-placeholder-or"><?php _e('o') ?></span>
        <div class="media-drop-placeholder-uploadbutton">
            <?php /* Put hidden input[type=file] above ordinary button. */ ?>
            <input id="realUploadBtn" name="media-drop-placeholder-file" type="file" accept="image/*" tabindex="-1"/>
            <a href="javascript:void(0);" id="uploadBtn" class="cpr-btn select-photo-btn" tabindex="-1"><i class="icon-picture"></i> <?php _e('Seleccionar foto') ?>&hellip;</a>
        </div>
    </div>
</div>
<div class="upload-step-2 hide">
    <!-- Error message placeholder. -->
    <p class="help-block error errormessages"></p>
    <form class="cpr-image-upload-form">
        <h3><?php _e('Algunos datos más...') ?></h3>
        <label for="cpr-address"><?php _e('La dirección es aproximada. Corrígela si es necesario:') ?></label>
        <input type="text" id="cpr-address" name="address" placeholder="<?php _e('Introduce aquí la dirección y presiona ENTER') ?>"/>
        <div id="map-upload-canvas"></div>
        <h3><?php _e('¿Quieres aportar más información?') ?></h3>
        <label for="cpr-title"><?php _e('Título') ?></label>
        <input id="cpr-title" type="text" name="title" placeholder="<?php _e('Danos un titular') ?>"/>
        <label for="cpr-history">La historia detrás de este sitio</label>
        <textarea id="cpr-history" name="content" rows="6" maxlength="10000" placeholder="<?php _e('Cuéntanos lo que sabes de este lugar...') ?>"></textarea>
    </form>
    <a id="upload-picture" class="cpr-btn" href="javascript:void(0);" data-label-uploading="&lt;i class=&quot;icon-spinner icon-spin&quot;&gt;&lt;/i&gt; <?php _e('Enviando lugar') ?>" data-label-upload="&lt;i class=&quot;icon-spinner&quot;&gt;&lt;/i&gt;<?php _e('Enviar lugar') ?>"><i class="icon-check-sign"></i> <?php _e('Enviar lugar') ?></a>
</div>
<?php }else{ ?>
<div class="social-login">
    <?php do_action('wordpress_social_login');?>
</div>
<?php } ?>