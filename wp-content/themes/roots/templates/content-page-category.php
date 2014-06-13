<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>
	<?php
		$category = get_field('category_id');
		$childs = get_posts(array('orderby' => 'menu_order', 'order' => 'ASC','showposts' => 1, 'post_parent' => $post->ID, 'post_type' => 'page'));
		wp_reset_query();	
		$childrenIDs = [];
		$GUIDmap = [];
		foreach ($childs as $key => $apost) {
			$anID = get_field('product_id', $apost->ID);
			$childrenIDs[] = $anID;
			$childrenGUIDs[] = $apost->ID;
		};
	?>
	<div ng-app="myApp">
		<div ng-controller="mainCtrl">

			<div ng-controller="productCategory">
				<form name="secrat" id="secrat">
					<input type="hidden" id="secret-children" name="secret-children" value="<?php echo implode(",", $childrenIDs)?>" get-product-ids />
					<input type="hidden" id="secret-category" name="secret-category" value="<?php echo implode(",", $childrenGUIDs) ?>" get-guids />
					<input type="hidden" id="secret-category" name="secret-category" value="<?php echo $category ?>" get-category="<?php echo $category ?>" />
				</form>
				<pre><?php echo implode(",", $childrenGUIDs) ?></pre>
				<div class="main-app category-page">

					<!-- <div class="product-pages">
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
					</div> -->
					<div class="products">
						<div class="subcategory" ng-repeat="subcat in productGroup.subcats">
							<h3 class="subcategory-heading">{{subcat.type}}</h3>
							<ul class="product-list">
								<li class="Media product" ng-repeat="product in subcat.items" ng-class="{'product-page-feature opens-page': hasProductPage(product.id)}">
									<a ng-href="{{hasProductPage(product.id) ? '/?page_id='+hasProductPage(product.id) : ''}}" href ng-click="hasProductPage(product.id) ? return : infoModal(product)" class="Media-figure image-wrap" ng-if="product.image">
										<img thumb-src="{{product.image}}">
									</a>
									<span class="Media-figure placeholder product image" ng-if="!product.image"></span>
									<span class="Media-body">
										<h4 class="heading product-heading" ng-if="hasProductPage(product.id)">
											<a ng-href="/?page_id={{hasProductPage(product.id)}}" class="product-link">{{product.name}}</a>
										</h4>
										<h4 class="heading product-heading" ng-if="!hasProductPage(product.id)">{{product.name}}</h4>
										<p class="product-description">{{product.description}}</p>
									</span>
								</li>
							</ul>
						</div>
					</div>

				</div>

				<div class="debug">

					<h5 class="debug">productPageIDs</h5>
					<pre class="debug">{{productPageIDs}}</pre>

					<h5 class="debug">$childrenIDs</h5>
					<pre class="debug"><?php echo print_r($childrenIDs); ?></pre>

					<?php /* <h5 class="debug">$childs</h5>
					<pre class="debug"><?php echo print_r($childs); ?></pre> */ ?>

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
