<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>

<?php
$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null, 'orderby' => 'rand', 'post_parent' => null);
			$theattachments = get_posts($args);
			if ($theattachments) {
				echo '';
				// count number of available images and change if less than the specified limit
				foreach ($theattachments as $post) {
						setup_postdata($post);
						$image = wp_get_attachment_image_src( $post->ID, 'thumbnail', false );
						?>
						<div class="demo-image">
							<a class="demo-image__img-wrap" href="<?php echo $image[0]?>" target="_blank">
								<img class="demo-image__img" src="<?php echo $image[0] ?>">
							</a>
							<p class="demo-image__title"><?php get_the_title() ?></p>
							<textarea class="demo-image__url copy-this-url" onclick="this.focus();this.select()" readonly="readonly"><?php echo wp_get_attachment_image_src( $post->ID, 'full', false )[0]; ?></textarea>
							<a class="demo-image__admin-link" href="<?php echo get_site_url() ?>/wp-admin/post.php?post=<?php echo $post->ID ?>&action=edit">Admin link</a>
						</div>
						<?php
				}
				echo '';
			}

			wp_reset_query();
				?>

<?php endwhile; ?>