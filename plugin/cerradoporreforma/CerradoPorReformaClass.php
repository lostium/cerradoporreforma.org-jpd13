<?php

/**
 * Cerrado Por Reforma Plugin
 *
 * @package   CerradoPorReforma
 * @author    Lostium Project <found@lostium.com>
 * @license   GPL-2.0+
 * @link      http://cerradoporreforma.com
 * @copyright 2013 Lostium Project
 */

/**
 * CerradoPorReforma
 *
 * @package CerradoPorReforma
 * @author    Lostium Project <found@lostium.com>
 */
class CerradoPorReforma {

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var     string
     */
    protected $version = '1.0.0';

    /**
     * Unique identifier for your plugin.
     *
     * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
     * match the Text Domain file header in the main plugin file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_slug = 'cerrado-por-reforma';

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Tipos admitidos en el upload
     *
     * @since    1.0.0
     *
     * @var      array
     */
    protected $tipos_admitidos = null;

    /**
     * Tamaño máximo admitido por fichero
     *
     * @since    1.0.0
     *
     * @var      int
     */
    protected $tamano_maximo = null;

    /**
     * Categoría elementos cerrados
     *
     * @since    1.0.0
     *
     * @var      array
     */
    protected $closed_category = null;

    /**
     * Categoría provincia
     *
     * @since    1.0.0
     *
     * @var      array
     */
    protected $province_category = null;

     /**
     * Etiqueta con historia
     *
     * @since    1.0.0
     *
     * @var      array
     */
    protected $history_tag = null;


    /**
     * Post status
     *
     * @since    1.0.0
     *
     * @var      array
     */
    protected $default_post_status = null;

    /**
     * Initialize the plugin by setting localization, filters, and administration functions.
     *
     * @since     1.0.0
     */
    private function __construct() {
        //tipos admitidos
        $this->tipos_admitidos = array('image/jpeg', 'image/png', 'image/gif');

        //límite 1mb
        $this->tamano_maximo = 1048576;

        $this->closed_category = 3;
        $this->province_category = 4;
        $this->history_tag = 5;


        $this->default_post_status = 'publish';

        // Load plugin text domain
        add_action('init', array($this, 'load_plugin_textdomain'));

        // Add the options page and menu item.
        // add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));



        // Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
        //add_action( 'TODO', array( $this, 'action_method_name' ) );
        //add_filter( 'TODO', array( $this, 'filter_method_name' ) );

        add_shortcode('form_upload_image', function($attr) {
                    //captura de la respuesta
                    ob_start();
                    if (is_user_logged_in()) {
                        include_once( 'views/form.php' );
                    } else {
                        do_action('wordpress_social_login');
                    }
                    //obtenemos el contenido del formulario
                    $form = ob_get_contents();
                    //limpiamos el buffer
                    ob_end_clean();
                    return $form;
                });
        //registramos el upload de ficheros
        add_action('wp_ajax_cpr_upload', array($this, 'gestionar_upload'));

        //Capturamos las peticiones para las urls custom
        add_action('parse_request', array(&$this, 'processCustomUrls'));
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    CerradoPorReforma    A single instance of this class.
     */
    public static function &get_instance() {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function processCustomUrls($data) {

        if (isset($data->request)) {
            if (preg_match('%^api/negocios\.json$%', $data->request)) {
                $this->get_pois();
                die();
            }
        }
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
     */
    public static function activate($network_wide) {
        // TODO: Define activation functionality here
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
     */
    public static function deactivate($network_wide) {
        // TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, dirname(plugin_basename(__FILE__)) . '/lang/');
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->id == $this->plugin_screen_hook_suffix) {
            wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('css/admin.css', __FILE__), $this->version);
        }
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->id == $this->plugin_screen_hook_suffix) {
            wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('js/admin.js', __FILE__), array('jquery'), $this->version);
        }
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        //wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('css/public.css', __FILE__), $this->version);
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        //wp_enqueue_script($this->plugin_slug . '-google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true', null, null);
        wp_enqueue_script($this->plugin_slug . '-exif', plugins_url('js/exif.js', __FILE__), array('jquery'), $this->version);
        wp_enqueue_script($this->plugin_slug . '-jsMVC', plugins_url('js/jsMVC.js', __FILE__), array('jquery'), $this->version);
        wp_enqueue_script($this->plugin_slug . '-html5-upload', plugins_url('js/jquery.html5uploader-1.1-lostium.js', __FILE__), array('jquery'), $this->version);
        wp_enqueue_script($this->plugin_slug . '-plugin-script', plugins_url('js/public.js', __FILE__), array('jquery'), $this->version);
        //incorporamos el punto de acceso ajax
        wp_localize_script($this->plugin_slug . '-plugin-script', 'cprImageUploadVars', array('ajaxUrl' => admin_url('admin-ajax.php')));
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        /*
         * TODO:
         *
         * Change 'Page Title' to the title of your plugin admin page
         * Change 'Menu Text' to the text for menu item for the plugin settings page
         * Change 'plugin-name' to the name of your plugin
         */
        $this->plugin_screen_hook_suffix = add_plugins_page(
                __('Cerrado por reforma', $this->plugin_slug), __('Administración', $this->plugin_slug), 'read', $this->plugin_slug, array($this, 'display_plugin_admin_page')
        );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        include_once( 'views/admin.php' );
    }

