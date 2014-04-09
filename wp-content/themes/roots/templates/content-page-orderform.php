<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>

	<div ng-app="myApp">

		<div ng-controller="orderForm">
			<section class="main-app">
				<form name="orderFormForm" novalidate>

				<section class="applicant-info order-form">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group"><label for="jobName" class="control-label">Job Name</label><input type="text" name="jobName" id="jobName" class="form-control" ng-model="orderMeta.jobName"></div>
						</div>
						<div class="col-sm-6">
							<div class="form-group"><label for="companyName" class="control-label">Company Name</label><input type="text" name="companyName" id="companyName" class="form-control" ng-model="orderMeta.companyName"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group"><label for="contactName" class="control-label">Contact Name</label><input type="text" name="contactName" id="contactName" class="form-control" ng-model="orderMeta.contactName"></div>
						</div>
						<div class="col-sm-6">
							<div class="form-group"><label for="contactPosition" class="control-label">Contact Position</label><input type="text" name="contactPosition" id="contactPosition" class="form-control" ng-model="orderMeta.contactPosition"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group"><label for="email" class="control-label">Email Address</label><input type="text" name="email" id="email" class="form-control" ng-model="orderMeta.email"></div>
						</div>
						<div class="col-sm-6">
							<div class="form-group"><label for="phone" class="control-label">Phone Number</label><input type="text" name="phone" id="phone" class="form-control" ng-model="orderMeta.phone"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group"><label for="jobNumber" class="control-label">Job #</label><input type="text" name="jobNumber" id="jobNumber" class="form-control" ng-model="orderMeta.jobNumber"></div>
						</div>
						<div class="col-sm-6">
							<div class="form-group"><label for="fax" class="control-label">Fax</label><input type="text" name="fax" id="fax" class="form-control" ng-model="orderMeta.fax"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group"><label for="PONumber" class="control-label">PO #</label><input type="text" name="PONumber" id="PONumber" class="form-control" ng-model="orderMeta.PONumber"></div>
						</div>
						<div class="col-sm-6">
							<div class="form-group"><label for="shootDays" class="control-label">Shoot Days</label><input type="text" name="shootDays" id="shootDays" class="form-control" ng-model="orderMeta.shootDays"></div>
						</div>
					</div>
				</section>

				<section class="rental-dates">
					<div class="row">
						<div class="col-sm-6">
							<h4>Pickup Date</h4>
							<div class="form-group">
								<p class="input-group">
									<input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="orderPickupDate.date" name="orderPickupDate" is-open="orderPickupDate.opened" min="minDate" max="'2020-06-22'" datepicker-options="dateOptions" ng-required="true" close-text="Close" ng-change="calcRentalDates()" />
									<span class="input-group-btn">
										<button class="btn btn-default" ng-click="open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
									</span>
								</p>
							</div>
						</div>
						<div class="col-sm-6">
							<h4>Return Date</h4>
							<div class="form-group">
								<p class="input-group" ng-class="{'has-error': (orderReturnDate.date < orderPickupDate.date)}">
									<input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="orderReturnDate.date" name="orderReturnDate" is-open="orderReturnDate.opened" min="minDate" max="'2020-06-22'" datepicker-options="dateOptions" ng-required="true" close-text="Close" ng-change="calcRentalDates()" />
									<span class="input-group-btn">
										<button class="btn btn-default" ng-click="open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
									</span>
								</p>
								<div class="help-block" ng-if="orderReturnDate.date < orderPickupDate.date">End date cannot be earlier than start date.</div>
							</div>
						</div>
					</div>
					<div ng-if="totalRentalDays > 0">
						<h4 class="pull-right">Total Rental Days: <strong>{{totalRentalDays}}</strong></h4>
						</div>
					</section>

					<section class="order-form row">
						<div class="col-sm-12">
							<h2>Selection</h2>
							<table class="order-form table">
								<thead>
									<tr>
										<td>Item</td>
										<td>Quantity</td>
										<td>Rate</td>
										<td>Days</td>
										<td>Days/Week</td>
										<td>Estimate</td>
										<td>Notes</td>
									</tr>
								</thead>
								<tbody ng-repeat="group in itemData" ng-if="group.items">
									<tr class="section-heading-row">
										<td colspan="7" class="section-heading"><h4 class="section-heading">{{group.type}}</h4></td>
									</tr>
									<tr ng-repeat="item in group.items" class="item-row" ng-class="{'has-error': (item.estimate === null)}">
										<td class="data static">{{item.name}}</td>
										<td class="data dynamic input" ng-class="{'has-error': (
										(orderFormForm[item.name + '_qty'].$invalid) )
									}"><input type="number" ng-model="item.qty" ng-change="changeQty(item); calcTotal()" dynamic-name="item.name + '_qty'"/ ng-pattern="/^[0-9][0-9]*$/" ><div class="help-block" ng-show="orderFormForm[item.name + '_qty'].$invalid">Must be a non-negative integer</div></td>
									<td class="data static">{{item.rate | currency:"$"}}</td>
									<td class="data dynamic input" ng-class="{'has-error': (
									(orderFormForm[item.name + '_days'].$invalid) )
								}"><input type="number" ng-model="item.days" ng-change="changeQty(item); calcTotal()" dynamic-name="item.name + '_days'"/ ng-pattern="/^[0-9][0-9]*$/" ><div class="help-block" ng-show="orderFormForm[item.name + '_days'].$invalid">Must be a non-negative integer</div></td>
								<td class="data static">{{item.daysweek}}</td>
								<td class="data dynamic"><span ng-show="!!(item.estimate)">{{item.estimate | currency:"$"}}</span></td>
								<td class="input dynamic"><span><textarea dynamic-name="item.name + '_notes'" ng-model="item.notes" /></textarea></span></td>
									</tr>
								</tbody>

	<!-- 					<div class="divs" ng-repeat="entry in itemData">
	 						<h3>{{ $index }}</h3>
							<div class="form-group" ng-repeat="(key,val) in entry">
								<label class="control-label">{{key}}</label>
								<input type="text" class="form-control" ng-model="entry[key]" />
							</div> 
						</div> -->
							</table>
							<div ng-if="totalEstimate > 0">
								<h4 class="pull-right">Total Estimate: <strong>{{totalEstimate | currency:"$"}}</strong></h4>
								</div>
						</div>
					</section>
				</form>
		</section>
	<section class="debug">
		<pre class="debug">{{totalEstimate}}</pre>
		<pre class="debug">{{orderMeta}}</pre>
		<!-- <pre class="debug">{{orderPickupDate | json}}</pre> -->
		<!-- <pre class="debug">{{orderReturnDate | json}}</pre> -->
		<h5 class="debug">Debug</h5>
		<pre class="debug">{{itemData | json}}</pre>
		<h5 class="debug">orderFormForm</h5>
		<pre class="debug">{{orderFormForm | json}}</pre>
	</section>
	<button class="btn" ng-click="resetForm()">Reset Form</button>

</div>

</div>

<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
