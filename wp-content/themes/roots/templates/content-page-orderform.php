<?php while (have_posts()) : the_post(); ?>
  <?php the_content(); ?>

	<div class="app-container">
		<form action="" id="main-form">
			
		</form>
	</div>
	<div class="templates">
		<!-- form-section -->
		<section class="form-section" data-template-item="">
			<section class="section-header">
				<h3 class="item-heading" data-value-inner="section-heading"></h3>
			<div class="section-contents" data-include="section-contents">
				<header class="table-header">
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
				</header>
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





	</div>

  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
