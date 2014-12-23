app.controller('estimateSingleCtrl', ['$scope', 'GSLoader', '$http', '$modal', function($scope, GSLoader, $http, $modal) {

	// --- INITIALIZE ---
	var init = function() {
		$scope.orderData = theOrderData;
		$scope.printOrder = function() {
			window.print();
		}

		// debugger;

			// $scope.calcRentalDates();

		// (function(data, status, headers, config) {
					// console.log("AJAX
		// });
	};
	init();

}]);
