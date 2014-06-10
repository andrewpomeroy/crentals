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
				$scope.products = angular.copy(group);
				console.log("Products: ", $scope.products);
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
		link: function(elem, attrs) {
			var filename = attrs['thumb-src'];
			var appendage = "-150x150";
			filename = filename.replace(/(\.[\w\d_-]+)$/i, appendage);
			elem.attr('src', filename);
		}
	};
});