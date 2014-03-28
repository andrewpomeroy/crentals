<?php
/**
 * Custom functions
 */

/* ----- Themeing ----- */

if (!is_page(9)) {
	
}

function my_scripts_init() {
	$scriptinitDir = get_bloginfo('template_directory').'assets/js/';
	if ( is_page_template('template-orderform.php')) {
			wp_enqueue_script('orderform', $scriptinitDir.'orderform.js', array('jquery'), false);
	}
}

add_action('wp_print_scripts', 'my_scripts_init');