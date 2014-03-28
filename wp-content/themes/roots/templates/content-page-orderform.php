<?php while (have_posts()) : the_post(); ?>
  <?php the_content(); ?>
  <h1>Barf</h1>
  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
