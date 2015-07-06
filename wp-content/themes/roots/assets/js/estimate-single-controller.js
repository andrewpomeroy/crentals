app.controller('estimateSingleCtrl', ['$scope', 'GSLoader', '$http', '$modal', function($scope, GSLoader, $http, $modal) {

	$scope.isAdmin = isAdmin;

	$scope.minDate = null;

	$scope.dateOptions = {
		'year-format': "'yy'",
		'starting-day': 1
	};

	$scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'shortDate', 'MM/dd/yyyy'];
	$scope.format = $scope.formats[3];

	var fixShortDate = function(date, correctYear) {
		// MM/dd/yyy
		if (date.match(/\d\d\/\d\d\/\d\d\d\d/g)) {
			var newDate = new Date();
			newDate.setYear(date.substr(6, 4));
			newDate.setMonth(parseInt(date.substr(0, 2)) - 1);
			newDate.setDate(date.substr(3, 2));
			return newDate;
		}
		// MM/dd
		else if (date.match(/\d\d\/\d\d/g)) {
			var newDate = new Date();
			newDate.setYear(correctYear);
			newDate.setMonth(parseInt(date.substr(0, 2)) - 1);
			newDate.setDate(date.substr(3, 2));
			return newDate;
		}
		else {
			return date;
		}
	}

	// --- INITIALIZE ---
	var init = function() {
		// Add back dates to items, calculate item total
		angular.forEach($scope.orderData.items, function(item, key) {
			if (!item.startDate) {
				item.startDate = new Date($scope.orderMeta.orderPickupDate.date);
			}
			else {
				item.startDate = fixShortDate(item.startDate, $scope.orderMeta.orderPickupDate.date.getFullYear());
				// item.startDate = new Date(item.startDate);
			}
			if (!item.endDate) {
				item.endDate = new Date($scope.orderMeta.orderReturnDate.date);
			}
			else {
				item.endDate = fixShortDate(item.endDate, $scope.orderMeta.orderReturnDate.date.getFullYear());
				// item.endDate = new Date(item.endDate);
			}
			if (typeof(item.startDate) === "string") {
				item.startDate = new Date(item.startDate);
			}
			if (typeof(item.endDate) === "string") {
				item.endDate = new Date(item.endDate);
			}
			item.days = parseInt((item.endDate - item.startDate) / $scope.one_day) + 1;
			$scope.changeQty(item);
		});
		$scope.calcRentalDates(true);
	// 	$scope.orderData = theOrderData;
	// 	$scope.orderMeta = theOrderData.orderMeta;
	// 	delete $scope.orderData.orderMeta;
	// 	$scope.printOrder = function() {
	// 		window.print();
	// 	}
	};
	init();

}]);
