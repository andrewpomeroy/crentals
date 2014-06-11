app.controller('productCategory', ['GSLoader', '$scope', '$http', '$modal', function(GSLoader, $scope, $http, $modal) {

var getProducts = function(category) {

	$scope.category = category;

	console.log("Category");
	console.log($scope.category);
	if ($scope.category !== "") {
		console.log("$scope.itemData", $scope.itemData);
		angular.forEach($scope.itemData, function(group) {
			console.log(group);
			if (group.id === $scope.category) {
				$scope.productGroup = angular.copy(group);
				console.log("Products: ", $scope.productGroup);
			}
		});
	}
};

var init = function() {
	GSLoader.getItemDataGS(globalGSUrl).then(function(response) {
		$scope.itemData = response;
		getProducts('vehicles');
	});
};

init();

}]);

app.directive('getCategory', function() {
  return {
	restrict: 'A',
	link: function($scope, elem, attrs) {
	$scope.category = attrs['get-category'];
	}
  };
});

app.directive('thumbSrc', function() {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			attrs.$observe('thumbSrc', function(value) {
				if (value) {
					var file = value;
					var appendage = "-150x150.jpg";
					file = file.replace(/(\.[\w\d_-]+)$/i, appendage);
					angular.element(element).attr('src', file).removeClass('no-image');
				}
			});			
		}
	};
});