    /**
     * Gestiona los uploads de los usuarios
     *
     * @since    1.0.0
     */
    public function gestionar_upload() {

        $retorno = array();
        if (is_user_logged_in() && isset($_FILES['image'])) {
            global $current_user;
            $file = $_FILES['image'];

            /**
             * Hack para firefox, no manda el nombre del fichero.
             */
            //comprobamos si hemos recibido el fichero
            if ($file['error']) {
                echo json_encode(array('error' => __('No has seleccionado ningún fichero o se ha producido un error al subirlo', $this->plugin_slug)));
                die();
            }
            //obtenemos la información relativa a la imagen
            $info_imagen = getimagesize($file['tmp_name']);
            if (!in_array($info_imagen['mime'], $this->tipos_admitidos)) {
                echo json_encode(array('error' => __('Por ahora sólo soportamos jpeg, png y gif', $this->plugin_slug)));
                die();
            }

            //comprobamos el tamaño
            if ($file['size'] > $this->tamano_maximo) {
                echo json_encode(array('error' => sprintf(__('Has sobrepasado el tamaño máximo permitido (%d mb), tu fichero ocupa: %d mb. ', $this->plugin_slug), round($this->tamano_maximo / 1048576), round($file['size'] / 1048576))));
                die();
            }

            //limpiamos todos los campos
            //TODO validarlos
            $title = sanitize_text_field($_POST['title']);
            $content = trim(esc_textarea($_POST['content']));
            $lat = floatval($_POST['lat']);
            $lng = floatval($_POST['lng']);
            $address = sanitize_text_field($_POST['address']);
            $province = sanitize_text_field($_POST['province']);

            if (empty($title)) {
                $title = __("Negocio cerca de ") . $address;
            }

            $user_image_data = array(
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'draft',
                'post_author' => $current_user->ID
            );
            //renombramos el fichero
            $file['name'] = ($title) . '.jpg';
            $_FILES['image'] = $file;


            //insertamos el post
            if ($post_id = wp_insert_post($user_image_data)) {

                //creamos la provincia, si ya existe nos devuelve su id
                $province_id = wp_create_category($province, $this->province_category);

                //establecemos la categoría negocio cerrado y el id de la provincia
                wp_set_post_terms($post_id, array($this->closed_category, $province_id), 'category');
                //si el usuario ha añadido contenido a la foto, marcar que tiene historia
                if (!empty($content)) {
                    wp_set_post_terms($post_id, "con historia", 'post_tag');
                }

                //actualizamos la posición
                update_field('field_519cee4e546e8', str_replace('|', ' ', $address) . '|' . $lat . ',' . $lng, $post_id);

                $this->process_image('image', $post_id, $title);

                $post = array();
                $post['ID'] = $post_id;
                $post['post_status'] = $this->default_post_status;

                // Update the post into the database
                wp_update_post($post);

                $retorno['post_id'] = $post_id;
                $retorno['permalink'] = get_permalink($post_id);
            }
        } else {
            $retorno['error'] = __('Se ha producido un error, vuelva a intentarlo pasados unos minutos');
        }
        header('Content-type: application/json');
        echo json_encode($retorno);

        die();
    }

    /**
     * Procesa una imagen recibida por post la almacena y la asocia al post
     *
     * @since    1.0.0
     */
    private function process_image($file, $post_id, $caption) {

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        $attachment_id = media_handle_upload($file, $post_id);

        update_post_meta($post_id, '_thumbnail_id', $attachment_id);

        $attachment_data = array(
            'ID' => $attachment_id,
            'post_excerpt' => $caption
        );

        wp_update_post($attachment_data);

        return $attachment_id;
    }

    /**
     * Obtiene el identificador de la categoría de provincias
     *
     * @since    1.0.0
     *
     */
    public function get_province_category() {
        return $this->province_category;
    }

    /**
     * Obtiene los negocios en formato JSON
     *
     * @since    1.0.0
     * @todo cachear esta petición
     */
    private function get_pois() {
        $retorno = array();
        $the_query = new WP_Query(
                array(
            'category__in' => array($this->closed_category),
            'posts_per_page' => -1
                )
        );

        while ($the_query->have_posts()) :
            $the_query->the_post();
            $id = get_the_ID();

            $businessLocation = get_field('ubicacion');
            if ($businessLocation) {
                $businessCoords = split(',', $businessLocation['coordinates']);

                $item = array();
                $item['id'] = $id;
                $item['title'] = get_the_title();
                $item['lat'] = floatval($businessCoords[0]);
                $item['lng'] = floatval($businessCoords[1]);
                $item['permalink'] = get_permalink();
                $item['province'] = $this->get_province();

                $htmlThumb = get_the_post_thumbnail($id, 'medium');
                if (preg_match('/\b(https?:\/\/[^"]+)/i', $htmlThumb, $regs)) {
                    $item['image'] = $regs[1];
                }

                $retorno[] = $item;
            }
        endwhile;

        header('Content-type: application/json');
        echo json_encode($retorno);
    }

    /**
     * Obtiene la provincia de un post
     *
     * @return string nombre de la provincia
     */
    private function get_province() {
        $province_category = $this->get_province_category();
        $categories = get_the_category();
        if ($categories) {
            foreach ($categories as $category) {
                if ($category->parent == $province_category) {
                    return esc_attr($category->name);
                }
            }
        }

        return '';
    }

}