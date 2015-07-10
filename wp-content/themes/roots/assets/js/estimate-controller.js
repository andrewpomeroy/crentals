app.controller('estimateForm', ['$scope', '$filter', 'GSLoader', '$http', '$modal', '$timeout', function($scope, $filter, GSLoader, $http, $modal, $timeout) {

	$scope.isAdmin = isAdmin;

	$scope.dataStatus = null;

	$scope.one_day = 1000*60*60*24;

	$scope.calcRentalDates = function(single) {
		$scope.orderMeta.totalRentalDays = parseInt(($scope.orderMeta.orderReturnDate.date - $scope.orderMeta.orderPickupDate.date)/$scope.one_day) + 1;
		$scope.resetTotal();
		if ($scope.itemData) {
			for(var i = 0; i < $scope.itemData.length; i++) {
				for (var x = 0; x < $scope.itemData[i].subcats.length;x++) {
					for (var y = 0; y < $scope.itemData[i].subcats[x].items.length;y++) {
						var _item = $scope.itemData[i].subcats[x].items[y];
						$scope.flushIndividualDate(_item);
						$scope.changeQty(_item);
						$scope.addToTotal(_item);
					}
				}
			}
		}
		else
		{
			for(var i = 0; i < $scope.orderData.items.length; i++) {
				var _item = $scope.orderData.items[i];
				$scope.flushIndividualDate(_item);
				$scope.changeQty(_item);
				$scope.addToTotal(_item);
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
		if ((new Date(item.startDate).toDateString() === $scope.orderMeta.orderPickupDate.date.toDateString()) && (new Date(item.endDate).toDateString() === $scope.orderMeta.orderReturnDate.date.toDateString())) {
			delete item.startDate;
			delete item.endDate;
		}
		else {
			// Safari is not feelin this.
			item.startDate = $filter('date')(item.startDate, 'MM/dd/yyyy');
			item.endDate = $filter('date')(item.endDate, 'MM/dd/yyyy');
		}
		if (item.clientnotes && !item.clientnotes.length) {
			delete item.clientnotes;
		}
		if (!item.customRentalPeriod) {
			delete item.customRentalPeriod;
		}
		delete item.notes;
		delete item.description;
		delete item.link;
		delete item.type;
		delete item.id;
		delete item.image;
		delete item.edit;
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
	var newDate,
		titleStr,
		contentStr;
	// Submit Order to Wordpress Backend
	$scope.submitOrder = function(obj) {
		var draft = obj.draft;
		if (isSingle()) {
			draft = !isSubmitted();
			$scope.orderData.orderMeta = angular.copy($scope.orderMeta);
		}
		var newDate = new Date();
		titleStr = ($scope.orderMeta.companyName ? ($scope.orderMeta.companyName + " – ") : "") + ($scope.orderMeta.jobName || "") + " (" + newDate.toLocaleString() + ")";
		if (!draft) {
			titleStr = "SUBMITTED: " + titleStr;
		}
		if (isSingle())
		{
			$scope.orderMeta.revision = ++$scope.orderMeta.revision || 1;	
			titleStr = titleStr + " (Revision " + $scope.orderMeta.revision + ")";
			// var headStr = $('head title').html();
			// if (headStr.match(/Revision \d*/)) {
			// 	headStr = headStr.replace(/Revision \d*/, "Revision " + ($scope.orderMeta.revision));
			// 	// titleStr = titleStr.replace(/Revision \d*/, headStr);
			// 	$('head title').html(headStr);
			// }
		}
		$('head title').html(titleStr);
		
		if (!isSingle() && !draft) {
			$scope.isFinalOrderGood = 0;
		}
		else
		{
			$scope.isOrderGood = 0;
			$scope.orderMeta.totalEstimate = $scope.totalEstimate;
			// Init stuff for creating new estimates
			if (!isSingle()) {
				$scope.orderData = {
					orderMeta: $scope.orderMeta,
					items: []
				}
				// loopThroughItems([addOne, addQuantityToOrderObj]);
				loopThroughItems([addQuantityToOrderObj]);
				// for (var item in $scope.orderData.items) {
				// 	cleanItemProperties(item);
				// }
			}
			// orderData, title, dates, etc. already exists for estimates being revised
			angular.forEach($scope.orderData.items, function(value, key) {
				cleanItemProperties(value);
			});
			$scope.orderData.orderMeta.orderPickupDate.date = Date.parse($scope.orderData.orderMeta.orderPickupDate.date);
			$scope.orderData.orderMeta.orderReturnDate.date = Date.parse($scope.orderData.orderMeta.orderReturnDate.date);
			// contentStr = JSON.stringify($scope.orderData);
			contentStr = jsonpack.pack($scope.orderData);
		}
		$scope.js_create_post(titleStr, contentStr, draft, $http, $scope, (isSingle() ?  currentPostId : -1), true);
	};
	$scope.js_create_post = function(title, content, draft, $http, $scope, currentPostId, compressed) {

		// console.log("current post id: ", currentPostId)

		$http({
			url: '/wp-admin/admin-ajax.php',
			method: "POST",
			params: {action : "make_est_post", title: title, content: content, draft: draft, currentPostId: currentPostId, compressed: compressed}
		}).success(function(response) {
			// console.log("yup:", response);
			$scope.isOrderGood = 1;
			if (!draft) {
				$scope.isFinalOrderGood = 1;
			}
			$scope.successScroll('#orderActionsInfoTop');
		}).error(function(response) {
			// console.log("nope:", response);
			$scope.isOrderGood = -1;
			if (!draft) {
				$scope.isFinalOrderGood = -1;
			}
		});
	};

	$scope.successScroll = function(thetarget) {
		$timeout(function() {
		    $('html, body').animate({
				scrollTop: $(thetarget).offset().top
		    }, 900);
		}, 100);
	
	}

	// --- DATEPICKER FUNCTIONS ---

	$scope.incrementIndividualDate = function(date, amount, originalDate, item) {
		date.setDate(date.getDate() + amount);
		// Set items object to either the set of hierarchically organized categories/items (Estimate Form) or flat array of items (Single Estimate View)
		// var items = $scope.itemData || $scope.orderData.items; 
		item.customRentalPeriod = true;
		$scope.calcRentalDates(isSingle());
	};

	$scope.flushIndividualDate = function(item, disableCustomRentalPeriod) {
		// console.log("ITEM OBJECT TO BE FLUSHED:");
		// console.log(item);
		if (disableCustomRentalPeriod) {
			item.customRentalPeriod = false;
		}
		if (item.customRentalPeriod) {
			if ((!item.startDate) && (!item.endDate)) {
				// console.log("no item start/end dates");
				item.startDate = new Date($scope.orderMeta.orderPickupDate.date);
				item.endDate = new Date($scope.orderMeta.orderReturnDate.date);
			}
			if (!item.endDate) {
				// console.log("no end date");
				item.endDate = angular.copy(item.startDate);
			}
			if (!item.startDate || (item.startDate > item.endDate)) {
				// console.log("no start date, or start date after end date");
				item.endDate = angular.copy(item.startDate);
			}
			if (item.endDate) {
				// console.log("endDate: " + item.endDate);
			}
			if (item.startDate && item.endDate) {
			}
			else {
				// console.log('nullifying '+item.name);
				item.days = null;
			}
			item.days = parseInt((item.endDate - item.startDate) / $scope.one_day) + 1;
		}
		else {
			item.startDate = new Date($scope.orderMeta.orderPickupDate.date);
			item.endDate = new Date($scope.orderMeta.orderReturnDate.date);
			item.days = $scope.orderMeta.totalRentalDays;
		}
	};

	$scope.resetTotal = function() {
		$scope.totalEstimate = 0;
	};
	$scope.addToTotal = function(item) {
		if (item.qty > 0) {
			$scope.totalEstimate += item.estimate;
		}
	};
	$scope.calcTotal = function() {
		$scope.resetTotal();
		loopThroughItems([$scope.addToTotal]);
	};

	loopThroughItems = function(itemFunctions) {
		// console.log($scope.itemData);
		if ($scope.itemData) {
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
		}
		else {			
			angular.forEach($scope.orderData.items, function(item, key) {
				for (var passedFunction in itemFunctions) {
					itemFunctions[passedFunction](item);
				}
			});
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

	$scope.removeItem = function($index) {
		$scope.orderData.items.splice($index, 1);
		$scope.calcTotal();
	}
	$scope.addItem = function() {
		$scope.orderData.items.push({
			name: "New Item",
			qty: 1,
			rate: 0,
			daysweek: 7,
			// startDate: $scope.orderMeta.orderPickupDate,
			// endDate: $scope.orderMeta.orderReturnDate,
			edit: true
		});
		$scope.flushIndividualDate($scope.orderData.items[$scope.orderData.items.length - 1]);
	}

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
		if (!isSingle()) {
			$scope.orderMeta = {};
			$scope.totalEstimate = 0;
			var todayDate = new Date();
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
		}
		else {
			$scope.orderData = theOrderData;
			$scope.orderMeta = $scope.orderData.orderMeta;
			$scope.orderMeta.orderPickupDate.date = new Date($scope.orderMeta.orderPickupDate.date);
			$scope.orderMeta.orderReturnDate.date = new Date($scope.orderMeta.orderReturnDate.date);
			$scope.orderMeta.totalRentalDays = parseInt(($scope.orderMeta.orderReturnDate.date - $scope.orderMeta.orderPickupDate.date) / $scope.one_day) + 1;
			delete $scope.orderData.orderMeta;
			$scope.printOrder = function() {
				$scope.orderData.orderMeta = angular.copy($scope.orderMeta);
				setTimeout(function() {
					window.print();
				}, 20);
			}
		}

	};
	init();

}]);
