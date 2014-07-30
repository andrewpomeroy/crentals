function js_create_post(title) 
{
     jQuery.ajax({
     type: 'POST',
     url: my_ajax_script.ajaxurl,
     data: ({action : "make_est_post", title: title}),
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