function my_js_function(testVarFrontEnd) 
{
     jQuery.ajax({
     url: my_ajax_script.ajaxurl,
     data: ({action : "make_est_post", testVar: testVarFrontEnd}),
     success: function(response) {
     	console.log('Success!', response)
      //Do stuff here
     },
     error: function(response) {
     	console.log('error!', response)
      //Do stuff here
     }

     });
}
my_js_function("hello");
debugger;