<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>

	<div class="app-container">
		<form action="" id="main-form">
			
		</form>
	</div>
	<div class="templates">
		<!-- table-wrap -->
			<section class="table" data-template-item="table-wrap">
				<section class="table-header" data-template-inject="table-header">
				</section>
				<section class="table-contents" data-template-inject="table-contents">
				</section>
			</section>
		<!-- form-section -->
		<section class="form-section" data-template-item="">
			<section class="section-header">
				<h3 class="item-heading" data-value-inner="section-heading"></h3>
				<div class="section-contents" data-template-item="section-contents">
					<div class="name">
						<p class="cell-label" data-perms-client="r" data-value-inner="name"></p>
					</div>
					<div class="quantity">
						<p class="cell-label" data-perms-client="r"></p>
						<input class="cell-label" data-perms-client="rw" />
					</div>
					<div class="rate">
						<p class="cell-label" data-perms-client="r" data-value-inner="rate"></p>
					</div>
					<div class="days-wk" data-default="7"></div>
					<div class="days" data-input="input"></div>
					<div class="estimate"></div>
				</div>
			</section>
			<!-- heading -->
			<div class="heading">
				<h3 class="item-heading"></h3>
			</div>
			<!-- item -->
			<div data-template-item="item" class="item">
			</div>
			<!-- item-inner -->
			<input data-template-item="item-input" class="cell-input" />
			<p data-template-item="item-ro" class="cell-ro-value" data-value-inner="true"></p>

		</section>
	</div>
	<div ng-app="myApp">

		<div ng-controller="orderForm">
			<section class="main-app">
				<form class="form">
					<div class="form-group">
						<label class="control-label">Field Label</label>
						<input type="text" class="form-control" ng-model="itemData.groups[0].type" />
					</div>
				</form>
			</section>
			<section class="debug">
				<h5 class="debug">Debug</h5>
				<pre class="debug">{{debugData | json}}</pre>
			</section>
			<button ng-click="clickButton()">Super Button</button>
		</div>

	</div>

		<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
	<?php endwhile; ?>
