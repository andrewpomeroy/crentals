<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>

	<div ng-app="myApp">
		<div ng-controller="productCategory">
			<section class="main-app">
				
			</section>
		</div>
	</div>

<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
