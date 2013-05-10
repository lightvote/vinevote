jQuery(document).ready(function($) {
	
	

	$('#sm-reset').submit(function(){
		
		
		$('#sm-feedback').html('<div class="loading"><img src="' + window.loadingImg + '" alt="" title="Resetting Score" /><br />Resetting Score...</div>').fadeIn(1000);
		
	
		data = {
			action: 'soundmashreset'
		};
		
		$.post(MyAjax.ajaxurl, data, function(response){

			$('#sm-feedback').html(response);
			
		});
		
		return false;
		
		
		
	});
	
	  SC.initialize({
	    client_id: "cf3bcf0b88b2f69fe39f43a32de64251",
	  });

	

	
	
	

 
});