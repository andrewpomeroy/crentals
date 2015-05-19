<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <div ng-app="myApp">
      <div ng-controller="mainCtrl">
        <div ng-controller="estimateForm">
          <div ng-controller="estimateSingleCtrl">
            <script type="text/javascript">
              var theOrderData = <?php echo get_the_content(); ?>;
              var isSubmitted = function() {
                  return <?php echo has_category("Submitted"); ?>
              };
              var singleEstimateTitle = "<?php echo get_the_title() ?>";
              var currentPostId = <?php echo get_the_ID() ?>;

            </script>

            <?php get_template_part('templates/content', 'summary-edit'); ?>
            
            <!-- <button class="btn btn-default print-styles-toggle btn-print" ng-click="printOrder()" ng-if="!isOrderGood">Print Order</button> -->
          </div>
        </div>
      </div>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
    <?php // comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
