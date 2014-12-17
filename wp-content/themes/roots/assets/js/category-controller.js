app.controller('productCategory', ['GSLoader', '$scope', '$http', '$modal', function(GSLoader, $scope, $http, $modal) {

$scope.dataStatus = null;

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
		$scope.itemData = response;
		$scope.dataStatus = "loaded";
		$scope.getProducts(category);
	});
};

$scope.hasProductPage = function(id) {
	var theIndex = $scope.productPageIDs.indexOf(id);
	if (theIndex === -1) {
		return false;
	}
	else {return $scope.productPageGUIDs[theIndex]};
};


// $scope.category = "vehicles";
// $scope.getTheStuff($scope.category);
$scope.productPageIDs = "";
$scope.productPageGUIDs = {};


}]);

app.directive('getCategory', function() {
  return {
	restrict: 'A',
	link: function($scope, elem, attr) {
		var observer = function(value) {
			$scope.category = value;
			$scope.getTheStuff(value);
			// attr.$observe();
		};
		attr.$observe('getCategory', observer);
	}
  };
});

app.directive('getGuids', function() {
  return {
	restrict: 'A',
	link: function($scope, elem, attrs) {
		$scope.productPageGUIDs = attrs.value.split(",");
	}
  };
});

app.directive('getProductIds', function() {
  return {
	restrict: 'A',
	link: function($scope, elem, attrs) {
		$scope.productPageIDs = attrs.value.split(",");

	}
  };
});

app.directive('thumbSrc', function() {
	return {
		restrict: 'A',
		link: function(scope, element, attr) {
			var observer = function(value) {
  				if (value) {
					var file = value.trim();
					var appendage = "-150x84.jpg";
					file = file.replace(/(\.[\w\d_-]+)$/i, appendage);
					file = "/" + file.split("/").slice(3).join("/");
					angular.element(element).attr('src', file).removeClass('no-image');
					// attr.$observe();
				}
			};
			attr.$observe('thumbSrc', observer);
		}
	};
});