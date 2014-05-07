<?php
/**
 * Custom functions
 */

/* ----- Themeing ----- */

if (!is_page(9)) {
	
}

function my_scripts_init() {
	$scriptinitDir = get_bloginfo('template_directory').'/assets/js/';
	if ( is_page_template('template-orderform.php')) {
			// wp_enqueue_script('orderform', $scriptinitDir.'orderform.js', array('jquery'), false);
		wp_enqueue_script('angular', get_bloginfo('template_directory').'/bower_components/angular/angular.js', array('jquery'), false);
		wp_enqueue_script('mainController', $scriptinitDir.'/main-controller.js', array('angular'), false);
		wp_enqueue_script('uiBootstrap', get_bloginfo('template_directory').'/bower_components/angular-bootstrap/ui-bootstrap-tpls.js', array('angular'), false);

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