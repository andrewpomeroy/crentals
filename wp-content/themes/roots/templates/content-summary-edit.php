  <header>
    <h1 class="entry-title title">Project Estimate:</h1>
    <h2 class="entry-title">{{orderMeta.companyName}} – {{orderMeta.jobName}}</h2>
    <?php // get_template_part('templates/entry-meta'); ?>
  </header>
  <div class="order-meta">

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label class="control-label">First Working Day</label>
          <div class="form-group">
            <p class="input-group">
              <input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="orderMeta.orderPickupDate.date" name="orderMeta.orderPickupDate" is-open="orderMeta.orderPickupDate.opened" min="minDate" max="'2020-06-22'" datepicker-options="dateOptions" close-text="Close" ng-change="calcRentalDates(isSingle)" />
              <span class="input-group-btn">
                <button class="btn btn-default" ng-click="open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
              </span>
            </p>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label class="control-label">Last Working Day</label>
          <div class="form-group">
            <p class="input-group" ng-class="{'has-error': (orderMeta.orderReturnDate.date < orderMeta.orderPickupDate.date)}">
              <input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="orderMeta.orderReturnDate.date" name="orderMeta.orderReturnDate" is-open="orderMeta.orderReturnDate.opened" min="minDate" max="'2020-06-22'" datepicker-options="dateOptions" close-text="Close" ng-change="calcRentalDates(isSingle)" />
              <span class="input-group-btn">
                <button class="btn btn-default" ng-click="open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
              </span>
            </p>
            <div class="help-block" ng-if="orderMeta.orderReturnDate.date < orderMeta.orderPickupDate.date">End date cannot be earlier than start date.</div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="companyName" class="control-label">Company Name</label>
          <input name="companyName" id="companyName" class="form-control" ng-model="orderMeta.companyName">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="contactName" class="control-label">Contact Name</label>
          <input name="contactName" id="contactName" class="form-control" ng-model="orderMeta.contactName">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="address" class="control-label">Address</label>
          <input name="address" id="address" class="form-control" ng-model="orderMeta.address">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="contactPosition" class="control-label">Contact Position</label>
          <input name="contactPosition" id="contactPosition" class="form-control" ng-model="orderMeta.contactPosition">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="jobName" class="control-label">Job Name</label>
          <input name="jobName" id="jobName" class="form-control" ng-model="orderMeta.jobName">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="email" class="control-label">Email Address</label>
          <input name="email" id="email" class="form-control" ng-model="orderMeta.email">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="jobNumber" class="control-label">Job #</label>
          <input name="jobNumber" id="jobNumber" class="form-control" ng-model="orderMeta.jobNumber">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="phone" class="control-label">Phone Number</label>
          <input name="phone" id="phone" class="form-control" ng-model="orderMeta.phone">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="PONumber" class="control-label">PO #</label>
          <input name="PONumber" id="PONumber" class="form-control" ng-model="orderMeta.PONumber">
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="notes" class="control-label">Notes</label>
          <input name="notes" id="notes" class="form-control" ng-model="orderMeta.notes">
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
        <th>Days/Week</th>
        <th>Notes</th>
        <th>Amount</th>
        <th></th>
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
          <input type="number" ng-if="item.edit" ng-model="item.qty" ng-change="changeQty(item); calcTotal()" ng-pattern="/^[0-9][0-9]*$/" />
        </td>
        <td class="data static number">
          <span ng-if="!item.edit" ng-bind="item.rate | currency:'$':0"></span>
          <input ng-if="item.edit" ng-model="item.rate" ng-change="changeQty(item); calcTotal()"/>
        </td>
        <td class="data static date rental-period" ng-class="{'edit-mode': item.edit, 'has-custom-rental-period': item.customRentalPeriod}">
          <strong class="individual-date" ng-bind="item.startDate | date:'MM/dd/yy'"></strong>
          <button ng-if="item.edit" class="btn glyphicon glyphicon-minus date-control" ng-click="incrementIndividualDate(item.startDate, -1, orderMeta.orderPickupDate.date, item)"></button>
          <button ng-if="item.edit" class="btn glyphicon glyphicon-plus date-control" ng-click="incrementIndividualDate(item.startDate, 1, orderMeta.orderPickupDate.date, item)"></button>
        </td>
        <td class="data static date rental-period" ng-class="{'edit-mode': item.edit, 'has-custom-rental-period': item.customRentalPeriod}">
          <strong class="individual-date" ng-bind="item.endDate | date:'MM/dd/yy'"></strong>
          <button ng-if="item.edit" class="btn glyphicon glyphicon-minus date-control" ng-click="incrementIndividualDate(item.endDate, -1, orderMeta.orderReturnDate.date, item)"></button>
          <button ng-if="item.edit" class="btn glyphicon glyphicon-plus date-control" ng-click="incrementIndividualDate(item.endDate, 1, orderMeta.orderReturnDate.date, item)"></button>
        </td>
        <td class="data static date" ng-if="!item.endDate">
          <span ng-if="!item.edit" ng-bind="orderMeta.orderReturnDate.date | date:'MM/dd/yy'"></span>
          <input ng-if="item.edit" ng-model="orderMeta.orderReturnDate.date" />
        </td>
        <td class="data static number">
          <span ng-if="!item.edit" ng-bind="item.daysweek"></span>
          <input ng-if="item.edit" ng-model="item.daysweek" ng-change="calcRentalDates(isSingle())" />
        <td class="data static">
          <span ng-if="!item.edit" ng-bind="item.clientnotes"></span>
          <input ng-if="item.edit" ng-model="item.clientnotes" />
        </td>
        <td class="data static number">
          <span ng-bind="item.estimate | currency:'$'"></span>
        </td>
        <td>
          <button class="btn date-mode-control glyphicon glyphicon-pencil" ng-if="!item.edit" ng-click="item.edit=true;"></button>
          <button class="btn date-mode-control glyphicon glyphicon-ok-circle" ng-if="item.edit" ng-click="item.edit=false;"></button>
        </td>
      </tr>
    </tbody>
  </table>
  <h4 class="align-right">Total: <strong>{{totalEstimate | currency:"$"}}</strong></h4>
