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

app.controller('orderForm', ['$scope', '$http', function($scope, $http) {

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
			// console.log("OBJECT:");
			// console.log(data);
			$scope.dataRetreived = data.feed.entry;
			$scope.itemData = baselineColVars(groupObjects(flattenGSFeed($scope.dataRetreived)));
			$scope.calcRentalDates();
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
		// $scope.recalcTotalEstimate();
	};

	// Reset everything
	$scope.resetForm = function() {
		// $scope.getItemDataGS();
		init();
	};

	// Submit Order to Wordpress Backend
	$scope.submitOrder = function() {
		doAjaxRequest();
	}

	var doAjaxRequest = function() {
		$.ajax({
			url: '/wp-admin/admin-ajax.php',
			data: {
				'theItemData': $scope.itemData,
				dataType: 'JSON',
				success: function(data) {
					console.log(data);
				},
				error: function(errorThrown) {
					alert('error');
					console.log(errorThrown);
				}
			}
		});
	};


	// --- DATEPICKER FUNCTIONS ---

	var one_day = 1000*60*60*24;

	$scope.incrementIndividualDate = function(date, amount) {
		console.log(date);
		date.setDate(date.getDate() + amount);
		console.log(date);
		$scope.calcRentalDates();
	};

	flushIndividualDate = function(item) {
		// console.log("ITEM OBJECT TO BE FLUSHED:");
		// console.log(item);
		if (item.customRentalPeriod) {
			if ((!item.startDate) && (!item.endDate)) {
				console.log("no item start/end dates");
				item.startDate = $scope.orderMeta.orderPickupDate.date;
				item.endDate = $scope.orderMeta.orderReturnDate.date;
			}
			if (!item.endDate) {
				console.log("no end date");
				item.endDate = item.startDate;
			}
			if (!item.startDate || (item.startDate > item.endDate)) {
				console.log("no start date, or start date after end date");
				item.startDate = item.endDate;
			}
			if (item.endDate) {
				console.log("endDate: " + item.endDate);
			}
			if (item.startDate && item.endDate) {
				item.days = parseInt((item.endDate - item.startDate) / one_day) + 1;
			}
			else {
				console.log('nullifying '+item.name);
				item.days = null;
			}
		}
		else {
			// console.log("setting default dates on: ");
			// console.log(item.name);
			console.log("ITEM-----BEFORE");
			console.log(item);
			item.startDate = new Date($scope.orderMeta.orderPickupDate.date);
			item.endDate = new Date($scope.orderMeta.orderReturnDate.date);
			console.log("ITEM-----AFTER");
			console.log(item);
			item.days = $scope.orderMeta.totalRentalDays;
			// console.log(item.startDate);
		}
		// item.days = ($scope.orderMeta.totalRentalDays > 0) ? $scope.orderMeta.totalRentalDays : 0;
		// item.days = ($scope.orderMeta.totalRentalDays > 0) ? $scope.orderMeta.totalRentalDays : 0;
	};

	resetTotal = function() {
		$scope.totalEstimate = 0;
	};
	addToTotal = function(item) {
		if (item.estimate > 0) {$scope.totalEstimate += item.estimate;}
	};
	$scope.calcTotal = function() {
		resetTotal();
		loopThroughItems([addToTotal]);
	};

	loopThroughItems = function(itemFunctions) {
		// console.log($scope.itemData);
		for (var group in $scope.itemData) {
			for (var item in $scope.itemData[group].items) {
				var theItem = $scope.itemData[group].items[item];
				// loop through specific item functions and execute each on found item
				for (var passedFunction in itemFunctions) {
					itemFunctions[passedFunction](theItem);
					// console.log("Called: ");
					// console.log(itemFunctions[passedFunction]);
					// console.log("with: ");
					// console.log(theItem);
				}
			}
		}
	};

	$scope.calcRentalDates = function() {
		$scope.orderMeta.totalRentalDays = parseInt(($scope.orderMeta.orderReturnDate.date - $scope.orderMeta.orderPickupDate.date)/one_day) + 1;
		resetTotal();
		for(var i = 0; i < $scope.itemData.length; i++) {
			for (var x= 0;x< $scope.itemData[i].items.length;x++) {
				var _item = $scope.itemData[i].items[x];
				flushIndividualDate(_item);
				$scope.changeQty(_item);
				addToTotal(_item);
			}
		}
		// angular.forEach($scope.itemData, function(group) { 
		// 	angular.forEach($scope.itemData[group].items, function(item) {
		// 		flushIndividualDate(item);
		// 		$scope.changeQty(item);
		// 		addToTotal(item);
		// 	});
		// });
		// loopThroughItems([flushIndividualDate, $scope.changeQty, addToTotal]);
		// calcTotal();
		// console.log($scope.itemData);
	};

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

	$scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'shortDate', 'MM/dd/yyyy'];
	$scope.format = $scope.formats[0];

	var getToday = function() {
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
		    dd='0'+dd
		} 

		if(mm<10) {
		    mm='0'+mm
		} 

		today = mm+'/'+dd+'/'+yyyy;
		// dateFormat(today);
		console.log("TODAY: " + today);
		return today;
	}

	// --- INITIALIZE ---
	var init = function() {
		$scope.orderMeta = {};
		$scope.totalEstimate = 0;
		var todayDate = new Date();
		$scope.orderMeta.orderPickupDate = {
			opened: false,
			date: todayDate
		};
		$scope.orderMeta.orderReturnDate = {
			opened: false,
			date: todayDate
		};
		// $scope.calcRentalDates();
		$scope.getItemDataGS();
	};
	init();

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





