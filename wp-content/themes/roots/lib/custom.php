<?php
/**
 * Custom functions
 */

/* ----- Themeing ----- */

if (!is_page(9)) {
	
}

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

// function roots_scripts() {

//   // jQuery is loaded using the same method from HTML5 Boilerplate:
//   // Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
//   // It's kept in the header instead of footer to avoid conflicts with plugins.
// add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);

// Hide Admin Bar
add_filter('show_admin_bar', '__return_false');

function my_scripts_init() {
	$scriptinitDir = get_bloginfo('template_directory').'/assets/js/';
    if ( (is_page_template('template-orderform.php') || is_page_template('template-category.php')) ) {
        wp_enqueue_script('angular', get_bloginfo('template_directory').'/bower_components/angular/angular.js', array('jquery'), false);
        wp_enqueue_script('uiBootstrap', get_bloginfo('template_directory').'/bower_components/angular-bootstrap/ui-bootstrap-tpls.js', array('angular'), false);
    }
	if ( is_page_template('template-orderform.php')) {
			// wp_enqueue_script('orderform', $scriptinitDir.'orderform.js', array('jquery'), false);
        // wp_register_script('jquery', $scriptinitDir.'/vendor/jquery-1.11.0.min.js', array(), null, false);
		wp_enqueue_script('mainController', $scriptinitDir.'/main-controller.js', array('angular'), false);
	}
    if ( is_page_template('template-category.php')) {
            // wp_enqueue_script('orderform', $scriptinitDir.'orderform.js', array('jquery'), false);
        // wp_register_script('jquery', $scriptinitDir.'/vendor/jquery-1.11.0.min.js', array(), null, false);
        wp_enqueue_script('categoryController', $scriptinitDir.'/category-controller.js', array('angular'), false);
    }
}

add_action('wp_print_scripts', 'my_scripts_init');

// -- Ajax -- //

// Init
function add_ajax() 
{

     wp_localize_script( 'function', 'my_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}

add_action( 'admin_ajax_your_form_action', 'wpse_126886_ajax_handler' );

function wpse_126886_ajax_handler() {

    // // maybe check some permissions here, depending on your app
    // if ( ! current_user_can( 'edit_posts' ) )
    //     exit;

    $post_data = array();
    //handle your form data here by accessing $_POST

    $new_post_ID = wp_insert_post( $post_data );

    // send some information back to the javascipt handler
    $response = array(
        'status' => '200',
        'message' => 'OK',
        'new_post_ID' => $new_post_ID
    );

    // // normally, the script expects a json respone
    header( 'Content-Type: application/json; charset=utf-8' );
    echo json_encode( $response );

    exit; // important
}

function livereload_script() { ?>
<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
<?php }
add_filter( 'wp_footer', 'livereload_script' );