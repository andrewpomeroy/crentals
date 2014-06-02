var app = angular.module('myApp', ['ui.bootstrap']);

app.controller('productCategory', ['$scope', '$http', '$modal', function($scope, $http, $modal) {


	$scope.GSurl = 'https://spreadsheets.google.com/feeds/list/0Ase_pp75HN9MdGlpbkZmZmVTUGU2MElkOEktVUxyQWc/od6/public/values?alt=json';
	$scope.dataRetreived = {};

	$scope.fakeItemData = {
		groups: [
		{
			"type": "uncategorized",
			"items": []
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

	$scope.getItemDataGS = function() {
		var responsePromise = $http.get($scope.GSurl);
		responsePromise.success(function(data, status, headers, config) {
			// console.table(data.feed.entry[0]);
			// console.log("OBJECT:");
			// console.log(data);
			$scope.dataRetreived = data.feed.entry;
			$scope.itemData = baselineColVars(groupObjects(flattenGSFeed($scope.dataRetreived)));
			// $scope.calcRentalDates();
		});
		responsePromise.error(function(data, status, headers, config) {
			console.log("AJAX failed!");
			$scope.itemData = baselineColVars($scope.fakeItemData.groups);
			// $scope.calcRentalDates();
		});
	};

	var flattenGSFeed = function(obj) {
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
		// console.log(output);
		return output;
	};

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
		endDate: function() {return new Date();}
		,
		days: undefined,
		daysweek: 7,
		notes: "",
		estimate: 0
	};


	var baselineColVars = function(obj) {
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
	};

	var groupObjects = function(obj) {
		var output = [];
		output.push({
			type: 'uncategorized',
			items: []
		});
		for (var entry in obj) {
			if (obj[entry].type === "Section") {
				output.push({
					type: obj[entry].name,
					items: []
				});
			}
			else if (obj[entry].type === "Item") {
				output[output.length - 1].items.push(obj[entry]);
			}
		}
		return output;
	};

	// --- INITIALIZE ---
	var init = function() {
		$scope.orderMeta = {};
		$scope.getItemDataGS();
	};
	init();

}]);
