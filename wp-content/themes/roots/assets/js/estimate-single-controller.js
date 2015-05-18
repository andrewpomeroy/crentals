app.controller('estimateSingleCtrl', ['$scope', 'GSLoader', '$http', '$modal', function($scope, GSLoader, $http, $modal) {

	$scope.isAdmin = isAdmin;

	$scope.minDate = null;

	$scope.dateOptions = {
		'year-format': "'yy'",
		'starting-day': 1
	};

	$scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'shortDate', 'MM/dd/yyyy'];
	$scope.format = $scope.formats[3];

	// --- INITIALIZE ---
	var init = function() {
		// Add back dates to items, calculate item total
		angular.forEach($scope.orderData.items, function(item, key) {
			if (!item.startDate) {
				item.startDate = new Date($scope.orderMeta.orderPickupDate.date);
			}
			else {
				item.startDate = new Date(item.startDate);
			}
			if (!item.endDate) {
				item.endDate = new Date($scope.orderMeta.orderReturnDate.date);
			}
			else {
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
