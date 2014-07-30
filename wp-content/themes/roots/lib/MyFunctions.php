<?php
function make_est_post($testVar)
{
	 echo roots_title();
	PostCreator($testVar, 'page', 'This is a test page.');
     // $var = get_option('my_var');
     // echo json_encode($var);
     die();
}

if(!function_exists('PostCreator')) {
	function PostCreator(
		$name = 'AUTO POST',
		$type = 'post',
		$content = 'DUMMY CONTENT',
		$category = array(1,2),
		$template = NULL,
		$author_id = '1',
		$status = 'publish') {

		define(POST_NAME, $name);
		define(POST_TYPE, $type);
		define(POST_CONTENT, $content);
		define(POST_CATEGORY, $category);
		define(POST_TEMPLATE, '');
		define(POST_AUTH_ID, $author_id);
		define(POST_STATUS, $status);

		if ($type == 'page') {
			$post = get_page_by_title( POST_NAME, 'OBJECT', $type  );
			$post_id = $post->ID;
			$post_data = get_page($post_id);
			define(POST_TEMPLATE, $template);
		}
		else {
			$post = get_page_by_title( POST_NAME, 'OBJECT', $type  );
			$post_id = $post->ID;
			$post_data = get_post($post_id);
		}
		function hbt_create_post() {
			$post_data = array(
				'post_title'    => wp_strip_all_tags(POST_NAME),
				'post_content'  => POST_CONTENT,
				'post_status'   => POST_STATUS,
				'post_type'     => POST_TYPE,
				'post_author'   => POST_AUTH_ID,
				'post_category' => POST_CATEGORY,
				'page_template' => POST_TEMPLATE
			);
			wp_insert_post( $post_data, $error_obj );
		}
		if(!isset($post))
			add_action('admin_init', 'hbt_create_post' );
		return $error_obj;
	}
}

/* All available options for PostCreator()

PostCreator( 'TITLE' , 'POST TYPE' , 'POST CONTENT' , 'POST CATEGORY' , 'TEMPLATE FILE NAME' , 'AUTHOR ID NUMBER' , 'POST STATUS');

TITLE - HTML Stripped Out. Simple String.
POST TYPE - Post type slug. Eg 'post' or 'page'. Custom Post Types are supported.
POST CONTENT - Content of the Post/Page. HTML allowed.
POST CATEGORY - An array of the integer ID's  of the category/categories you want to link to your post
TEMPLATE FILE NAME - File name of the template. Only for Pages. In the format 'file_name.php'.
AUTHOR ID NUMBER - Integer value. Default is 1.
POST STATUS - Available options; [ 'draft' | 'publish' | 'pending'| 'future' | 'private' | custom registered status ]

If successful, PostCreator() returns nothing.
If there is an error PostCreator() returns a WP_error object.

*/

// PostCreator('My Lorem Ipsum', 'page', 'With a sizable serving of Dolor. This was created using Harri Bell-Thomas\'s PostCreator function.');


?>