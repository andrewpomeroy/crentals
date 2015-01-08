var orderForm = (function($) {
	var feedJSON;
	var settings = {
		container: function() {
			return $('.app-container');
		}
	};
	var init = function() {
		getGSData();
	};
	var getGSData = function() {
		$.ajax({
			url: 'https://spreadsheets.google.com/feeds/list/0Ase_pp75HN9MdGlpbkZmZmVTUGU2MElkOEktVUxyQWc/od6/public/values?alt=json',
			success: function(data) {
				// console.log(data);
				$(document).ready( function() {
					feedJSON = data.feed.entry;
					// console.table(flattenGSFeed(feedJSON));
					var properFormObj = groupObjects(flattenGSFeed(feedJSON));
					console.log(properFormObj);
					// popFields(feedJSON);
					// stringJSON(properFormObj);
					// console.table(properFormObj);
					// console.table(feedJSON);
				});
			},
			error: function(thiss, that, other) {
				// console.log(thiss);
				// console.log(that);
				// console.log(other);
			}
		});
	};
	var popFields = function(feedJSON) {
		var strings = JSON.stringify(feedJSON, null, "\t");
		// $('.app-container').append('<div>'+strings+'</div>');

		for (var i = 0; i < feedJSON.length; i++) {
			$(settings.container).append('<div>' + feedJSON[i].gsx$type.$t + ' ' + feedJSON[i].gsx$name.$t + ' ' + feedJSON[i].gsx$rate.$t + '</div>');
		}
	};
	var stringJSON = function(obj) {
		$('.app-container').append('<pre>' + JSON.stringify(obj, null, "\t") + '</pre>');
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
	var groupObjects = function(obj) {
		var output = [];
		output.push({
			type: 'uncategorized',
			items: []
		});
		// var entryDepth = 0;
		for (var entry in obj) {
			// console.log('entry:' + entry);
			// console.log('Obj-entry:');
			// console.log(obj[entry]);
			if (obj[entry].type === "Section") {
				// console.log("found section");
				// console.log(obj[entry]);
				output.push({
					type: obj[entry].name,
					items: []
				});
				// entryDepth++;
			}
			else if (obj[entry].type === "Item") {
				// console.log("found item");
				// console.table(obj[entry]);
				output[output.length - 1].items.push(obj[entry]);
			}
		}
		return output;
	};

	return {
		init: init
	};
})(jQuery);

orderForm.init();
// https://spreadsheets.google.com/feeds/list/0Ase_pp75HN9MdGlpbkZmZmVTUGU2MElkOEktVUxyQWc/od6/public/values?alt=json-in-script&callback=x