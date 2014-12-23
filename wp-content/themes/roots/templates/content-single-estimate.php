<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <div ng-app="myApp">
      <div ng-controller="mainCtrl">
        <div ng-controller="estimateSingleCtrl">
          <script>theOrderData = <?php echo get_the_content(); ?>;</script>
          <?php get_template_part('templates/content', 'summary'); ?>
          <button class="btn btn-default print-styles-toggle btn-print" ng-click="printOrder()">Print Order</a>
        </div>
      </div>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
    <?php // comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
