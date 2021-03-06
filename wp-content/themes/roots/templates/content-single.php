<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php // get_template_part('templates/entry-meta'); ?>
    </header>
    <?php get_template_part('templates/content', 'video'); ?>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <?php get_template_part('templates/content', 'video-list'); ?>
    <?php get_template_part('templates/content', 'file-list'); ?>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
    <?php // comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
