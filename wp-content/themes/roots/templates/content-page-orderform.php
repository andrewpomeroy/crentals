<?php while (have_posts()) : the_post(); ?>
	<?php the_content(); ?>

<? /*	<div class="app-container">
		<form action="" id="main-form">
			
		</form>
	</div>
	<div class="templates">
		<!-- table-wrap -->
			<section class="table" data-template-item="table-wrap">
				<section class="table-header" data-template-inject="table-header">
				</section>
				<section class="table-contents" data-template-inject="table-contents">
				</section>
			</section>
		<!-- form-section -->
		<section class="form-section" data-template-item="">
			<section class="section-header">
				<h3 class="item-heading" data-value-inner="section-heading"></h3>
				<div class="section-contents" data-template-item="section-contents">
					<div class="name">
						<p class="cell-label" data-perms-client="r" data-value-inner="name"></p>
					</div>
					<div class="quantity">
						<p class="cell-label" data-perms-client="r"></p>
						<input class="cell-label" data-perms-client="rw" />
					</div>
					<div class="rate">
						<p class="cell-label" data-perms-client="r" data-value-inner="rate"></p>
					</div>
					<div class="days-wk" data-default="7"></div>
					<div class="days" data-input="input"></div>
					<div class="estimate"></div>
				</div>
			</section>
			<!-- heading -->
			<div class="heading">
				<h3 class="item-heading"></h3>
			</div>
			<!-- item -->
			<div data-template-item="item" class="item">
			</div>
			<!-- item-inner -->
			<input data-template-item="item-input" class="cell-input" />
			<p data-template-item="item-ro" class="cell-ro-value" data-value-inner="true"></p>

		</section>
	</div>

*/ ?>

	<div ng-app="myApp">

		<div ng-controller="orderForm">
			<section class="main-app">
				<ng-form name="orderFormForm" novalidate>

					<section class="applicant-info order-form">
						<div class="form-group"><label for="Job Name" class="control-label">Name</label><input type="text" class="form-control"></div>
					</section>
					<table class="order-form table">
						<thead>
							<tr>
								<td>Item</td>
								<td>Quantity</td>
								<td>Rate</td>
								<td>Days/Week</td>
								<td>Estimate</td>
								<td>Notes</td>
							</tr>
						</thead>
						<tbody ng-repeat="group in itemData">
							<tr class="section-heading-row">
								<td colspan="7" class="section-heading"><h3 class="section-heading">{{group.type}}</h3></td>
							</tr>
							<tr ng-repeat="item in group.items" class="item-row" ng-class="{error: (item.estimate === null)}">
								<td class="data static">{{item.name}}</td>
								<td class="data dynamic input" ng-class="{'has-error': (
								(orderFormForm[item.name + '_qty'].$error.required) )
							}"><input type="number" ng-model="item.qty" ng-change="changeQty(item)" dynamic-name="item.name + '_qty'"/ ng-pattern="/[0-9]{1,6}/"><span ng-show="orderFormForm[item.name + '_qty'].$error.required">Required</span><span ng-show="orderFormForm[item.name + '_qty'].$invalid">integer</span></td>
								<td class="data static">{{item.rate | currency:"$"}}</td>
								<td class="data static">{{item.daysweek}}</td>
								<td class="data dynamic"><span ng-show="!!(item.estimate)">{{item.estimate | currency:"$"}}</span></td>
								<td class="data dynamic"><span><input ng-model="item.notes" dynamic-name="item.name + '_notes'"/></span></td>
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
			</form>
			</section>
			<section class="debug">
				<h5 class="debug">Debug</h5>
				<pre class="debug">{{itemData | json}}</pre>
				<h5 class="debug">orderFormForm</h5>
				<pre class="debug">{{orderFormForm | json}}</pre>
			</section>
			<button class="btn" ng-click="clickButton()">Super Button</button>

		</div>

	</div>

		<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
	<?php endwhile; ?>
