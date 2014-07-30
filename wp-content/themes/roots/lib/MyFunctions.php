<?php

/**
 * A function used to programmatically create a post in WordPress. The slug, author ID, and title
 * are defined within the context of the function.
 *
 * @returns -1 if the post was never created, -2 if a post with the same title exists, or the ID
 *          of the post if successful.
 */
function make_est_post() {

	// Initialize the page ID to -1. This indicates no action has been taken.

	$post_id = -1;

	// Setup the author, slug, and title for the post
	$author_id = 1;
	$slug = -1;
	$title = $_POST['title'];

	$response = array(
    'status' => '200',
    'message' => 'Dunno',
    'new_post_ID' => $post_id
	);

	$response['titleget'] = get_page_by_title(
	array(
		'page_title' => $title,
		'post_type' => 'post'
		)
	);

	// If the page doesn't already exist, then create it
	if ( get_page_by_title(array('page_title' => $title, 'post_type' => 'post')) == null ) {
		// Set the post ID so that we know the post was created successfully
		$post_id = wp_insert_post(
			array(
				'comment_status'	=>	'closed',
				'ping_status'		=>	'closed',
				'post_author'		=>	$author_id,
				'post_name'		=>	$slug,
				'post_title'		=>	$title,
				'post_status'		=>	'publish',
				'post_type'		=>	'post'
			)
		);

		$response['message'] = 'OK';
		$response['new_post_ID'] = $post_id;

	// Otherwise, we'll stop
	} else {

    		// Arbitrarily use -2 to indicate that the page with the title already exists
    		$post_id = -2;


	} // end if

	header( 'Content-Type: application/json; charset=utf-8' );
	echo json_encode( $response );
	exit;

} // end programmatically_create_post
// add_filter( 'after_setup_theme', 'programmatically_create_post' );

?>