var app = angular.module('myApp', []);

app.controller('orderForm', function($scope, $http) {

	$scope.GSurl = 'https://spreadsheets.google.com/feeds/list/0Ase_pp75HN9MdGlpbkZmZmVTUGU2MElkOEktVUxyQWc/od6/public/values?alt=json';
	$scope.dataRetreived = {};

	$scope.getItemDataGS = function() {
		var responsePromise = $http.get($scope.GSurl);
		responsePromise.success(function(data, status, headers, config) {
			// console.table(data.feed.entry[0]);
			$scope.dataRetreived = data.feed.entry;
			$scope.debugData = flattenGSFeed($scope.dataRetreived);
		});
		responsePromise.error(function(data, status, headers, config) {
			alert("AJAX failed!");
		});
	};

	var flattenGSFeed = function(obj) {
		var output = {};
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
		// console.log(output);
		return output;
	};

	$scope.fakeItemData = {
		groups: [
			{
				type: "Vehicles",
				items: [
					{
						name: "Ford Pinto",
						rate: 50
					},
					{
						name: "Abrams Tank",
						rate: 600
					}
				]
			}
		]
	};


	// $scope.itemDataOutput = JSON.stringify($scope.fakeItemData, null, '\t');


	$scope.clickButton = function() {
		$scope.getItemDataGS();
	};

	var init = function() {
		$scope.getItemDataGS();
	};
	init();

	});

// <field-div> directive

// app.directive	