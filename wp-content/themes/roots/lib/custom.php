<?php
/**
 * Custom functions
 */

/* Settings / Flags */

/* ----- Advanced Custom Fields - activate Options screen ----- */
acf_add_options_page();

/* ----- Themeing ----- */

// // Import webfonts

// function myfonts_css() {
//   wp_enqueue_style('myfonts', get_template_directory_uri() . '/assets/css/myfonts.css', false);
// }
// add_filter('wp_print_styles', 'myfonts_css');

function typekit_js() {
    wp_enqueue_script('typekit', "//use.typekit.net/wwx3lml.js", false );
}
add_filter('wp_print_scripts', 'typekit_js');

function typekit_try() { 
    ?>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
    <?php
}
add_filter('wp_head', 'typekit_try');

function php_vars_go() {
    ?>
    <script type="text/javascript">
        var globalGSUrl = '<?php echo get_field('product_spreadsheet_url', 'option') ?>';
    </script>
    <?php // echo print_r(get_fields('option'));
}
add_filter('wp_head', 'php_vars_go');

// function roots_scripts() {

//   // jQuery is loaded using the same method from HTML5 Boilerplate:
//   // Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
//   // It's kept in the header instead of footer to avoid conflicts with plugins.
// add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);

// Hide Admin Bar
add_filter('show_admin_bar', '__return_false');

// Disable auto <p>
// TODO:
// http://stackoverflow.com/questions/7697465/wordpress-remove-auto-generated-paragraphs-for-speciffic-custom-post-type
remove_filter('the_content','wpautop');

function my_scripts_init() {
	$scriptinitDir = get_bloginfo('template_directory').'/assets/js/';
    // wp_enqueue_script('phpvars', $scriptinitDir.'phpvars.js', array('jquery'), false);
    wp_register_script('jquery', $scriptinitDir.'vendor/jquery-1.11.0.min.js', array(), null, false);
    wp_enqueue_script('svginject', get_bloginfo('template_directory').'/bower_components/svg-injector/dist/svg-injector.min.js', array('jquery'), false);

    if ( (is_page_template('template-orderform.php') || is_page_template('template-category.php')) ) {
        wp_enqueue_script('angular', get_bloginfo('template_directory').'/bower_components/angular/angular.js', array('jquery'), false);
        wp_enqueue_script('uiBootstrap', get_bloginfo('template_directory').'/bower_components/angular-bootstrap/ui-bootstrap-tpls.js', array('angular'), false);
    }
    if ( is_page_template('template-orderform.php') || is_page_template('template-category.php') ) {
        wp_enqueue_script('mainController', $scriptinitDir.'main-controller.js', array('angular'), false);
    }
    if ( is_page_template('template-orderform.php') ) {
        wp_enqueue_script('estimateController', $scriptinitDir.'estimate-controller.js', array('angular'), false);
    }
    if ( is_page_template('template-category.php')) {
            // wp_enqueue_script('orderform', $scriptinitDir.'orderform.js', array('jquery'), false);
        // wp_register_script('jquery', $scriptinitDir.'vendor/jquery-1.11.0.min.js', array(), null, false);
        wp_enqueue_script('categoryController', $scriptinitDir.'category-controller.js', array('mainController'), false);
    }
    if (is_single() && !is_page_template()) {
		wp_enqueue_script('QTObject', $scriptinitDir.'qtobject.js', array('jquery'), false);
	}
}

add_action('wp_print_scripts', 'my_scripts_init');

// -- Ajax -- //



// Add PHP functions for AJAX
$dirName = dirname(__FILE__);
$baseName = basename(realpath($dirName));
require_once ("$dirName/MyFunctions.php");

add_action("wp_ajax_nopriv_make_est_post", "make_est_post");
add_action("wp_ajax_make_est_post", "make_est_post");

// Init
function add_ajax() 
{
     wp_enqueue_script( 'ajax_post_function', get_template_directory_uri().'/assets/js/ajax_test.js', array('jquery'), true);
     wp_localize_script( 'ajax_post_function', 'my_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('template_redirect', 'add_ajax');

// CUSTOM IMAGE THUMBNAIL SIZES

if ( function_exists( 'add_image_size' ) ) { 
    add_image_size( 'width_960', 960, 9999 ); //960 pixels wide (and unlimited height)
}
if ( function_exists( 'add_image_size' ) ) { 
    add_image_size( 'width_1280', 1280, 9999 ); //1280 pixels wide (and unlimited height)
}
if ( function_exists( 'add_image_size' ) ) { 
    add_image_size( 'width_1920', 1920, 9999 ); //1920 pixels wide (and unlimited height)
}
if ( function_exists( 'add_image_size' ) ) { 
    add_image_size( 'width_800', 800, 9999 ); //800 pixels wide (and unlimited height)
}
if ( function_exists( 'add_image_size' ) ) { 
    add_image_size( 'width_1600', 1600, 9999 ); //1600 pixels wide (and unlimited height)
}
if ( function_exists( 'add_image_size' ) ) { 
    add_image_size( 'width_640', 640, 9999 ); //640 pixels wide (and unlimited height)
}

// CUSTOM POST TYPE CREATE

// Review Post type

add_action('init', 'estimate_posttype');

function estimate_posttype() {

    $labels = array(
        'name' => _x('Estimates', 'post type general name'),
        'singular_name' => _x('Estimate', 'post type singular name'),
        'add_new' => _x('Add New', 'estimate'),
        'add_new_item' => __('Add New Estimate'),
        'edit_item' => __('Edit Estimate'),
        'new_item' => __('New Estimate'),
        'view_item' => __('View Estimate'),
        'search_items' => __('Search Estimate'),
        'not_found' =>  __('Nothing found'),
        'not_found_in_trash' => __('Nothing found in Trash'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'menu_icon' => null,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 10,
        'supports' => array('title','editor','thumbnail')
      ); 

    register_post_type( 'estimate' , $args );
}

add_action('init','add_category_to_estimate');
function add_category_to_estimate() {
    register_taxonomy_for_object_type('category','estimate');
}


function livereload_script() { ?>
<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
<?php }
// add_filter( 'wp_footer', 'livereload_script' );

function svg_inject_script() { ?>
<script>
var mySVGsToInject = document.querySelectorAll('img.svg-inject');
SVGInjector(mySVGsToInject);
</script>


<?php }
add_filter( 'wp_footer', 'svg_inject_script' );

function home_title_option($classes) {
    if (is_front_page()) {
        $classes[] = 'home';
        $classes[] = 'home_title_'.get_field('home_page_title', 'option');
    }
    return $classes;
}
add_filter('body_class', 'home_title_option');
