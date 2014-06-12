<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>
	<?php
		$category = get_field('category_id');
		$children = get_posts(array('orderby' => 'menu_order', 'order' => 'ASC','showposts' => 1, 'post_parent' => $post->ID, 'post_type' => 'page'));
		$childrenIDs = [];
		foreach ($children as $key => $apost) {
			$childrenIDs[] = get_field('product_id', $apost->ID);
		};
	?>
	<div ng-app="myApp">
		<div ng-controller="mainCtrl">

			<div ng-controller="productCategory">
				<form name="secrat" id="secrat">
					<input type="hidden" id="secret-children" name="secret-children" value="<?php echo implode(",", $childrenIDs)?>" get-product-ids />
					<input type="hidden" id="secret-category" name="secret-category" value="<?php echo $category ?>" get-category="<?php echo $category ?>" />
				</form>
				<div class="main-app category-page">

					<div class="product-pages">
						<ul class="product-list product-page-list">
							<li ng-repeat="product in productPageObject" class="Media product product-page-feature clickable">
								<a href class="Media-figure product image-wrap">
									<img thumb-src="{{product.image}}" ng-if="product.image">
									<span class="Media-figure placeholder product image" ng-if="!product.image"></span>
								</a>
								<a href class="Media-body">
									<h4 class="heading product-heading">{{product.name}}</h4>
									<p class="product-description">{{product.description}}</p>
								</a>
							</li>
						</ul>
					</div>
					<div class="products">
						<ul class="product-list">
							<li class="Media product" ng-repeat="product in productGroup.items" ng-class="{'opens-page clickable': product.image}" ng-if="!hasProductPage(product.id)">
								<img class="Media-figure product image" thumb-src="{{product.image}}" ng-if="product.image">
								<span class="Media-figure placeholder product image" ng-if="!product.image"></span>
								<span class="Media-body">
									<h4 class="heading product-heading">{{product.name}}</h4>
									<p class="product-description">{{product.description}}</p>
								</span>
							</li>
						</ul>
					</div>

				</div>

				<div class="debug">

					<h5 class="debug">productPageObject</h5>
					<pre class="debug">{{productPageObject}}</pre>

					<h5 class="debug">productPageIDs</h5>
					<pre class="debug">{{productPageIDs}}</pre>

					<h5 class="debug">$childrenIDs</h5>
					<pre class="debug"><?php echo print_r($childrenIDs); ?></pre>

					<h5 class="debug">$children</h5>
					<pre class="debug"><?php echo print_r($children); ?></pre>

					<h5 class="debug">Products</h5>
					<pre class="debug">{{productGroup | json}}</pre>

					<h5 class="debug">Debug</h5>

					<pre class="debug">{{itemData | json}}</pre>

				</div>

			</div>
		</div>
	</div>

<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
