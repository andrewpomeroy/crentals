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
          <p ng-if="!isAdmin" class="form-control-static" ng-bind="orderData.orderMeta.orderPickupDate.date | date:'MM/dd/yy'"></p>
          <input ng-if="isAdmin" class="form-control" ng-model="orderData.orderMeta.orderPickupDate.date">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label class="control-label">Last Working Day</label>
          <p ng-if="!isAdmin" class="form-control-static" ng-bind="orderData.orderMeta.orderReturnDate.date | date:'MM/dd/yy'"></p>
          <input ng-if="isAdmin" class="form-control" ng-model="orderData.orderMeta.orderReturnDate.date">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="companyName" class="control-label">Company Name</label>
          <p ng-if="!isAdmin" name="companyName" id="companyName" class="form-control-static" ng-bind="orderData.orderMeta.companyName"></p>
          <input ng-if="isAdmin" name="companyName" id="companyName" class="form-control" ng-model="orderData.orderMeta.companyName">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="contactName" class="control-label">Contact Name</label>
          <p ng-if="!isAdmin" name="contactName" id="contactName" class="form-control-static" ng-bind="orderData.orderMeta.contactName"></p>
          <input ng-if="isAdmin" name="contactName" id="contactName" class="form-control" ng-model="orderData.orderMeta.contactName">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="address" class="control-label">Address</label>
          <p ng-if="!isAdmin" name="address" id="address" class="form-control-static" ng-bind="orderData.orderMeta.address"></p>
          <input ng-if="isAdmin" name="address" id="address" class="form-control" ng-model="orderData.orderMeta.address">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="contactPosition" class="control-label">Contact Position</label>
          <p ng-if="!isAdmin" name="contactPosition" id="contactPosition" class="form-control-static" ng-bind="orderData.orderMeta.contactPosition"></p>
          <input ng-if="isAdmin" name="contactPosition" id="contactPosition" class="form-control" ng-model="orderData.orderMeta.contactPosition">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="jobName" class="control-label">Job Name</label>
          <p ng-if="!isAdmin" name="jobName" id="jobName" class="form-control-static" ng-bind="orderData.orderMeta.jobName"></p>
          <input ng-if="isAdmin" name="jobName" id="jobName" class="form-control" ng-model="orderData.orderMeta.jobName">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="email" class="control-label">Email Address</label>
          <p ng-if="!isAdmin" name="email" id="email" class="form-control-static" ng-bind="orderData.orderMeta.email"></p>
          <input ng-if="isAdmin" name="email" id="email" class="form-control" ng-model="orderData.orderMeta.email">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="jobNumber" class="control-label">Job #</label>
          <p ng-if="!isAdmin" name="jobNumber" id="jobNumber" class="form-control-static" ng-bind="orderData.orderMeta.jobNumber"></p>
          <input ng-if="isAdmin" name="jobNumber" id="jobNumber" class="form-control" ng-model="orderData.orderMeta.jobNumber">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="phone" class="control-label">Phone Number</label>
          <p ng-if="!isAdmin" name="phone" id="phone" class="form-control-static" ng-bind="orderData.orderMeta.phone"></p>
          <input ng-if="isAdmin" name="phone" id="phone" class="form-control" ng-model="orderData.orderMeta.phone">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="PONumber" class="control-label">PO #</label>
          <p ng-if="!isAdmin" name="PONumber" id="PONumber" class="form-control-static" ng-bind="orderData.orderMeta.PONumber"></p>
          <input ng-if="isAdmin" name="PONumber" id="PONumber" class="form-control" ng-model="orderData.orderMeta.PONumber">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="notes" class="control-label">Notes</label>
          <p ng-if="!isAdmin" name="notes" id="notes" class="form-control-static" ng-bind="orderData.orderMeta.notes"></p>
          <input ng-if="isAdmin" name="notes" id="notes" class="form-control" ng-model="orderData.orderMeta.notes">
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
        <td class="data static">
          <span ng-if="!item.edit" ng-bind="item.name"></span>
          <input ng-if="item.edit" ng-model="item.name" />
        </td>
        <td class="data static number">
          <span ng-if="!item.edit" ng-bind="item.qty"></span>
          <input ng-if="item.edit" ng-model="item.qty" />
        </td>
        <td class="data static">
          <span ng-if="!item.edit" ng-bind="item.rate | currency:'$':0"></span>
          <input ng-if="item.edit" ng-model="item.rate" />
        </td>
        <td class="data static date" ng-if="item.startDate">
          <strong ng-if="!item.edit" ng-bind="item.startDate"></strong>
          <input ng-if="item.edit" ng-model="item.startDate" />
        </td>
        <td class="data static date" ng-if="!item.startDate">
          <span ng-if="!item.edit" ng-bind="orderData.orderMeta.orderPickupDate.date | date:'MM/dd'"></span>
          <input ng-if="item.edit" ng-model="orderData.orderMeta.orderPickupDate.date" />
        </td>
        <td class="data static date" ng-if="item.endDate">
          <strong ng-if="!item.edit" ng-bind="item.endDate"></strong>
          <input ng-if="item.edit" ng-model="item.endDate" />
        </td>
        <td class="data static date" ng-if="!item.endDate">
          <span ng-if="!item.edit" ng-bind="orderData.orderMeta.orderReturnDate.date | date:'MM/dd'"></span>
          <input ng-if="item.edit" ng-model="orderData.orderMeta.orderReturnDate.date" />
        </td>
        <td class="data static">
          <span ng-if="!item.edit" ng-bind="item.clientnotes"></span>
          <input ng-if="item.edit" ng-model="item.clientnotes" />
        </td>
        <td class="data static number">
          <span ng-if="!item.edit" ng-bind="item.estimate | currency:'$'"></span>
          <input ng-if="item.edit" ng-model="item.estimate" />
        </td>
        <td ng-if="isAdmin">
          <button class="btn date-mode-control glyphicon glyphicon-pencil" ng-if="!item.edit" ng-click="item.edit=true;"></button>
          <button class="btn date-mode-control glyphicon glyphicon-ok-circle" ng-if="item.edit" ng-click="item.edit=false;"></button>
        </td>
      </tr>
    </tbody>
  </table>
  <h4 class="align-right">Total: <strong>{{orderData.orderMeta.totalEstimate | currency:"$"}}</strong></h4>
