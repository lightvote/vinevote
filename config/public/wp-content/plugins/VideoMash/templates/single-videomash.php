<?php
/**
 * Pics Mash Single Picture Template
 *
 */
get_header();


$query = "
		  SELECT * FROM (SELECT ID,HOUR(CONVERT_TZ(d.post_date, '+00:00',meta2.meta_value)) as hour, meta1.meta_value as rating from  $wpdb->posts d
					JOIN  $wpdb->postmeta meta2 ON meta2.post_id = d.ID
				JOIN  $wpdb->postmeta meta1 ON meta1.post_id = d.ID
				
				AND meta2.meta_key = 'wpcf-utc-offset'
				AND meta1.meta_key = 'rating'

ORDER BY rating DESC) AS a

GROUP BY a.hour

ORDER BY HOUR
		
		";
		
		$pictures = $wpdb->get_results($query);
	
	 $i=0;

        	foreach($pictures as $picture){
	        $bestscore[$i] = $picture->rating;

	        $i=$i+1;
	        }
	
		
?>

 
 
 
 
 
 	<!--<h1 class="ps-entry-title"><?php the_title(); ?></h1>	-->
		<?php global $post;?>
		
		
			<?php
				$date = strtotime(get_post_meta($post->ID, 'date', true));
			$rating = get_post_meta($post->ID,'rating',true);
			$wins = get_post_meta($post->ID,'wins',true);
			$losses = get_post_meta($post->ID,'losses',true);
			$iframv = get_post_meta($post->ID,'wpcf-vine',true);
			//$iframv = get_post_meta($post->ID,'iframv',true);
			$date = strtotime(get_post_meta($post->ID,'date',true));
			$twitterid = get_post_meta($post->ID,'wpcf-guid',true);
			$timeago = timeago($date);
$locfeed = get_post_meta($post->ID,'wpcf-location',true);
$gmttime = get_post_meta($post->ID, 'date', true);
		$utc = get_post_meta($post->ID,'wpcf-utc-offset',true);
//$cattime = sum_the_time(date('H', $date),$utc);
$utctime = str_replace(':00',' hours',$utc);
$cattime = date('H',strtotime ( $utctime , $date ));
$localtime = date('H:i',strtotime ( $utctime , $date ));

$score = round($bestscore[ltrim($cattime, '0')] - $rating);


$id = explode( '/statuses', $twitterid );

/*			
preg_match('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', $iframv, $iframvfull);

$iframv = $iframvfull[0];
$iframv = expand_url($iframv);
*/
$sites_html = file_get_contents_curl($iframv);

$html = new DOMDocument();
@$html->loadHTML($sites_html);
$meta_og_img = null;
//Get all meta tags and loop through them.
foreach($html->getElementsByTagName('meta') as $meta) {
    //If the property attribute of the meta tag is og:image
    if($meta->getAttribute('property')=='twitter:image'){ 
        //Assign the value from content attribute to $vineimg
        $vineimg = $meta->getAttribute('content');
    }
 if($meta->getAttribute('property')=='twitter:player:stream'){ 
        //Assign the value from content attribute to $vinemp4
        $vinemp4 = $meta->getAttribute('content');
    }
}

?>


	
<div class="author">
<div class="author-content">
  			<div class="grid col-940 ">
 					<div class="author-image">
 <?php $author_id=$post->post_author; ?>
<a href="https://twitter.com/share?url=<?php echo wp_get_shortlink(get_the_ID()); ?>&text=This vine video has been nominated for The Perfect Day&via=lightvote" class="twitterlink"><img src="<?php echo get_post_meta($post->ID,'wpcf-imageauthor',true); ?> " width="48" height="48" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id ); ?> on lightvote" title="<?php echo the_author_meta( 'display_name' , $author_id ); ?> on lightvote"/></a>
 					</div>
 					<div class="author-text">
 						<div class="title"><?php echo the_author_meta( 'display_name' , $author_id ) ?></div>
 <a href="https://twitter.com/share?url=<?php echo wp_get_shortlink(get_the_ID()); ?>&text=This vine video has been nominated for The Perfect Day&via=lightvote" class="twitterlink" title="<?php echo the_author_meta( 'display_name' , $author_id ); ?> on lightvote"><?php echo wp_get_shortlink(get_the_ID()); ?></a>
 <!--<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fbit.ly%2F15Brmkg&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font=tahoma&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=244784295650896" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
 -->
 					</div>
 					<div class="author-votes">
                     <div class="score-category">
                    <span class="score-number"><?php echo $cattime ?>:00</span><br /><span class="score-detail">category nominated</span>
                    </div>
                    <div class="score-best">
                    <span class="score-number"><?php echo $score ?></span><br /><span class="score-detail">votes behind n°1</span>
                    </div>
                  
 					</div>

			</div>
     
    </div>     
</div>


<?php responsive_container(); // before container hook ?>


		<div id="container" class="hfeed">






<div class="grid col-460">
		
		<div class = "ps-img ps-img-left">
			<a href="#" class="mute1 mute-button off"></a>
<video id="video_1" class="video video-js vjs-default-skin" width="450" height="450" poster="<?php echo $vineimg; ?>" autoplay preload="auto" loop>
  <source type="video/mp4" src="<?php echo $vinemp4; ?>">
</video>
		</div>

		<div class="detail">
<span class= 'ps-ago-left timeago'> <?php echo $timeago; ?></span>  <span class= 'ps-catname-left location'><?php echo $locfeed; ?></span>
<?php global $user_ID; if( $user_ID ) { ?>
<?php if( current_user_can('level_10') ) { ?>
<span class="profile-admin-left"><?php echo $localtime;?> <a href="<?php echo $twitterid; ?>" title="Twitter ID" target="_new">link</a></span>
<?php }; ?>
<?php }; ?>
     	</div>
        
 
 </div>
        
 <div class="grid col-460 fit">
 <div class="profile-choose">
 

         <a href="../../vote-for-the-best-<?php echo $cattime ?>00-moments/" title="">
         <div class = "ps-vote" onclick = "">VOTE IN THE <?php echo $cattime ?>:00 CATEGORY</div>
         </a>
         <span class="profile-choose-or">or</span>
         <a href="../../index.php" title="VOTE LIVE"><div class = "ps-vote">VOTE LIVE</div></a>
  
        
  </div>
 


<script>

		jQuery(function () {

			

			var player = _V_('video_1').ready(function () {
			
				this.volume(0);
				
	
			});

			jQuery('.mute-button').click(function (e) {
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

			jQuery('video').click(function () {
				if (player.paused())
					player.play()
				else
					player.pause()
			});
		});
	</script>
<!--
	
	 <p><div class = "ps-share">Share & shine : <?php echo the_shortlink( wp_get_shortlink() ); ?></div>	</p>
-->
		
      
    </div>


   

<?php get_footer(); ?>