var orderForm = (function($) {
	var feedJSON;
	var init = function() {
		getGSData();
	};
	var getGSData = function() {
		$.ajax({
			url: 'https://spreadsheets.google.com/feeds/list/0Ase_pp75HN9MdGlpbkZmZmVTUGU2MElkOEktVUxyQWc/od6/public/values?alt=json',
			success: function(data) {
				console.log(data);
				$(document).ready( function() {
					feedJSON = data.feed.entry;
					popFields(feedJSON);
					console.table(feedJSON);
				});
			},
			error: function(thiss, that, other) {
				console.log(thiss);
				console.log(that);
				console.log(other);
			}
		});
	};
	var popFields = function(feedJSON) {
		var strings = JSON.stringify(feedJSON, null, "\t");
		// $('.app-container').append('<div>'+strings+'</div>');
		var container = $('.app-container');

		for (var i = 0; i < feedJSON.length; i++) {
			$(container).append('<div>' + feedJSON[i].gsx$type.$t + ' ' + feedJSON[i].gsx$name.$t + ' ' + feedJSON[i].gsx$rate.$t + '</div>');
		}
	}

	return {
		init: init
	};
})(jQuery);

orderForm.init();
// https://spreadsheets.google.com/feeds/list/0Ase_pp75HN9MdGlpbkZmZmVTUGU2MElkOEktVUxyQWc/od6/public/values?alt=json-in-script&callback=x