  <header>
    <h1 class="entry-title title">Project Estimate:</h1>
    <h2 class="entry-title">{{orderData.orderMeta.companyName}} – {{orderData.orderMeta.jobName}}</h2>
    <?php // get_template_part('templates/entry-meta'); ?>
  </header>
  <div class="order-meta">

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label class="control-label">First Working Day</label>
          <p class="form-control-static" ng-bind="orderData.orderMeta.orderPickupDate.date | date:'MM/dd/yy'"></p>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label class="control-label">Last Working Day</label>
          <p class="form-control-static" ng-bind="orderData.orderMeta.orderReturnDate.date | date:'MM/dd/yy'"></p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="companyName" class="control-label">Company Name</label>
          <p name="companyName" id="companyName" class="form-control-static" ng-bind="orderData.orderMeta.companyName"></p>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="contactName" class="control-label">Contact Name</label>
          <p name="contactName" id="contactName" class="form-control-static" ng-bind="orderData.orderMeta.contactName"></p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="address" class="control-label">Address</label>
          <p name="address" id="address" class="form-control-static" ng-bind="orderData.orderMeta.address"></p>
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
          <label for="jobName" class="control-label">Job Name</label>
          <p name="jobName" id="jobName" class="form-control-static" ng-bind="orderData.orderMeta.jobName"></p>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="email" class="control-label">Email Address</label>
          <p name="email" id="email" class="form-control-static" ng-bind="orderData.orderMeta.email"></p>
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
          <label for="phone" class="control-label">Phone Number</label>
          <p name="phone" id="phone" class="form-control-static" ng-bind="orderData.orderMeta.phone"></p>
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
      <div class="col-sm-6">
        <div class="form-group">
          <label for="notes" class="control-label">Notes</label>
          <p name="notes" id="notes" class="form-control-static" ng-bind="orderData.orderMeta.notes"></p>
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
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>                  
      <tr ng-repeat="item in orderData.items" class="item-row" ng-class="{'hide-dates': daysWeekZero(item)}" >
        <td class="data static" ng-bind="item.name"></td>
        <td class="data static number" ng-bind="item.qty"></td>
        <td class="data static" ng-bind="item.rate | currency:'$':0"></td>
        <td class="data static date" ng-if="item.startDate"><strong ng-bind="item.startDate | date:'MM/dd/yy'"></strong></td>
        <td class="data static date" ng-if="!item.startDate" ng-bind="orderData.orderMeta.orderPickupDate.date | date:'MM/dd/yy'"></td>
        <td class="data static date" ng-if="item.endDate"><strong ng-bind="item.endDate | date:'MM/dd/yy'"></strong></td>
        <td class="data static date" ng-if="!item.endDate" ng-bind="orderData.orderMeta.orderReturnDate.date | date:'MM/dd/yy'"></td>
        <td class="data static" ng-bind="item.clientnotes"></td>
        <td class="data static number" ng-bind="item.estimate | currency:'$'"></td>
      </tr>
    </tbody>
  </table>
  <h4 class="align-right">Total: <strong>{{orderData.orderMeta.totalEstimate | currency:"$"}}</strong></h4>
