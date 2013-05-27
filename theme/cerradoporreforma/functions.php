<?php

add_action('wp_enqueue_scripts', 'scriptsCerrado');
add_theme_support('post-thumbnails');
add_theme_support('excerpt');
add_filter('show_admin_bar', '__return_false');

//remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
//remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
//remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
//remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
//remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version

function scriptsCerrado() {

    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '1.0');
    wp_enqueue_style('cerrado-less', get_template_directory_uri() . '/css/cerrado.css', array(), '1.0');
    wp_enqueue_style('bootstrap-responsive', get_template_directory_uri() . '/css/responsive.css', array(), '1.0');


    wp_deregister_script('jquery');
    wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', array(), null, true);
    wp_register_script('jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js', array('jquery'), null, true);
    wp_register_script('google-jsapi', 'https://www.google.com/jsapi?key=AIzaSyDPBKirC56WlHkkHZyLMe10U8HT8TleA38', array(), null);
    wp_register_script('google-maps', '//maps.googleapis.com/maps/api/js?sensor=true&language=es&region=ES', array(), null, true);
    wp_register_script('google-maps-clusterer', get_template_directory_uri() . '/js/marker-clusterer-plus/markerclusterer_packed.js', array('google-maps'), '2.0.15', true);

    wp_enqueue_script('google-jsapi');
    wp_enqueue_script('google-maps');

    wp_enqueue_script('jquery');

    wp_enqueue_script('jquery-ui');

    wp_enqueue_script('google-maps-clusterer');

    wp_enqueue_script('hogan');

    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '2.3.0', true);
    wp_enqueue_script('cerrado', get_template_directory_uri() . '/js/cerrado.js', array('jquery'), '1.24', true);

    //array para variables javascript
    $cprVars = array('is_single' => is_single() && get_post_type(), 'is_archive'=>is_archive(), 'siteUrl'=>  site_url('/'));
    wp_localize_script('cerrado', 'cprVars', $cprVars);
}

function the_province() {

    if (is_single() && get_post_type()=='post') {
        $cerradoPorReforma = CerradoPorReforma::get_instance();
        $categories = get_the_category();
        if ($categories) {
            foreach ($categories as $category) {
                if ($category->parent == $cerradoPorReforma->get_province_category()){
                    echo esc_attr($category->name);
                    break;
                }
            }
        }
    }
}

/**
 * Id de la etiqueta con historia
 *
 * @return int
 */
function get_history_tag_id(){
    return 5;
//    $instance = CerradoPorReforma::get_instance();
//    return $instance->history_tag;
}


/**
 *  Install Add-ons
 *
 *  The following code will include all 4 premium Add-Ons in your theme.
 *  Please do not attempt to include a file which does not exist. This will produce an error.
 *
 *  All fields must be included during the 'acf/register_fields' action.
 *  Other types of Add-ons (like the options page) can be included outside of this action.
 *
 *  The following code assumes you have a folder 'add-ons' inside your theme.
 *
 *  IMPORTANT
 *  Add-ons may be included in a premium theme as outlined in the terms and conditions.
 *  However, they are NOT to be included in a premium / free plugin.
 *  For more information, please read http://www.advancedcustomfields.com/terms-conditions/
 */

// Fields
add_action('acf/register_fields', 'my_register_fields');

function my_register_fields()
{
	//include_once('add-ons/acf-repeater/repeater.php');
	//include_once('add-ons/acf-gallery/gallery.php');
	//include_once('add-ons/acf-flexible-content/flexible-content.php');
}

// Options Page
//include_once( 'add-ons/acf-options-page/acf-options-page.php' );


/**
 *  Register Field Groups
 *
 *  The register_field_group function accepts 1 array which holds the relevant data to register a field group
 *  You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
 */

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_custom-fields-negocio',
		'title' => 'Custom fields - Negocio',
		'fields' => array (
			array (
				'key' => 'field_519cee4e546e8',
				'label' => 'Ubicación',
				'name' => 'ubicacion',
				'type' => 'location-field',
				'val' => 'address',
				'center' => '48.856614,2.3522219000000177',
				'zoom' => 16,
				'scrollwheel' => 1,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_category',
					'operator' => '==',
					'value' => '2',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
