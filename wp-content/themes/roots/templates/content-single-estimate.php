<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <div ng-app="myApp">
      <div ng-controller="mainCtrl">
        <div ng-controller="estimateForm">
          <div ng-controller="estimateSingleCtrl">
            <script type="text/javascript">
              var isCompressed = function() {
                return <?php echo get_post_meta(get_the_ID(), 'compressed', true); ?>;
              }
              <?php 
              if (get_post_meta(get_the_ID(), 'compressed', true)) {
                $rawOrderData = get_the_content();
                $orderData = addslashes($rawOrderData);
                $orderData = str_replace(array("\r\n", "\n", "\r"), ' ', $orderData);

                echo (
                  "var theOrderData = jsonpack.unpack(\"". $orderData . "\");"
                  );
              }
              else {
                echo ("var theOrderData = " . get_the_content() . ";");
              }
              ?>
              var isSubmitted = function() {
                  return <?php echo has_category("Submitted"); ?>
              };
              var singleEstimateTitle = "<?php echo get_the_title() ?>";
              var currentPostId = <?php echo get_the_ID() ?>;

            </script>
            <input type="text" ng-model="selectedItem" typeahead="item as item.name for item in flatItemData | filter:{name: $viewValue} | limitTo:8" class="form-control" ng-if="dataStatus === 'loaded'" typeahead-focus-first="false" typeahead-on-select="hello($item, $model, $label)">
            <pre>{{selectedItem | json}}</pre>
            <div class="no-print estimateEdit">
              <?php get_template_part('templates/content', 'summary-edit'); ?>
            </div>
            <div class="print-only estimateSummary">
              <?php get_template_part('templates/content', 'summary'); ?>
            </div>
            
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
