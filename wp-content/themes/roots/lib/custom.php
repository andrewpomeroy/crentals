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