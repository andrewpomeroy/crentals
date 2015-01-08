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
// add_filter('show_admin_bar', '__return_false');

// Disable auto <p>
// http://stackoverflow.com/questions/7697465/wordpress-remove-auto-generated-paragraphs-for-speciffic-custom-post-type
remove_filter('the_content','wpautop');

//decide when you want to apply the auto paragraph

add_filter('the_content','my_custom_formatting');
function my_custom_formatting($content){
//     include 'ChromePhp.php';
// ChromePhp::log('Hello console!');
// ChromePhp::log($_SERVER);
// ChromePhp::warn('something went wrong!');
// PC::debug(has_category('10'));
if(has_category('10')) //if it does not work, you may want to pass the current post object to get_post_type
    return $content;//no autop
else
 return wpautop($content);
}

function my_scripts_init() {
	$scriptinitDir = get_bloginfo('template_directory').'/assets/js/';
    // wp_enqueue_script('phpvars', $scriptinitDir.'phpvars.js', array('jquery'), false);
    wp_register_script('jquery', $scriptinitDir.'vendor/jquery-1.11.0.min.js', array(), null, false);
    wp_enqueue_script('svginject', get_bloginfo('template_directory').'/bower_components/svg-injector/dist/svg-injector.min.js', array('jquery'), false);

    function add_google_analytics() {
        wp_register_script('ga', $scriptinitDir.'ga.js', array(), 1.0, false);
        wp_enqueue_script('ga');
    }
    add_action('wp_enqueue_scripts', 'add_google_analytics');


    if ( (is_page_template('template-orderform.php') || is_page_template('template-category.php')) || ( 'estimate' == get_post_type()) ) {
        wp_enqueue_script('angular', get_bloginfo('template_directory').'/bower_components/angular/angular.js', array('jquery'), false);
        wp_enqueue_script('uiBootstrap', get_bloginfo('template_directory').'/bower_components/angular-bootstrap/ui-bootstrap-tpls.js', array('angular'), false);
    }
    if ( is_page_template('template-orderform.php') || is_page_template('template-category.php') || ( 'estimate' == get_post_type()) ) {
        wp_enqueue_script('mainController', $scriptinitDir.'main-controller.js', array('angular'), false);
    }
    if ( is_page_template('template-orderform.php') ) {
        wp_enqueue_script('estimateController', $scriptinitDir.'estimate-controller.js', array('angular'), false);
    }
    if ( 'estimate' == get_post_type()) {
        wp_enqueue_script('estimateSingleController', $scriptinitDir.'estimate-single-controller.js', array('mainController'), false);
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

add_action('init','add_category_to_estimate');
function add_category_to_estimate() {
    register_taxonomy_for_object_type('category','estimate');
}

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
        'taxonomies' => array('category'),
        'supports' => array('title','editor','thumbnail')
      ); 

    register_post_type( 'estimate' , $args );
}



// 'Estimate' Post type gets proper template
// add_filter('roots_wrap_base', 'roots_wrap_base_cpts'); // Add our function to the roots_wrap_base filter

// function roots_wrap_base_cpts($templates) {
// $cpt = get_post_type(); // Get the current post type
// if ($cpt) {
//    array_unshift($templates, 'base-' . $cpt . '.php'); // Shift the template to the front of the array
// }
// return $templates; // Return our modified array with base-$cpt.php at the front of the queue
// }

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

function wpse_allowedtags() {
    // Add custom tags to this string
        return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>'; 
    }

if ( ! function_exists( 'wpse_custom_wp_trim_excerpt' ) ) : 

    function wpse_custom_wp_trim_excerpt($wpse_excerpt) {
    $raw_excerpt = $wpse_excerpt;
        if ( '' == $wpse_excerpt ) {

            $wpse_excerpt = get_the_content('');
            $wpse_excerpt = strip_shortcodes( $wpse_excerpt );
            $wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
            $wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
            $wpse_excerpt = strip_tags($wpse_excerpt, wpse_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */

            //Set the excerpt word count and only break after sentence is complete.
                $excerpt_word_count = 75;
                $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 
                $tokens = array();
                $excerptOutput = '';
                $count = 0;

                // Divide the string into tokens; HTML tags, or words, followed by any whitespace
                preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);

                foreach ($tokens[0] as $token) { 

                    if ($count >= $excerpt_length && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) { 
                    // Limit reached, continue until , ; ? . or ! occur at the end
                        $excerptOutput .= trim($token);
                        break;
                    }

                    // Add words to complete sentence
                    $count++;

                    // Append what's left of the token
                    $excerptOutput .= $token;
                }

            $wpse_excerpt = trim(force_balance_tags($excerptOutput));

                $excerpt_end = ' <a href="'. esc_url( get_permalink() ) . '">' . '&nbsp;&raquo;&nbsp;' . sprintf(__( 'Read more about: %s &nbsp;&raquo;', 'wpse' ), get_the_title()) . '</a>'; 
                $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end); 

                //$pos = strrpos($wpse_excerpt, '</');
                //if ($pos !== false)
                // Inside last HTML tag
                //$wpse_excerpt = substr_replace($wpse_excerpt, $excerpt_end, $pos, 0); /* Add read more next to last word */
                //else
                // After the content
                // $wpse_excerpt .= $excerpt_more; /*Add read more in new paragraph */

            return $wpse_excerpt;   

        }
        return apply_filters('wpse_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
    }

endif; 

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wpse_custom_wp_trim_excerpt'); 