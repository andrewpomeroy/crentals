<?php while (have_posts()) : the_post(); ?>
<?php  get_template_part('templates/content', 'video'); ?>
  <?php the_content(); ?>
  <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
