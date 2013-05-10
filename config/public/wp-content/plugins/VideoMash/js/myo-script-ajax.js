jQuery(document).ready(function($) {
	
	var options = { 
	        target:        '#ps_output',      
	        beforeSubmit:  showRequest,     
	        success:       showResponse,    
	        url:    	   MyAjax.ajaxurl    
	    }; 
	
	

	jQuery('#thumbnail_upload').ajaxForm(options); 
	
	function showRequest(formData, jqForm, options) {

	jQuery('#ps_output').html('Sending...');
	jQuery('#submit-ajax').attr("disabled", "disabled");
	}
	function showResponse(responseText, statusText, xhr, $form)  {

	jQuery('#submit-ajax').attr("enabled", "enabled");
	}
	
	var win;
	var lose;
	var fromtime;
	var perfectid;

	
	voteset = function(){
	
	 $(".ps-vote").on("hover", function (b) {
            $(this).css("cursor", "pointer");
            win = $(this).data('ps-winner');
            lose = $(this).data('ps-loser');
			 fromtime = $(this).data('ps-fromtime');
    });
	}
	
		voteset();
		
		
	
	pslikevote = function(){

		
	    $('.ps-img').html('<div class="loading"><img src="' + window.loadingImg + '" alt="" title="Loading Vine" /></div>').fadeIn(1000);

		$('.mute-button').fadeOut(1000);
		$('.ps-catname-left').fadeOut(1000);
		$('.ps-catname-right').fadeOut(1000);
		$('.ps-ago-left').fadeOut(1000);
		$('.ps-ago-right').fadeOut(1000);
		$('.profile-admin-left').fadeOut(1000);
		$('.profile-admin-right').fadeOut(1000);
	
	

        var g = {
            action: "ps_ajax_vote",
            win: win,
            lose: lose,
			fromtime: fromtime,
        };
        var e = $.ajax({
            url: MyAjax.ajaxurl,
            type: "POST",
            data: g,
            dataType: "json",
        });
		
		
        
        e.done(function (res) {

 		
$(".ps-img-left").html("<video id='"+ res.posts[0] +"' class='video1 video-js vjs-default-skin' width='420' height='420' autoplay preload='auto' loop poster='"+ res.vinemeta[0][0] +"'><source type='video/mp4' src='"+ res.vinemeta[0][1] +"'></video>");
				
$(".ps-img-right").html("<video id='"+ res.posts[1] +"' class='video2 video-js vjs-default-skin' width='420' height='420' autoplay preload='auto' loop poster='"+ res.vinemeta[1][0] +"'><source type='video/mp4' src='"+ res.vinemeta[1][1] +"'></video>");	
	 			 
 			 
			$('.mute-button').fadeIn(1000);
			$(".ps-catname-left").html(res.locfeed[0]).delay(3000).fadeIn(1000);
			$(".ps-catname-right").html(res.locfeed[1]).delay(3000).fadeIn(1000);
			
			$(".ps-ago-left").html("<a href='"+ res.link[0] +"' title='Lightvote profile'>"+ res.timeago[0] +"</a>").fadeIn(1000);
			$(".ps-ago-right").html("<a href='"+ res.link[1] +"' title='Lightvote profile'>"+ res.timeago[1] +"</a>").fadeIn(1000);
			
			
 			$(".ps-rating-left").html(res.rating[0]);
 			$(".ps-wins-left").html(res.wins[0]);
 			$(".ps-losses-left").html(res.losses[0]);
 			
 			$('.image-link-left').attr('href', res.link[0]);
				
 			$('.pint-left').attr('href', 'http://pinterest.com/pin/create/button/?url=' + res.link[0] + '&media=' + res.iframv[0] + '&description=Description');
 			$('.pint-right').attr('href', 'http://pinterest.com/pin/create/button/?url=' + res.link[1] + '&media=' + res.iframv[1] + '&description=Description');
 			
 			
 			$(".fblikeleft").html("<fb:like send='false' layout = 'button_count' width='450' show_faces='false' data-href='"+res.link[0] + "' data-send='false' data-ps-winner = '"+res.posts[0] +"' data-ps-loser = '" +res.posts[1] + "'></fb:like>");
 			$(".fblikeright").html("<fb:like send='false' layout = 'button_count' width='450' show_faces='false' data-href='"+res.link[1] + "' data-send='false' data-ps-winner = '"+res.posts[1] +"' data-ps-loser = '" +res.posts[0] + "'></fb:like>");
 			
 			$(".fbcommentleft").html('<div class="fb-comments" data-href="'+res.link[0] + '" data-num-posts="10" data-width="345"></div>');
 			$(".fbcommentright").html('<div class="fb-comments" data-href="'+res.link[1] + '" data-num-posts="10" data-width="345"></div>');
 			
 			$(".gplusleft").html("<div class='g-plusone' data-size='medium' data-annotation='inline' data-width='360' data-href='"+res.link[0] +"'></div>");
 			$(".gplusright").html("<div class='g-plusone' data-size='medium' data-annotation='inline' data-width='360' data-href='"+res.link[1] +"'></div>");
 			
	
			$(".ps-rating-right").html(res.rating[1]);
			
 			$(".ps-wins-right").html(res.wins[1]);
			
 			$(".ps-losses-right").html(res.losses[1]);
			
			$('.image-link-right').attr('href', res.link[1]);
 			
 			$(".ps-vote-left").html("<div class = 'ps-vote' data-ps-fromtime = '"+ res.fromtime +"' data-ps-winner = '"+ res.posts[0] +"' data-ps-loser = '"+ res.posts[1] +"' onclick = 'pslikevote()'>VOTE</div>");
			
 			$(".ps-vote-right").html("<div class = 'ps-vote' data-ps-fromtime = '"+ res.fromtime +"' data-ps-winner = '"+ res.posts[1] +"' data-ps-loser = '"+ res.posts[0] +"' onclick = 'pslikevote()'>VOTE</div>");
 			
			
 			var player = _V_(res.posts[0]).ready(function () {
				this.volume(0);
			});

			$('.mute1').click(function (e) {
				if (player.volume() == 0) {
					$(e.target).removeClass('off');
					$(e.target).addClass('on');
					player.volume(1);
				} else {
					$(e.target).removeClass('on');
					$(e.target).addClass('off');
					player.volume(0);
				}
				return false;
			});
			
			$('.video1').click(function () {
				if (player.paused())
					player.play()
				else
					player.pause()
			});



			var player2 = _V_(res.posts[1]).ready(function () {
				this.volume(0);
			});
			
		$('.mute2').click(function (e) {
				if (player2.volume() == 0) {
					$(e.target).removeClass('off');
					$(e.target).addClass('on');
					player2.volume(1);
				} else {
					$(e.target).removeClass('on');
					$(e.target).addClass('off');
					player2.volume(0);
				}
				return false;
			});
			$('.video2').click(function () {
				if (player2.paused())
					player2.play()
				else
					player2.pause()
			});
			
 			voteset();
			
			
		

        });
        e.fail(function (i, j) {
            alert("Request failed: " + j)
        });
        return true

    
   }
   
   
   perfectday = function(){

	$(".ps-voteperfect").fadeOut(2000);
	
	perfectid = $(".ps-vote").data('perfectid') +1;
	
	
	
		
       var g = {
            action: "ps_ajax_perfectday",
			perfectid: perfectid,
        };
		
        var e = $.ajax({
            url: MyAjax.ajaxurl,
            type: "POST",
            data: g,
            dataType: "json",
        });
		
		
        e.done(function (res) {
				
			$(".ps-voteperfect").html("<div class = 'ps-vote hidden' data-perfectid = '"+ res.perfectid +"' onclick = 'perfectday()'>NEXT</div>");
			
			
				$(".perfect-hour").html(""+ res.cattime[res.perfectid] +":00").fadeIn(200,function(){
					
					$(".perfect-location").html(""+ res.locfeed[res.perfectid] +"").fadeIn(200, function(){
							$(".perfect-hour").delay(1000).fadeOut(500);
						$(".perfect-location").delay(1000).fadeOut(500, function(){
		
							$(".perfect-video").html("<video id='"+ res.postid[res.perfectid] +"' class='video1 grayvideo video-js vjs-default-skin' width='700' height='700' autoplay preload='auto'><source type='video/mp4' src='"+ res.vinemeta[res.perfectid][1] +"'></video>");	
		
								var player = _V_(res.postid[res.perfectid]).ready(function () {
									 this.volume(0);
									 this.addEvent("ended", function(){ 
       								
      
 											$(".perfect-video").fadeOut(2000);
											if ( perfectid >= 23) {
											$(".ps-voteperfect").html("<div class = 'ps-vote hidden' data-perfectid = '-1' onclick = 'perfectday()'>NEXT</div>");
											perfectday();
											}else
											perfectday()
					
		
      								  });
		
								 });
						 });
				 });
				 
			  });
		
		  });
			
        e.fail(function (i, j) {
            alert("Request failed: " + j)
        });
        return true
    
   }

});
