<?php
/**
 * Vista correspondiente al formulario encargado de subir una imagen
 *
 * @package   cerrado-por-reforma-plugin
 * @author    Lostium Project <found@lostium.com>
 * @license   GPL-2.0+
 * @link      http://cerradoporreforma.com
 * @copyright 2013 Lostium Project
 */
?>
<h3>Sube tu foto</h3>
<!-- Drop media element. -->
<div class="media-drop upload-step-1">
    <!-- Image placeholder. -->
    <div id="droppedimage"></div>
    <div id="dropbox" class="media-drop-placeholder" style="width: 300px; height: 300px">
        <span class="media-drop-placeholder-title"><?php _e('Arrastra una imagen aquí') ?></span>
        <span class="media-drop-placeholder-or"><?php _e('o') ?></span>
        <div class="media-drop-placeholder-uploadbutton">
            <?php /* Put hidden input[type=file] above ordinary button. */ ?>
            <input id="realUploadBtn" name="media-drop-placeholder-file" type="file" accept="image/*" tabindex="-1"/>
            <button id="uploadBtn" type="button" class="btn" tabindex="-1"><?php _e('Seleccionar fichero') ?>&hellip;</button>
        </div>
    </div>
</div>
<div class="upload-step-2 hide">
    <!-- Error message placeholder. -->
    <p class="help-block error errormessages"></p>
    <form class="cpr-image-upload-form">
        <h3><?php _e('Dinos dónde está') ?></h3>
        <label for="cpr-address"><?php _e('Dirección aproximada') ?></label>
        <input type="input" id="cpr-address" name="address" placeholder="<?php _e('Modifica aquí la dirección y presiona ENTER') ?>"/>
        <div id="map-upload-canvas" style="width:500px;height: 500px;"></div>
        <h3><?php _e('¿Tienes más información?') ?></h3>
        <label for="cpr-title"><?php _e('Título') ?></label>
        <input id="cpr-title" type="input" name="title" placeholder="<?php _e('Danos un titular') ?>"/>
        <label for="cpr-history">La historia</label>
        <textarea id="cpr-history" name="content" placeholder="<?php _e('Cuéntanos lo que sabes de este lugar...') ?>"></textarea>
    </form>
    <button id="upload-picture" type="button" class="btn"><?php _e('Enviar lugar') ?></button>
    <button id="resetupload" type="button hide" class="btn"><?php _e('Dar de alta otro lugar') ?></button>
</div>