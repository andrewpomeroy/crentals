<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <div ng-app="myApp">
      <div ng-controller="mainCtrl">
        <div ng-controller="estimateSingleCtrl">
          <script>theOrderData = <?php echo get_the_content(); ?>;</script>
          <header>
            <h1 class="entry-title title">Estimate</h1>
            <h2 class="entry-title">{{orderData.orderMeta.companyName}} – {{orderData.orderMeta.jobName}}</h2>
            <?php // get_template_part('templates/entry-meta'); ?>
          </header>
          <div class="order-meta">

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">Order Pickup Date</label>
                  <p class="form-control-static" ng-bind="orderData.orderMeta.orderPickupDate.date | date:'MM/dd/yy'"></p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label">Order Return Date</label>
                  <p class="form-control-static" ng-bind="orderData.orderMeta.orderReturnDate.date | date:'MM/dd/yy'"></p>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="jobName" class="control-label">Job Name</label>
                  <p name="jobName" id="jobName" class="form-control-static" ng-bind="orderData.orderMeta.jobName"></p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="companyName" class="control-label">Company Name</label>
                  <p name="companyName" id="companyName" class="form-control-static" ng-bind="orderData.orderMeta.companyName"></p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="contactName" class="control-label">Contact Name</label>
                  <p name="contactName" id="contactName" class="form-control-static" ng-bind="orderData.orderMeta.contactName"></p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="contactPosition" class="control-label">Contact Position</label>
                  <p name="contactPosition" id="contactPosition" class="form-control-static" ng-bind="orderData.orderMeta.contactPosition"></p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="email" class="control-label">Email Address</label>
                  <p name="email" id="email" class="form-control-static" ng-bind="orderData.orderMeta.email"></p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="phone" class="control-label">Phone Number</label>
                  <p name="phone" id="phone" class="form-control-static" ng-bind="orderData.orderMeta.phone"></p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="jobNumber" class="control-label">Job #</label>
                  <p name="jobNumber" id="jobNumber" class="form-control-static" ng-bind="orderData.orderMeta.jobNumber"></p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="fax" class="control-label">Fax</label>
                  <p name="fax" id="fax" class="form-control-static" ng-bind="orderData.orderMeta.fax"></p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="PONumber" class="control-label">PO #</label>
                  <p name="PONumber" id="PONumber" class="form-control-static" ng-bind="orderData.orderMeta.PONumber"></p>
                </div>
              </div>
            </div>
          </div>
          <table class="order-form table">
            <thead>
              <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Notes</th>
                <th>Estimate</th>
              </tr>
            </thead>
            <tbody>                  
              <tr ng-repeat="item in orderData.items" class="item-row">
                <td class="data static" ng-bind="item.name"></td>
                <td class="data static" ng-bind="item.qty"></td>
                <td class="data static" ng-bind="item.rate"></td>
                <td class="data static" ng-bind="item.startDate | date:'MM/dd'"></td>
                <td class="data static" ng-bind="item.endDate | date:'MM/dd'"></td>
                <td class="data static" ng-bind="item.clientnotes"></td>
                <td class="data static" ng-bind="item.estimate | currency:'$'"></td>
              </tr>
            </tbody>
          </table>
          <h4 class="align-right">Total Estimate: <strong>{{orderData.orderMeta.totalEstimate | currency:"$"}}</strong></h4>
          <div class="gray-box align-center">
              <a href="javascript:window.print();" class="btn btn-default print-styles-toggle">Print Order</a>
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
