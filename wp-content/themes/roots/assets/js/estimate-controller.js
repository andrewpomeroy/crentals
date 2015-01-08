app.controller('estimateForm', ['$scope', '$filter', 'GSLoader', '$http', '$modal', function($scope, $filter, GSLoader, $http, $modal) {

	$scope.dataStatus = null;

	$scope.calcRentalDates = function() {
		$scope.orderMeta.totalRentalDays = parseInt(($scope.orderMeta.orderReturnDate.date - $scope.orderMeta.orderPickupDate.date)/one_day) + 1;
		resetTotal();
		for(var i = 0; i < $scope.itemData.length; i++) {
			for (var x = 0; x < $scope.itemData[i].subcats.length;x++) {
				for (var y = 0; y < $scope.itemData[i].subcats[x].items.length;y++) {
					var _item = $scope.itemData[i].subcats[x].items[y];
					$scope.flushIndividualDate(_item);
					$scope.changeQty(_item);
					addToTotal(_item);
				}
			}
		}
	};

	// $scope.GSurl = 'https://spreadsheets.google.com/feeds/list/0Ase_pp75HN9MdGlpbkZmZmVTUGU2MElkOEktVUxyQWc/od6/public/values?alt=json';
	$scope.GSurl = globalGSUrl;
	$scope.dataRetreived = {};

	$scope.changeQty = function(item) {

		// ---  calculate Days/Week
		var daysCounted;
		// Parse out sale items
		if (item.daysweek == 0) {
			daysCounted = 1;
		}
		else {
			// Otherwise, begin with assumption of normal day-counting
			daysCounted = item.days;
			// When days/week restriction is enabled for this item..
			if (item.daysweek < 7) {
				// Start out with the number of days counted in all complete weeks
				daysCounted = Math.floor(item.days/7) * item.daysweek;
				// In the case that the days in final week exceed specified days/week..
				if ((item.days%7) > item.daysweek) {
					// Add only the specified days in final week
					daysCounted += parseInt(item.daysweek);
				}
				// Otherwise, add the entire remainder of days in the final week
				else {daysCounted += (item.days % 7);}
			}
		}
		// Total = Amount * Rate * Time
		item.estimate = item.qty * item.rate * daysCounted;
		// $scope.recalcTotalEstimate();
	};

	// Reset everything
	$scope.resetForm = function() {
		// $scope.getItemDataGS();
		init();
	};

	var addQuantityToOrderObj = function(item) {
		if (item.qty > 0) {
			$scope.orderData.items.push(item);
		}
	};

	var addOne = function(item) {
		item.qty = 1;
	}

	var cleanItemProperties = function(item) {
		// debugger;
		if ((item.startDate.toDateString() === $scope.orderMeta.orderPickupDate.date.toDateString()) && (item.endDate.toDateString() === $scope.orderMeta.orderReturnDate.date.toDateString())) {
			delete item.startDate;
			delete item.endDate;
		}
		else {
			item.startDate = $filter('date')(item.startDate, 'MM/dd');
			item.endDate = $filter('date')(item.endDate, 'MM/dd');
		}
		if (!item.clientnotes.length) {
			delete item.clientnotes;
		}
		delete item.customRentalPeriod;
		delete item.notes;
		delete item.description;
		delete item.link;
		delete item.type;
		delete item.id;
		delete item.image;
	}

	$scope.printOrder = function() {
		window.print();
	}

	$scope.ajaxSuccess = function() {
		$scope.isOrderGood = 1;
	}
	$scope.ajaxFail = function() {
		$scope.isOrderGood = -1;
	}
	$scope.responseVar = 0;
	// Submit Order to Wordpress Backend
	$scope.submitOrder = function() {
		$scope.isOrderGood = 0;
		$scope.orderMeta.totalEstimate = $scope.totalEstimate;
		$scope.orderData = {
			orderMeta: $scope.orderMeta,
			items: []
		}
		// loopThroughItems([addOne, addQuantityToOrderObj]);
		loopThroughItems([addQuantityToOrderObj]);
		// for (var item in $scope.orderData.items) {
		// 	cleanItemProperties(item);
		// }
		angular.forEach($scope.orderData.items, function(value, key) {
			// debugger;
			cleanItemProperties(value);
		});
		var newDate = new Date();
		var titleStr = $scope.orderMeta.companyName + " – " + $scope.orderMeta.jobName + " (" + newDate.toLocaleString() + ")";
		var contentStr = JSON.stringify($scope.orderData);
		debugger;
		// js_create_post(titleStr, contentStr, $scope.ajaxSuccess, $scope.ajaxFail);
		$scope.js_create_post(titleStr, contentStr, $http, $scope);
		// $scope.orderData = [];
	};
	$scope.js_create_post = function(title, content, $http, $scope) {

		$http({
			url: '/wp-admin/admin-ajax.php',
			method: "POST",
			params: {action : "make_est_post", title: title, content: content}
		}).success(function(response) {
			// debugger;
			console.log(response);
			$scope.isOrderGood = 1;
		}).error(function(data) {
			$scope.isOrderGood = -1;
		});
	};

	// --- DATEPICKER FUNCTIONS ---
 var one_day = 1000*60*60*24;

	$scope.incrementIndividualDate = function(date, amount) {
		console.log(date);
		date.setDate(date.getDate() + amount);
		console.log(date);
		$scope.calcRentalDates();
	};

	$scope.flushIndividualDate = function(item, disableCustomRentalPeriod) {
		// console.log("ITEM OBJECT TO BE FLUSHED:");
		// console.log(item);
		if (disableCustomRentalPeriod) {
			item.customRentalPeriod = false;
		}
		if (item.customRentalPeriod) {
			if ((!item.startDate) && (!item.endDate)) {
				console.log("no item start/end dates");
				item.startDate = new Date($scope.orderMeta.orderPickupDate.date);
				item.endDate = new Date($scope.orderMeta.orderReturnDate.date);
			}
			if (!item.endDate) {
				console.log("no end date");
				item.endDate = angular.copy(item.startDate);
			}
			if (!item.startDate || (item.startDate > item.endDate)) {
				console.log("no start date, or start date after end date");
				item.endDate = angular.copy(item.startDate);
			}
			if (item.endDate) {
				console.log("endDate: " + item.endDate);
			}
			if (item.startDate && item.endDate) {
			}
			else {
				console.log('nullifying '+item.name);
				item.days = null;
			}
			item.days = parseInt((item.endDate - item.startDate) / one_day) + 1;
		}
		else {
			// console.log("setting default dates on: ");
			// console.log(item.name);
			// console.log("ITEM-----BEFORE");
			// console.log(item);
			item.startDate = new Date($scope.orderMeta.orderPickupDate.date);
			item.endDate = new Date($scope.orderMeta.orderReturnDate.date);
			// console.log("ITEM-----AFTER");
			// console.log(item);
			item.days = $scope.orderMeta.totalRentalDays;
			// console.log(item.startDate);
		}
		// item.days = ($scope.orderMeta.totalRentalDays > 0) ? $scope.orderMeta.totalRentalDays : 0;
		// item.days = ($scope.orderMeta.totalRentalDays > 0) ? $scope.orderMeta.totalRentalDays : 0;
	};

	resetTotal = function() {
		$scope.totalEstimate = 0;
	};
	addToTotal = function(item) {
		if (item.qty > 0) {
			console.table(item);
			$scope.totalEstimate += item.estimate;
		}
	};
	$scope.calcTotal = function() {
		resetTotal();
		loopThroughItems([addToTotal]);
	};

	loopThroughItems = function(itemFunctions) {
		// console.log($scope.itemData);
		for (var group in $scope.itemData) {
			for (var subcat in $scope.itemData[group].subcats) {
				for (var item in $scope.itemData[group].subcats[subcat].items) {
					var theItem = $scope.itemData[group].subcats[subcat].items[item];
					// loop through specific item functions and execute each on found item
					for (var passedFunction in itemFunctions) {
						itemFunctions[passedFunction](theItem);
						// console.log("Called: ");
						// console.log(itemFunctions[passedFunction]);
						// console.log("with: ");
						// console.log(theItem);
					}
				}
			}
		}
	};

	$scope.categoryHasItems = function(category) {
		for (var group in $scope.itemData) {
			if ($scope.itemData[group].type === category.type) {
				for (var subcat in $scope.itemData[group].subcats) {
					if ($scope.itemData[group].subcats[subcat].items.length > 0) {
						return true;
					}
				}
			}
		}
		return false;
	};


	$scope.showWeeks = true;
	$scope.toggleWeeks = function () {
		$scope.showWeeks = ! $scope.showWeeks;
	};

	$scope.clear = function () {
		$scope.dt = null;
	};

	// $scope.toggleMin = function() {
	// 	$scope.minDate = ( $scope.minDate ) ? null : new Date();
	// };
	// $scope.toggleMin();
	$scope.minDate = null;

	$scope.dateOptions = {
		'year-format': "'yy'",
		'starting-day': 1
	};

	$scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'shortDate', 'MM/dd/yyyy'];
	$scope.format = $scope.formats[3];

	// --- INITIALIZE ---
	var init = function() {
		$scope.orderMeta = {};
		$scope.totalEstimate = 0;
		var todayDate = new Date();
		// $scope.calcRentalDates();
		// $scope.getItemDataGS(function() {
		$scope.orderMeta.orderPickupDate = {
			opened: false,
			date: todayDate
		};
		$scope.orderMeta.orderReturnDate = {
			opened: false,
			date: todayDate
		};
		GSLoader.getItemDataGS(globalGSUrl).then(function(response) {
			$scope.itemData = response;
			$scope.dataStatus = "loaded";
			$scope.calcRentalDates();
		});

			// $scope.calcRentalDates();

		// (function(data, status, headers, config) {
					// console.log("AJAX
		// });
	};
	init();

}]);
