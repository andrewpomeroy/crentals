app.controller('productCategory', ['GSLoader', '$scope', '$http', '$modal', function(GSLoader, $scope, $http, $modal) {

$scope.getProducts = function(category) {

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

$scope.getTheStuff = function(category) {
	GSLoader.getItemDataGS(globalGSUrl).then(function(response) {
		// debugger;
		$scope.itemData = response;
		$scope.getProducts(category);
		buildProductPageObject($scope.productGroup);
	});
};

$scope.hasProductPage = function(id) {
	if ($scope.productPageIDs.indexOf(id) === -1) {
		return false;
	}
	else {return true};
};

var buildProductPageObject = function() {
	debugger;
	var counter = 0;
	$scope.productPageObject = [];
	angular.forEach($scope.productGroup.items, function(value, key) {
		if ($scope.hasProductPage(value.id)) {
			$scope.productPageObject.push(value);
		}
	});
};

			$scope.category = "vehicles";
			$scope.getTheStuff("vehicles");
$scope.productPageIDs = "";

}]);

// app.directive('getCategory', function() {
//   return {
// 	restrict: 'A',
// 	link: function($scope, elem, attr) {
// 		// debugger;
// 		var observer = function(value) {
// 			$scope.category = value;
// 			$scope.getTheStuff(value);
// 			// attr.$observe();
// 			// debugger;
// 		};
// 		attr.$observe('getCategory', observer);
// 		// debugger;
// 	}
//   };
// });

// app.directive('getProductIds', function() {
//   return {
// 	restrict: 'A',
// 	link: function($scope, elem, attrs) {
// 		// debugger;
// 		$scope.productPageIDs = attrs.value.split(",");
// 	}
//   };
// });

// app.directive('thumbSrc', function() {
// 	return {
// 		restrict: 'A',
// 		link: function(scope, element, attr) {
// 			// debugger;
// 			var observer = function(value) {
//   				if (value) {
// 					var file = value;
// 					var appendage = "-150x150.jpg";
// 					file = file.replace(/(\.[\w\d_-]+)$/i, appendage);
// 					angular.element(element).attr('src', file).removeClass('no-image');
// 					// attr.$observe();
// 					// debugger;
// 				}
// 			};
// 			attr.$observe('thumbSrc', observer);
// 			// debugger;
// 		}
// 	};
// });