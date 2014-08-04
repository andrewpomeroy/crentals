	var js_create_post = function(title, content, $http, $scope) {
		// jQuery.ajax({
		// type: 'POST',
		// url: my_ajax_script.ajaxurl,
		// data: ({action : "make_est_post", title: title, content: content}),
		// success: function(response) {
		// 	console.log('Success!', response);
		// 	successFunction();
		// },
		// error: function(response) {
		// 	console.log('error!', response);
		// 	failFunction();
		// }
		// });
		$http({
			url: my_ajax_script.ajaxurl,
			method: "POST",
			params: {action : "make_est_post", title: title, content: content}
		}).success(function(response) {
			// debugger;
			console.log(response);
			$scope.isOrderGood = 1;
		}).error(function(data) {
			$scope.isOrderGood = -1;
		});
	}