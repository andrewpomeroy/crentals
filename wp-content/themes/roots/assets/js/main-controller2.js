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

app.controller('orderForm', function($scope, $http) {

	$scope.orderMeta = {};

	var valueTypeBase = {
		type: "item",
		name: "Unnamed",
		qty: 0,
		rate: 0,
		days: 0,
		daysweek: 7,
		notes: "",
		estimate: 0
	};

	valueTypesOrdered = [
		{
			col: "type",
			colName: false,
			name: "item",
			order: false,
		},
		{
			order: 0,
			col: "name",
			colName: "Name",
			defValue: "Unnamed",
		},
		{
			order: 1,
			col: "qty",
			colName: "Quantity",
			defValue: 0
		},
		{
			order: 2,
			col: "rate",
			colName: "Rate",
			defValue: 0
		},
		{
			order: 3,
			colName: "daysweek",
			defValue: 0
		},
		{
			order: 0,
			colName: "notes",
			defValue: ""
		}
	];

	var valueTypeOrder = [
		"type", "name,"
	];

	// 	var getOrdered(array, key) {
	// 	for (entry in array) {
	// 		if (entry === key)
	// 			return array[entry]
	// 	}
	// }


	$scope.GSurl = 'https://spreadsheets.google.com/feeds/list/0Ase_pp75HN9MdGlpbkZmZmVTUGU2MElkOEktVUxyQWc/od6/public/values?alt=json';
	$scope.dataRetreived = {};

	$scope.getItemDataGS = function() {
		var responsePromise = $http.get($scope.GSurl);
		responsePromise.success(function(data, status, headers, config) {
			// console.table(data.feed.entry[0]);
			console.log("OBJECT:");
			console.log(data);
			$scope.dataRetreived = data.feed.entry;
			$scope.itemData = baselineColVars(groupObjects(flattenGSFeed($scope.dataRetreived)));
		});
		responsePromise.error(function(data, status, headers, config) {
			console.log("AJAX failed!");
			$scope.itemData = baselineColVars($scope.fakeItemData.groups);
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
				console.log("checking key w/ item: ");
				console.log(item);
				console.log("key: "+key);
				if (!(item[key]) || (item[key] === "")) {
					console.table(item);
					// console.log(key);
					// console.log(item[key]);
					// console.log(valueTypeBase[key]);
					// item.push({keyitem[key] = valueTypeBase[item[key]];
					item[key] = (valueTypeBase[key]);
					console.log("Added: "+item[key]+" to "+key);
				}
			}
			return item;
		};
		for (var entry in obj) {
			// console.log("hello entry");
			// console.table(obj[entry]);
			if (obj[entry].items) {
				// console.log("hello items");
				// console.table(obj[entry].items);
				for (var item in obj[entry].items)
				{
					item = checkEmptyItem(obj[entry].items[item]);
					// console.table(obj[entry].items[item]);
					// for (var key in obj[entry].items[item])
					// {
					// 	// console.log('KEY: '+ key);
					// 	// console.log('VAL: '+ obj[entry].items[item][key]);
					// 	obj[entry].items[item][key] = checkEmptySingle(key, obj[entry].items[item][key]);
					// }
				}
			}
		}
		return obj;
	};


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

	$scope.recalcTotalEstimate

	$scope.changeQty = function(item) {

		// ---  calculate Days/Week
		// Begin with assumption of normal day-counting
		var daysCounted = item.days;
		// When days/week restriction is enabled for this item..
		if (item.daysweek < 7) {
			// Start out with the number of days counted in all complete weeks
			daysCounted = Math.floor(item.days/7) * item.daysweek;
			// In the case that the days in final week exceed specified days/week..
			if ((item.days%7) > item.daysweek) {
				// Add only the specified days in final week
				daysCounted += parseInt(item.daysweek);
			}
			// Otherwise, add the entire remainder of days in the final week
			else {daysCounted += (item.days % 7);}
		}
		// Total = Amount * Rate * Time
		item.estimate = item.qty * item.rate * daysCounted;
		// $scope.recalcTotalEstimate($scope.itemData);
	};

	// Reset everything
	$scope.resetForm = function() {
		$scope.getItemDataGS();
	};


	// --- DATEPICKER FUNCTIONS ---

	var one_day = 1000*60*60*24;

	$scope.orderPickupDate = {
		opened: false
	};
	$scope.orderReturnDate = {
		opened: false
	};

	$scope.flushIndividualDates = function() {
		console.log($scope.itemData);
		for (var group in $scope.itemData) {
			console.log($scope.itemData[group]);
			for (var item in $scope.itemData[group].items) {
				var theItem = $scope.itemData[group].items[item];
				console.log(theItem);
				theItem.days = ($scope.totalRentalDays > 0) ? $scope.totalRentalDays : 0;
				$scope.changeQty(theItem);
			}
		}
	}

	$scope.calcRentalDates = function() {
		$scope.totalRentalDays = ($scope.orderReturnDate.date - $scope.orderPickupDate.date)/one_day;
		$scope.flushIndividualDates();
	};

	$scope.calcRentalDates();


	$scope.showWeeks = true;
	$scope.toggleWeeks = function () {
		$scope.showWeeks = ! $scope.showWeeks;
	};

	$scope.clear = function () {
		$scope.dt = null;
	};

	$scope.toggleMin = function() {
		$scope.minDate = ( $scope.minDate ) ? null : new Date();
	};
	$scope.toggleMin();

	$scope.dateOptions = {
		'year-format': "'yy'",
		'starting-day': 1
	};

	$scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'shortDate'];
	$scope.format = $scope.formats[0];



	var init = function() {
		$scope.getItemDataGS();
	};
	init();

});



// <field-div> directive

// app.directive	

// app.directive('fieldDiv', function() {
//   return {
//     restrict: 'E',
//     replace: true,
//     transclude: true,
//     scope: {
//       label: '@',
//     },
//     template: [
//     '<div class="divdiv">',
//     	'<div class="thisdiv' ng-repeat=""
//     	// '<label class="control-label">{{ label}}</label>',
//     	// '<div class="'
//     	// '<input type="text" class="form-control" ng-model="itemData.groups[0].type" />',
//     '</div>'
//     ].join('')
//   };
// });



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




