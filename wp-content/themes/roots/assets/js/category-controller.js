app.controller('productCategory', ['$scope', '$http', '$modal', function($scope, $http, $modal) {

var getProducts = function() {

	$scope.category = 'vehicles';

	console.log("Category");
	console.log($scope.category);
	if ($scope.category !== "") {
		console.log("$scope.itemData", $scope.itemData);
		for (var group in $scope.itemData) {
			console.log(group);
			if (group.id === $scope.category) {
				$scope.products = angular.copy(group);
			}
		}
	}
};

var init = function() {
	getProducts();
}

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