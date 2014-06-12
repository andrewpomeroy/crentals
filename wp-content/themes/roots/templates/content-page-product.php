<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>
	<div ng-app="myApp">
		<div ng-controller="mainCtrl">

			<div ng-controller="productCategory">
				<section class="main-app category-page" get-category="<?php echo get_field('category_id');?>"> 
					
					<div class="product-list">
						<div class="Media product" ng-repeat="product in productGroup.items" ng-class="{'opens-page clickable': product.image}">
							<img class="Media-figure product image" thumb-src="{{product.image}}" ng-if="product.image">
							<span class="Media-figure placeholder product image" ng-if="!product.image"></span>
							<div class="Media-body">
								<h4 class="heading product-heading">{{product.name}}</h4>
								<p class="product-description">{{product.description}}</p>
							</div>
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
