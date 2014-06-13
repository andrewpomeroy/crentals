var app = angular.module('myApp', ['ui.bootstrap']);

// angular.module('myReverseModule', [])
//   .filter('nozero', function() {
//     return function(input, uppercase) {
//       input = input || '';
//       var out = "";
//       for (var i = 0; i < input.length; i++) {
//         out = input.charAt(i) + out;
//       }
//       // conditional based on optional argument
//       if (uppercase) {
//         out = out.toUpperCase();
//       }
//       return out;
//     };
//   });

app.service('dataTransform', function() {

	var valueTypeBase = {
		type: "item",
		name: "Unnamed",
		qty: 0,
		rate: 0,
		customRentalPeriod: false,
		startDate: {
			opened: false,
			date: undefined
		},
		endDate: function() {return new Date();},
		days: undefined,
		daysweek: 7,
		notes: "",
		estimate: 0
	};

	return {

		baselineColVars: function(obj) {
			var checkEmptySingle = function(key, value) {
				if (value === "") {
					if (valueTypeBase[key])
					{
						value = valueTypeBase[key];
					}
				}
				return value;
			};
			var checkEmptyItem = function(item) {
				for (var key in valueTypeBase) {
					// console.log("checking key w/ item: ");
					// console.log(item);
					// console.log("key: "+key);
					if (!(item[key]) || (item[key] === "")) {
						// console.table(item);
						item[key] = (valueTypeBase[key]);
						// console.log("Added: "+item[key]+" to "+key);
					}
				}
				return item;
			};
			for (var entry in obj) {
				if (obj[entry].items) {
					for (var item in obj[entry].items)
					{
						item = checkEmptyItem(obj[entry].items[item]);
					}
				}
			}
			return obj;
		},

		flattenGSFeed: function(obj) {
			var output = [];
			for (var entry in obj) {
				if (obj.hasOwnProperty(entry)) {
					output[entry] = {};
					for (var key in obj[entry]) {
						if (obj[entry].hasOwnProperty(key)) {
							if (key.slice(0,4) === "gsx$") {
								// var someobject;
								var newname = key.slice(4);
								output[entry][newname] = obj[entry][key].$t;
								// console.log(output);
							}
						}
					}
				}
			}
			return output;
		},

		groupObjects: function(obj) {
			var output = [];
			output.push({
				type: 'uncategorized',
				subcats: [
					{
						"items": []
					}
				]
			});

			for (var entry in obj) {
				if (obj[entry].type === "Section") {
					// Create Obj for new Section
					output.push({
						// Type == name of section
						type: obj[entry].name,
						id: obj[entry].id,
						subcats: [
							{
								items: []
							}
						]
					});
				}
				if (obj[entry].type === "Subcategory") {
					output[output.length - 1].subcats.push({
						type: obj[entry].name,
						id: obj[entry].id,
						items: []
					});
				}
				else if (obj[entry].type === "Item") {
					// Are there any subcats in the last Section we created?
					// debugger;
					// if (output[output.length - 1].subcats.length > 0) {
						// Put em in there
						var subCatIndex = output[output.length - 1].subcats.length - 1;
						output[output.length - 1].subcats[subCatIndex].items.push(obj[entry]);
					// }
					// else {
					// 	// Otherwise, just put em in the Section.
					// 	output[output.length - 1].items.push(obj[entry]);
					// }
				}
			}
			return output;
		}

	};
});

app.service('GSLoader', ['dataTransform', '$http', '$q', function(dataTransform, $http, $q) {

	// FALLBACK DATA

	this.fakeItemData = {
		groups: [
		{
			"type": "uncategorized",
			"id": "uncategorized",
			"subcats" : [
				{
					"items": []
				}
			]
		},
		{
			"type": "Vehicles",
			"items": [
			{
				"type": "Item",
				"name": "Ford Fiesta",
				"rate": "5",
				"daysweek": ""
			},
			{
				"type": "Item",
				"name": "Abrams Tank",
				"rate": "400",
				"daysweek": ""
			}
			]
		}
		]
	};

	// this.dataGet = $q.defer();

	this.getItemDataGS = function(url) {
		var responsePromise = $http.get(url).then(function(response) {
			// console.log("Response Promise in SERVICE:", response);
			var transformedData = dataTransform.baselineColVars(
				dataTransform.groupObjects(
					dataTransform.flattenGSFeed(
						response.data.feed.entry
						)
					)
				);
			return transformedData;
		});
		return responsePromise;
		// responsePromise.success(function(data, status, headers, config) {
			// debugger;
	    	// $scope.itemData = GSLoader.baselineColVars(groupObjects(flattenGSFeed($scope.dataRetreived)));

			// return data.feed.entry;
		// });
		// responsePromise.error(function(data, status, headers, config) {
		// 	console.log("AJAX failed!");
			// return this.fakeItemData.groups;
		// });
	};	
}]);

app.controller('mainCtrl', ['$scope', 'GSLoader', '$http', '$modal', function($scope, GSLoader, $http, $modal) {

	// MODAL STUFF

	 $scope.infoModal = function (item) {

		var modalInstance = $modal.open({
		  templateUrl: '/wp-content/themes/roots/templates/myModalContent.html',
		  controller: ModalInstanceCtrl,
		  item: item,
		  resolve: {
			theItem: function () {
			  return item;
			}
		  }
		});
	}

	var ModalInstanceCtrl = function ($scope, $modalInstance, theItem) {

	$scope.theItem = theItem;

	  $scope.ok = function () {
		$modalInstance.close();
	  };

	};

}]);

app.directive('dynamicName', function($compile, $parse) {
  return {
	restrict: 'A',
	terminal: true,
	priority: 100000,
	link: function(scope, elem) {
	  var name = $parse(elem.attr('dynamic-name'))(scope);
	  elem.removeAttr('dynamic-name');
	  elem.attr('name', name);
	  $compile(elem)(scope);
	}
  };
});










