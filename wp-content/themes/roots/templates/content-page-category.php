<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>
	<div ng-app="myApp">
		<div ng-controller="mainCtrl">

			<div ng-controller="productCategory">
				<section class="main-app" get-category="<?php echo get_field('category_id');?>"> 
					
					<div class="product-list">
						<div class="product" ng-repeat="product in productList">
							<h4>{{product.name}}</h4>
							<img thumb-src="{{product.image}}">
							<p class="product-description">{{product.description}}</p>
						</div>
					</div>

				</section>

				<section class="debug">

					<h5 class="debug">Products</h5>

					<pre class="debug">{{products | json}}</pre>
					<h5 class="debug">Debug</h5>

					<pre class="debug">{{itemData | json}}</pre>

				</section>

			</div>
		</div>
	</div>

<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
