// JavaScript Document

jQuery(function () {

			var player = _V_('video_1').ready(function () {
				this.volume(0);
			});

		

			jQuery('.mute1').click(function (e) {
				if (player.volume() == 0) {
					jQuery(e.target).removeClass('off');
					jQuery(e.target).addClass('on');
					player.volume(1);
				} else {
					jQuery(e.target).removeClass('on');
					jQuery(e.target).addClass('off');
					player.volume(0);
				}
				return false;
			});
			
			jQuery('.video1').click(function () {
				if (player.paused())
					player.play()
				else
					player.pause()
			});


			var player2 = _V_('video_2').ready(function () {
				this.volume(0);
			});
			
			

			jQuery('.mute2').click(function (e) {
				if (player2.volume() == 0) {
					jQuery(e.target).removeClass('off');
					jQuery(e.target).addClass('on');
					player2.volume(1);
				} else {
					jQuery(e.target).removeClass('on');
					jQuery(e.target).addClass('off');
					player2.volume(0);
				}
				return false;
			});
			jQuery('.video2').click(function () {
				if (player2.paused())
					player2.play()
				else
					player2.pause()
			});
			
			
			
			
			
		});