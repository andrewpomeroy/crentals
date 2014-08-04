<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>

	<div ng-app="myApp">

		<div ng-controller="mainCtrl">
			<div ng-controller="estimateForm">
				<section class="loading" ng-if="!dataStatus">
					<div class="loading-spinner"></div>
					<h4 class="loading-status">Loading product data</h4>
				</section>
				<section class="main-app" ng-if="dataStatus === 'loaded'">
					<form name="orderFormForm" novalidate>

						<section class="rental-dates order-form">
							<div class="row">
								<div class="col-sm-6">
									<h4>First Working Day</h4>
									<div class="form-group">
										<p class="input-group">
											<input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="orderMeta.orderPickupDate.date" name="orderMeta.orderPickupDate" is-open="orderMeta.orderPickupDate.opened" min="minDate" max="'2020-06-22'" datepicker-options="dateOptions" close-text="Close" ng-change="calcRentalDates()" />
											<span class="input-group-btn">
												<button class="btn btn-default" ng-click="open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
											</span>
										</p>
									</div>
								</div>
								<div class="col-sm-6">
									<h4>Last Working Day</h4>
									<div class="form-group">
										<p class="input-group" ng-class="{'has-error': (orderMeta.orderReturnDate.date < orderMeta.orderPickupDate.date)}">
											<input type="text" class="form-control" datepicker-popup="{{format}}" ng-model="orderMeta.orderReturnDate.date" name="orderMeta.orderReturnDate" is-open="orderMeta.orderReturnDate.opened" min="minDate" max="'2020-06-22'" datepicker-options="dateOptions" close-text="Close" ng-change="calcRentalDates()" />
											<span class="input-group-btn">
												<button class="btn btn-default" ng-click="open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
											</span>
										</p>
										<div class="help-block" ng-if="orderMeta.orderReturnDate.date < orderMeta.orderPickupDate.date">End date cannot be earlier than start date.</div>
									</div>
								</div>
							</div>
							<div ng-if="orderMeta.totalRentalDays > 0">
								<h4 class="pull-right">Total Rental Days: <strong>{{orderMeta.totalRentalDays}}</strong></h4>
								</div>
							</section>

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
									<!-- <div class="form-group"><label for="shootDays" class="control-label">Shoot Days</label><input type="text" name="shootDays" id="shootDays" class="form-control" ng-model="orderMeta.shootDays"></div> -->

								</div>
							</div>
						</section>

						<section class="order-form row">
							<div class="col-sm-12">
								<h2 class="title">Item Selection</h2>
								<table class="order-form table" ng-repeat="group in itemData" ng-if="categoryHasItems(group)">
									<thead>
										<tr class="section-heading-row">
											<th colspan="8" class="section-heading"><h3 class="section-heading">{{group.type}}</h3></th>
										</tr>
										<tr>
											<th>Item</th>
											<th>Quantity</th>
											<th>Rate</th>
											<th>Working Dates</th>
											<th>Days</th>
											<th>Days/Week</th>
											<th>Notes</th>
											<th>Estimate</th>
										</tr>
									</thead>
									<tbody ng-repeat="subcat in group.subcats">
										<tr>
											<td colspan="8" class="subcat-heading"><h5 class="subcat-heading" ng-if="subcat.type">{{subcat.type}}</h5></td>
										</tr>
										<tr ng-repeat="item in subcat.items" class="item-row" ng-class="{'has-error': (item.estimate === null)}">
											<td class="data static">
												<a href class="item-name linked" ng-if="item.description || item.image" ng-click="infoModal(item)">{{item.name}}<i class="glyphicon glyphicon-camera text-muted" ng-if="item.image"></i></a>
												<span class="item-name no-image no-description" ng-if="!item.description && !item.image">{{item.name}}</span>
											</td>
											<td class="data dynamic input number" ng-class="{'has-error': (
											(orderFormForm[item.name + '_qty'].$invalid) )
										}"><input type="number" ng-model="item.qty" ng-change="changeQty(item); calcTotal()" dynamic-name="item.name + '_qty'"/ ng-pattern="/^[0-9][0-9]*$/" ><div class="help-block" ng-show="orderFormForm[item.name + '_qty'].$invalid">Must be a non-negative integer</div></td>
										<td class="data static number">{{item.rate | currency:"$"}}</td>
										<td class="data static rental-period" ng-class="{'edit-mode': item.customRentalPeriod}">
											<div class="date-controls-container">
												<div class="date-controls left">
													<span class="date-type-label edit-mode">First Day</span>
													<span class="individual-date">{{item.startDate | date:"MM/dd"}}</span><button class="btn glyphicon glyphicon-minus date-control" ng-click="incrementIndividualDate(item.startDate, -1)" ng-if="item.customRentalPeriod"></button><button class="btn glyphicon glyphicon-plus date-control" ng-click="incrementIndividualDate(item.startDate, 1)" ng-if="item.customRentalPeriod"></button>
													<span class="date-separator"> – </span>
													<span class="date-type-label edit-mode">Last Day</span>
													<span class="individual-date">{{item.endDate | date:"MM/dd"}}</span><button class="btn glyphicon glyphicon-minus date-control" ng-click="incrementIndividualDate(item.endDate, -1)" ng-if="item.customRentalPeriod"></button><button class="btn glyphicon glyphicon-plus date-control" ng-click="incrementIndividualDate(item.endDate, 1)" ng-if="item.customRentalPeriod"></button>
												</div>
												<div class="date-controls right">
													<button class="btn date-mode-control glyphicon glyphicon-pencil" ng-if="!item.customRentalPeriod" ng-click="item.customRentalPeriod=true;"></button>
													<button class="btn date-mode-control glyphicon glyphicon-remove-circle" ng-if="item.customRentalPeriod" ng-click="flushIndividualDate(item, true)" title="Reset"></button>
												</div>
											</div>
										</td>
										<!-- <td class="data static">{{item.startDate}}</td> -->
										<!-- <td class="data static">{{item.endDate}}</td> -->
										<!-- <td class="data dynamic input" ng-class="{'has-error': ((orderFormForm[item.name + '_days'].$invalid) )}"><input type="number" ng-model="item.days" ng-change="changeQty(item); calcTotal()" dynamic-name="item.name + '_days'"/ ng-pattern="/^[0-9][0-9]*$/" ><div class="help-block" ng-show="orderFormForm[item.name + '_days'].$invalid">Must be a non-negative integer</div></td> -->
										<td class="data static number" ng-bind="item.days"></td>
									<td class="data static item-days number" ng-bind="item.daysweek"></td>
									<td class="notes">
										<span class="static notes display-block" ng-if="item.notes" ng-bind="item.notes"></span>
										<span><textarea class="dynamic notes" dynamic-name="item.name + '_notes'" ng-model="item.clientnotes" ng-class="{'only-hover': !item.clientnotes}" placeholder="Enter any custom note..."/></textarea></span>
									</td>
									<td class="data dynamic"><span ng-show="!!(item.estimate)"><strong>{{item.estimate | currency:"$"}}</strong></span></td>
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
							</div>
						</section>
						<section class="order-form row">
							<div class="col-sm-12">
								<div ng-if="totalEstimate > 0">
									<h4 class="align-right">Total Estimate: <strong>{{totalEstimate | currency:"$"}}</strong></h4>
									<div class="submit-bar align-right">
									<!-- <div class="submit-bar align-right" ng-if="isOrderGood !== 1"> -->
										<button class="btn" ng-click="resetForm()" ng-disabled="isOrderGood === 0">Reset Form</button>
										<button class="btn btn-info submit" ng-click="submitOrder()" ng-disabled="orderFormForm.$invalid || (isOrderGood === 0)" ng-class="{'processing': isOrderGood === 0}">
											<span ng-if="(isOrderGood === undefined) || (isOrderGood === -1)">Submit</span>
											<span ng-if="(isOrderGood === 0)">
												<span class="processing-spinner inline-spinner"></span>
												 Processing..
											</span>
										</button>
									</div>
								</div>
								<div class="gray-box align-center">
									<span ng-if="!isOrderGood">
										<?php if (get_field('disclaimer')) { ?>
										<h5><?php echo get_field('disclaimer')?></h5>
										<?php } ?>
									</span>
									<div class="success" ng-if="isOrderGood === 1">
										<h4>Estimate request submitted successfully.</h4>
										<a class="print-styles-toggle">View printable order summary</a>
									</div>
									<h4 ng-if="isOrderGood === -1"><strong>Error</strong> – Unable to process estimate request.</h4>
								</div>
							</div>
						</section>
					</form>
			</section>
		<section class="debug">
			<h4>$scope.isOrderGood</h4>
			<pre class="debug">{{isOrderGood | json}}</pre>
			<h4>$scope.orderData</h4>
			<pre class="debug">{{orderData | json}}</pre>
			<pre class="debug">{{totalEstimate}}</pre>
			<pre class="debug">{{orderMeta | json}}</pre>
			<!-- <pre class="debug">{{orderMeta.orderPickupDate | json}}</pre> -->
			<!-- <pre class="debug">{{orderMeta.orderReturnDate | json}}</pre> -->
			<!-- <h5 class="debug">Debug</h5> -->
			<!-- <pre class="debug">{{itemData | json}}</pre> -->
<!-- 			<h5 class="debug">orderFormForm</h5>
			<pre class="debug">{{orderFormForm | json}}</pre>
 -->	<!-- 		<h5 class="debug">Scope</h5>
			<pre class="debug">{{}}</pre> -->
		</section>
	</div>

</div>

</div>



<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
<?php endwhile; ?>
