<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>

	<div ng-app="myApp">
		<div ng-controller="orderForm">

			<div ng-controller="productCategory">
				<section class="main-app">
					
				</section>

				<section class="debug">

					<h5 class="debug">Debug</h5>

					<pre class="debug">{{itemData | json}}</pre>

				</section>

			</div>
		</div>
	</div>

<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
