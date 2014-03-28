<?php while (have_posts()) : the_post(); ?>
  <?php the_content(); ?>

	<div class="app-container"></div>

  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
