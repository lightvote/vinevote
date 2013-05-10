<?php
/*
Plugin Name: Vinevote
Plugin URI: http://www.lightvote.com/
Description: See lightvote for more infos.
Version: 1.0
Author: Lightvote
Author URI: http://www.lightvote.com/
*/

    #} Hooks

    #} Install/uninstall
    register_activation_hook(__FILE__,'videomash__install');
    register_deactivation_hook(__FILE__,'videomash__uninstall');
    
    #} general
    add_action('init', 'videomash__init');
    add_action('admin_menu', 'videomash__admin_menu'); #} Initialise Admin menu
    

	#} Initial Vars
	global $videomash_db_version;
	$videomash_db_version                = "1.0";
	$videomash_version               = "1.0";
	$videomash_activation                = '';


	#} Urls
    global $videomash_urls;
    $videomash_urls['home']      = 'http://www.videomashplugin.com/';
    $videomash_urls['docs']      = plugins_url('/documentation/index.html',__FILE__);
    

	
	#} Page slugs
    global $videomash_slugs;
    $videomash_slugs['config']           = "videomash-plugin-config";
    $videomash_slugs['share']            = "videomash-plugin-share";
    $videomash_slugs['settings']         = "videomash-plugin-settings";

	#} Taxonomies
	global $videomash_taxonomy;
	$videomash_taxonomy['vcats'] = 'v-mash-cats';
	$videomash_taxonomy['vtags'] = 'v-mash-tags';



	#} Install function
	function videomash__install(){

    #} Default Options

        add_option('vm_ShowScore','yes','','yes');
		add_option('vm_fbcomments','yes','','yes');
		add_option('vm_fbcomments2','yes','','yes');
		add_option('vm_topratings','yes','','yes');
		add_option('vm_socialshare','yes','','yes');
		add_option('vm_ps_pending','yes','','yes');
		add_option('vm_custompage','yes','','yes');

	

	}



	#} Uninstall
	function videomash__uninstall(){
	    
	    #} Removes initial settings, leaves config intact for upgrades.
	    delete_option("videomash_db_version");
	    delete_option("videomash_version");
	    delete_option("videomash_activation");
	    
	}



#} Initialisation - enqueueing scripts/styles
function videomash__init(){
  
    global $videomash_slugs, $videomash_taxonomy; #} Req
    
    #} Admin & Public
    wp_enqueue_script("jquery");
	
	#ajaxpb
	wp_enqueue_script( 'jquery-form',array('jquery'),false,true ); 
	wp_enqueue_style('picsmashcssajax', plugins_url('/css/PicsMashAJAX.css',__FILE__) );
	wp_enqueue_script('videojs-js',plugins_url('/js/video.min.js',__FILE__),array('jquery'));
wp_enqueue_style('videojs-css', plugins_url('/css/video-js.min.css',__FILE__) );
	wp_enqueue_script('myo-ajax2',plugins_url('/js/myo-script-ajax.js',__FILE__),array('jquery'));
	wp_localize_script( 'myo-ajax2', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
	#ajaxpb
	
	
    
    
    #} Admin only
    if (is_admin()) {
        
        #} Admin CSS
        wp_enqueue_style('mysmashCSSADM', plugins_url('/css/MySmashAdmin.css',__FILE__) );
        
        #} Admin JS

		wp_enqueue_script('videomash-ajax',plugins_url('/js/vm-ajax-script.js',__FILE__),array('jquery'));
		wp_localize_script( 'videomash-ajax', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

    }
    
     #} Custom post types - video
     $labels = array(
                'name' => _x('Videos Mash', 'post type general name'),
                'singular_name' => _x('Video Mash', 'post type singular name'),
                'add_new' => _x('Manually Add Video', 'video'),
                'add_new_item' => __('Manually Add New Video'),
                'edit_item' => __('Edit Video'),
                'new_item' => __('New Video'),
                'view_item' => __('View Video'),
                'search_items' => __('Search Videos'),
                'not_found' =>  __('No videos found'),
                'not_found_in_trash' => __('No videos found in Trash'),
                'parent_item_colon' => '',
                'menu_name' => 'Videos Mash'
            );
    $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => 'videomash','with_front' => FALSE ),
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => false,
                'menu_icon' => plugins_url('/i/movie.png',__FILE__),
                'menu_position' => null,
                'supports' => array( 'title', 'author','comments')
            );
    #} Register it
    register_post_type('videomash',$args);
	flush_rewrite_rules();


     #} Initialize New Taxonomy Labels
    $labels = array(
                'name' => _x( 'Video Categories', 'taxonomy general name' ),
                'singular_name' => _x( 'Video Category', 'taxonomy singular name' ),
                'search_items' =>  __( 'Search Video Categories' ),
                'all_items' => __( 'All Video Categories' ),
                'parent_item' => __( 'Parent Video Category' ),
                'parent_item_colon' => __( 'Parent Video Category:' ),
                'edit_item' => __( 'Edit Video Category' ),
                'update_item' => __( 'Update Video Category' ),
                'add_new_item' => __( 'Add New Video Category' ),
                'new_item_name' => __( 'New Video Category Name' ),
            );
    #} Custom taxonomy for Project Tags
    register_taxonomy($videomash_taxonomy['vcats'],array('videomash'), array(
                'hierarchical' => true,
                'labels' => $labels,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => $videomash_taxonomy['vcats']),
                        ));

    #} Initialize New Taxonomy Labels
    $labels = array(
                'name' => _x( 'Video Tags', 'taxonomy general name' ),
                'singular_name' => _x( 'Video Tag', 'taxonomy singular name' ),
                'search_items' =>  __( 'Search Video Tags' ),
                'all_items' => __( 'All Video Tags' ),
                'parent_item' => __( 'Parent Video Tag' ),
                'parent_item_colon' => __( 'Parent Video Tag:' ),
                'edit_item' => __( 'Edit Video Tag' ),
                'update_item' => __( 'Update Video Tag' ),
                'add_new_item' => __( 'Add New Video Tag' ),
                'new_item_name' => __( 'New Video Tag Name' ),
            );
    #} Custom taxonomy for Project Tags
    register_taxonomy($videomash_taxonomy['vtags'],array('videomash'), array(
                'hierarchical' => false,
                'labels' => $labels,
                'show_ui' => true,
                'query_var' => true,
                'rewrite' => array( 'slug' => $videomash_taxonomy['vtags']),
                        ));
    
    add_filter( 'single_template', 'videomash_single_template');
    function videomash_single_template($single_template) {
    global $post;
            if ($post->post_type == 'videomash')
                $single_template = dirname( __FILE__ ) . '/templates/single-videomash.php';
            return $single_template;
        }
    


}

#} Add le admin menu
function videomash__admin_menu() {

    global $videomash_slugs,$videomash_menu; #} Req
    
    $videomash_menu = add_menu_page( 'Video Mash Options', 'Video Mash Admin', 'manage_options', $videomash_slugs['config'], 'videomash_pages_configs', plugins_url('/i/movie.png',__FILE__));
    add_submenu_page( $videomash_slugs['config'], 'Settings', 'Settings', 'manage_options', $videomash_slugs['settings'] , 'videomash_pages_settings' );
    
}


function videomash_checkForMessages(){
    
    global $videomash_urls;

    # First deal with legit purchases
    if (isset($_GET['legit'])){
        
        # Update
        update_option('vm_stormgate_firstLoadMsg',1);
        
        #} Set this here
        $flFlag = 1;
        
    } else $flFlag = get_option('vm_stormgate_firstLoadMsg');
    
    
    
    if (empty($flFlag)) {
        
        videomash_html_msg(2,'<div class="sgThanks">
            <h3>Thank you for installing the Video Mash Plugin, only available on CodeCanyon!</h3>
            <p>This license entitles you to use Video Mash on a single WordPress install.</br>
            </p>
                        
            <p>Its Easy to get started, you can work it out for yourself below or read the <a href="'.$videomash_urls['docs'].'" target="_blank">Video Mash Support Manual</a>.<br />To keep up to date with Video Mash follow us on <a href="http://codecanyon.net/user/mikemayhem3030/follow/" target="_blank">CodeCanyon</a></p>
        
            <div class="sgButtons">
                <a class="buttonG" href="?page=videomash-plugin-config&legit=true">I have a License</a>
                <a class="buttonBad" href="http://codecanyon.net/item/pics-mash-image-rating-tool/3256459">I need a License</a>
            </div>
                    
            <div class="clear"></div>
        </div>');
        
    }
    
}



// sum the time

function sum_the_time($time1, $time2) {
  $times = array($time1, $time2);
  $seconds = 0;
  foreach ($times as $time)
  {
    list($hour,$minute,$second) = explode(':', $time);
    $seconds += $hour*3600;
    $seconds += $minute*60;
    $seconds += $second;
  }
  $hours = floor($seconds/3600);
  $seconds -= $hours*3600;
  $minutes  = floor($seconds/60);
  $seconds -= $minutes*60;
  // return "{$hours}:{$minutes}:{$seconds}";
  return sprintf('%02d:%02d', $hours, $minutes); 
}



// some functions - just call me Zuck baby!!
		
	function vm_expected($Rb, $Ra) {
	     return 1/(1 + pow(10, ($Rb-$Ra)/400));
	    }
	// Calculate the new winnner score
	function vm_win($score, $expected, $k = 24) {
	    return $score + $k * (1-$expected);
	    }
	// Calculate the new loser score
	function vm_loss($score, $expected, $k = 24) {
	    return $score + $k * (0-$expected);
	    }
	
	function vm_get_score($ID){
	    $rating = get_post_meta($ID,'rating',true);
	    return $rating;
	}
	
	function vm_update_score($ID,$score){
	    $score = (double)$score;
	    update_post_meta($ID, 'rating', $score);
	}
	
	function vm_update_wins($ID){
	    $votes = get_post_meta($ID,'wins',true);
	    $votes = $votes + 1;
	    update_post_meta($ID, 'wins', $votes);
	}
	
	function vm_update_losses($ID){
	    $votes = get_post_meta($ID,'losses',true);
	    $votes = $votes + 1;
	    update_post_meta($ID, 'losses', $votes);
	}


#} Options page
function videomash_pages_configs() {
    
    global $wpdb, $videomash_urls, $videomash_version,$videomash_slugs;    #} Req
    // add database pointer
    $wpdb->nggpictures                  = $wpdb->prefix . 'ngg_pictures';
    $wpdb->nggallery                    = $wpdb->prefix . 'ngg_gallery';
    $wpdb->nggalbum                     = $wpdb->prefix . 'ngg_album';
    
    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    ?>
    <div id="sgpBod">
       <div class='myslogo'><?php echo '<img src="' .plugins_url( 'i/videomash.jpg' , __FILE__ ). '" > ';   ?></div>
        <div class='mysearch'>
            
            <?php videomash_header(); ?>
            
            <?php videomash_checkForMessages(); ?>

                    <div class="postbox">
                      <h3><label>Welcome</label></h3>
                     <div class="inside">
                       <p>Welcome to the Video Mash Plugin. Thank you for purchasing the plugin. Adding Sounds to Mash couldn't be easier. Simply input your SoundCloud ID on the settings
                       	page and you're good to go.</p>
                  </div>
                </div>
        
    
        <div class="postbox">
		<h3><label><?php _e('Video Mash News'); ?></label></h3>
		 <div class="inside">
			<?php // Get RSS Feed(s)
			include_once(ABSPATH . WPINC . '/feed.php');
			
			
			
			add_filter( 'wp_feed_cache_transient_lifetime' , 'vm_feed_cache' );
			$rss = fetch_feed('http://videomashplugin.com/feed/');
			remove_filter( 'wp_feed_cache_transient_lifetime' , 'vm_feed_cache' );
			
			// Get a SimplePie feed object from the specified feed source.
			
			if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
			    // Figure out how many total items there are, but limit it to 5. 
			    $maxitems = $rss->get_item_quantity(5); 
			
			    // Build an array of all the items, starting with element 0 (first element).
			    $rss_items = $rss->get_items(0, $maxitems); 
			endif;
			?>
			
			<ul>
			    <?php if ($maxitems == 0) echo '<li>No News.</li>';
			    else
			    // Loop through each feed item and display each item as a hyperlink.
			    foreach ( $rss_items as $item ) : ?>
			    <li>
			        <a href='<?php echo esc_url( $item->get_permalink() ); ?>' target = '_blank'
			        title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
			        <?php echo esc_html( $item->get_title() ); ?></a><br/>
			        <?php echo esc_html( $item->get_description() ); ?>
			    </li>
			    <?php endforeach; ?>
			</ul>
		</div>
		</div>
             
             
      
    <div class="postbox">
      <h3><label>Support</label></h3>
        <div class="inside">
             <p>If you are struggling to use the plugin please check the <a href="<?php echo $videomash_urls['docs']; ?>" target="_blank">Video Mash Support Manual</a>. If you are still struggling do not hesitate to contact us.
             We will try and respond to all support requests within 24 to 48 hours.</p>
        </div>
    </div>
    
    

     
     
     </div>


</div>
<?php
}

function vm_feed_cache( $seconds )
			{
			  // change the default feed cache recreation period to 2 hours
			  return 7200;
			}

function videomash_load_scripts($hook){
    global $videomash_menu;
    
    wp_enqueue_script('videomash-ajax',plugins_url('/js/vm-ajax-script.js',__FILE__),array('jquery'));
    
}
add_action('admin_enqueue_scripts','videomash_load_scripts');


#ajax pb

add_action( 'wp_ajax_nopriv_ps_ajax_vote', 'ps_ajax_vote' );
add_action( 'wp_ajax_ps_ajax_vote', 'ps_ajax_vote' );
add_action( 'wp_ajax_nopriv_ps_ajax_perfectday', 'ps_ajax_perfectday' );
add_action( 'wp_ajax_ps_ajax_perfectday', 'ps_ajax_perfectday' );


function ps_ajax_vote(){
	global $wp_query,$paged,$post,$wp,$wpdb;
	
	if(isset($_POST['fromtime'])){
		if($fromtime != '0'){
	$fromtime = $_POST['fromtime'];
		}
	}
	if(isset($_POST['win']) && isset($_POST['lose'])){
    $winner = $_POST['win'];
    $loser = $_POST['lose'];
    }

     if (isset($winner)) {
    
     $winner_score = vm_get_score($winner);
     $loser_score = vm_get_score($loser);
     
     $winner_expected = vm_expected($loser_score, $winner_score);
     $winner_new_score = vm_win($winner_score, $winner_expected);
    
     $loser_expected = vm_expected($winner_score, $loser_score);
     $loser_new_score = vm_loss($loser_score, $loser_expected);
    
    vm_update_score($winner,$winner_new_score);
    vm_update_score($loser, $loser_new_score);
    vm_update_wins($winner);
    vm_update_losses($loser);
	
	 }
	
	 
	 
    $query = "SELECT * FROM $wpdb->posts WHERE post_type = 'videomash' AND post_status = 'publish' AND DATE_SUB(UTC_TIMESTAMP(),INTERVAL 80 MINUTE) <= post_date ORDER BY RAND() LIMIT 0,2";
	
	if($fromtime != '0'){
		$query = "SELECT * FROM $wpdb->posts
		LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
    	WHERE $wpdb->postmeta.meta_key = 'wpcf-utc-offset'
		AND HOUR(CONVERT_TZ(post_date, '+00:00',$wpdb->postmeta.meta_value)) = $fromtime 
		ORDER BY RAND() LIMIT 0,2";
	
	}
	
	$pictures = $wpdb->get_results($query);
    $i=0;
    foreach($pictures as $picture){
	    $thumb[$i] = get_post_meta($picture->ID,'iframv',true);
		$date[$i] = strtotime(get_post_meta($picture->ID, 'date', true));
		$postid[$i] = $picture->ID;
		$rating[$i] = round(get_post_meta($picture->ID,'rating',true),0);
		$votes[$i] = get_post_meta($picture->ID,'wins',true);
		$losses[$i] = get_post_meta($picture->ID,'losses',true);       
		$link[$i] = get_permalink($postid[$i]); 
		$vine[$i] = get_post_meta($picture->ID,'wpcf-vine',true);
	//$vine[$i] = myo_youtube($thumb[$i]); 
		$vinemeta[$i] = getvinemeta($vine[$i]);
	$timeago[$i] = timeago($date[$i]);
	$twitterimg[$i] = get_post_meta($picture->ID,'wpcf-imageauthor',true);
	$twitterid[$i] = get_post_meta($picture->ID,'wpcf-guid',true);
	$locfeed[$i] = get_post_meta($picture->ID,'wpcf-location',true);
	$gmttime[$i] = get_post_meta($picture->ID, 'date', true);
		$utc[$i] = get_post_meta($picture->ID,'wpcf-utc-offset',true);
	$localtime[$i] = sum_the_time(date('H:i', $date[$i]),$utc[$i]);
	$utctime[$i] = str_replace(':00',' hours',$utc[$i]);
	$localphp[$i] = date('H:i',strtotime ( $utctime[$i] , $date[$i] ));
	$more[$i] = get_permalink($postid[$i]);

	

		$i++;
	}
	
	$response['iframv'] = $thumb;
	$response['date'] = $date;
	$response['posts'] = $postid;
	$response['rating'] = $rating;
	$response['wins'] = $votes;
	$response['losses'] = $losses;
	$response['link'] = $link;
	$response['vine'] = $vine;
	$response['vinemeta'] = $vinemeta;
	$response['timeago'] = $timeago;
	$response['twitterid'] = $twitterid;
	$response['twitterimg'] = $twitterimg;
	$response['locfeed'] = $locfeed;
	$response['date'] = $gmttime;
	$response['wpcf-utc-offset'] = $utc;
	$response['fromtime'] = $fromtime;
	$response['localphp'] = $localphp;
	$response['more'] = $more;
 
	
	echo json_encode($response);
	
	die();
	
}



#ajax pb

add_action( 'wp_ajax_nopriv_videomashreset', 'videomashreset' );
add_action( 'wp_ajax_videomashreset', 'videomashreset' );

function videomashreset(){
	
	global $wp, $wpdb;  #} Req
            
     $querystr = "SELECT ID FROM $wpdb->posts WHERE post_type = 'videomash'";
     $added = $wpdb->get_col($querystr);
	 $str = 0;
     $str = implode(',', $added);
	 
	
	$query = "UPDATE $wpdb->postmeta SET meta_value = 1400 WHERE post_id IN ($str) AND meta_key = 'rating'";
	$wpdb->query($query);
	
	$query = "UPDATE $wpdb->postmeta SET meta_value = 0 WHERE post_id IN ($str) AND meta_key = 'wins'";
	$wpdb->query($query);
	
	$query = "UPDATE $wpdb->postmeta SET meta_value = 0 WHERE post_id IN ($str) AND meta_key = 'losses'";
	$wpdb->query($query);

	
	echo "<b><br/>Ratings reset</b>";
	
	die();
}

#} Options page
function videomash_pages_settings() {
    
    global $wpdb, $videomash_urls, $videomash_version;    #} Req
    
    if (!current_user_can('manage_options'))  {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }
    
    
?><div id="sgpBod">
        <div class='myslogo'><?php echo '<img src="' .plugins_url( 'i/videomash.jpg' , __FILE__ ). '" > ';   ?></div>
        <div class='mysearch'>
        	
        <?php videomash_header(); ?>
            
                
         <?php
         	if(isset($_GET['save'])){ 
             if ($_GET['save'] == "1"){
                videomash_html_save_settings();
             }
		    }
            if(!isset($_GET['save'])){
                videomash_html_settings();
            }
    ?></div>
</div>
<?php
}

function videomash_header(){

    global $videomash_urls;
    ?>

    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=438275232886336";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

   
   
   <?php
    //build the twitter text tweet
        $URL = $videomash_urls['home'];
        $siteURL = get_site_url();
        $PicsM = "http://www.videomashplugin.com/";
        $text = "I love " . $PicsM;
        $hash = "#videomash";
        $QP = "?url=".$URL."&text=".$text."&hashtags=".$hash;
    ?>

	<a href="https://twitter.com/PicsMashPlugin" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @picsmashPlugin</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	

    <?php

        $img = "http://3.s3.envato.com/files/37870139/previewnew.jpg";
        echo "<a href='http://pinterest.com/pin/create/button/?url=$URL&media=$img&description=Description' class='pin-it-button' count-layout='horizontal'><img border'0' src='//assets.pinterest.com/images/PinExt.png' title='Pin It' /></a>";
    ?>

  <div class="fb-like" data-href="http://www.videomashplugin.com/" data-send="false" data-width="360" data-show-faces="false" data-font="arial"></div>


    <?php
	$home = $videomash_urls['home'];
	$docs = $videomash_urls['docs'];
        echo "<div id = 'ps-links' style = 'padding-top:1%;padding-bottom:1%'><ul style = 'display:inline'>
        <li style = 'display:inline;padding-right:1%'><a href = '$home'>videomashplugin.com</a></li>
        <li style = 'display:inline;padding-right:1%'><a href = '$docs'>Documentation</a></li>
        <li style = 'display:inline;padding-right:1%'><a href='mailto:support@videomashplugin.com?Subject=Support%20request'>Support</a></li>
        </ul></div>";



}




function videomash_html_settings(){
        
    global $wpdb, $videomash_urls, $videomash_slugs;  #} Req
    
    $vmConfig = array();
    $vmConfig['ShowScore']  =           get_option('vm_ShowScore');
    $vmConfig['trans_id']   =           get_option('vm_pstrans_id');
	$vmConfig['fb']  		=           get_option('vm_fbcomments');
	$vmConfig['fb2']  		=           get_option('vm_fbcomments2');
	$vmConfig['ss']  	 	=           get_option('vm_socialshare');
	$vmConfig['tr2'] 		=           get_option('vm_topratings');
	$vmConfig['cp'] 		=           get_option('vm_custompage');
	$vmConfig['tw'] 		=           get_option('vm_topwidth');
    
    ?>
    
	 <script type="text/javascript">var loadingImg = '<?php echo plugins_url( 'i/loading.gif' , __FILE__ ); ?>';</script>
     <form action="?page=<?php echo $videomash_slugs['settings']; ?>&save=1" method="post">
     <div class="postbox">
     <h3><label>General settings</label></h3>
     
     <table class="form-table" width="100%" border="0" cellspacing="0" cellpadding="6">
         
        <tr valign="top">
            <td width="25%" align="left"><strong>item purchase code:</strong></td>
            <td align="left"><input id=pstrans_id name=pstrans_id type="text" placeholder="item purchase code" size="20" value = '<?php echo $vmConfig['trans_id']; ?>' /><br><i>This is the purchase code contained in your License Certificate on CodeCanyon</i></td>
        </tr>

        <tr valign="top">
                        <td width="25%" align="left"><strong>Show Scores:</strong></td>
                        <?php if($vmConfig['ShowScore'] == 'yes'){ ?>
                        <td align="left">
                            <input type="radio" name="ShowScore" value="yes" checked> Yes
                            <input type="radio" name="ShowScore" value="no"> No
                            <br><i>Would you like to display the rankings, wins and losses below each video?</i>
                        </td>
                        <?php }else{ ?>
                         <td align="left">
                            <input type="radio" name="ShowScore" value="yes"> Yes
                            <input type="radio" name="ShowScore" value="no" checked> No
                            <br><i>Would you like to display the rankings, wins and losses below each video?</i>
                        </td>
                        <?php } ?>
         </tr>
         <tr valign="top">
                        <td width="25%" align="left"><strong>Show Facebook comments:</strong></td>
                        <?php if($vmConfig['fb'] == 'yes'){ ?>
                        <td align="left">
                            <input type="radio" name="fb" value="yes" checked> Yes
                            <input type="radio" name="fb" value="no"> No
                            <br><i>Display facebook comments instead of WordPress comments on each videos page?</i>
                        </td>
                        <?php }else{ ?>
                         <td align="left">
                            <input type="radio" name="fb" value="yes"> Yes
                            <input type="radio" name="fb" value="no" checked> No
                            <br><i>Display facebook comments instead of WordPress comments on each videos page?</i>
                        </td>
                        <?php } ?>
         </tr>
         
         <tr valign="top">
                        <td width="25%" align="left"><strong>Show Facebook comments below image:</strong></td>
                        <?php if($vmConfig['fb2'] == 'yes'){ ?>
                        <td align="left">
                            <input type="radio" name="fb2" value="yes" checked> Yes
                            <input type="radio" name="fb2" value="no"> No
                            <br><i>Show the dual facebook comments on the video mash page</i>
                        </td>
                        <?php }else{ ?>
                         <td align="left">
                            <input type="radio" name="fb2" value="yes"> Yes
                            <input type="radio" name="fb2" value="no" checked> No
                            <br><i>Show the dual facebook comments on the video mash page</i>
                        </td>
                        <?php } ?>
         </tr>
         
         <tr valign="top">
                        <td width="25%" align="left"><strong>Show ratings on the top rated page</strong></td>
                        <?php if($vmConfig['tr2'] == 'yes'){ ?>
                        <td align="left">
                            <input type="radio" name="tr2" value="yes" checked> Yes
                            <input type="radio" name="tr2" value="no"> No
                            <br><i>Show ratings, wins, losses on the top and bottom rated pages</i>
                        </td>
                        <?php }else{ ?>
                         <td align="left">
                            <input type="radio" name="tr2" value="yes"> Yes
                            <input type="radio" name="tr2" value="no" checked> No
                               <br><i>Show ratings, wins, losses on the top and bottom rated pages</i>
                        </td>
                        <?php } ?>
         </tr>
         
         
          <tr valign="top">
                        <td width="25%" align="left"><strong>Show Social Share:</strong></td>
                        <?php if($vmConfig['ss'] == 'yes'){ ?>
                        <td align="left">
                            <input type="radio" name="ss" value="yes" checked> Yes
                            <input type="radio" name="ss" value="no"> No
                            <br><i>Show Tweet, Facebook like, Pin it, Google+1?</i>
                        </td>
                        <?php }else{ ?>
                         <td align="left">
                            <input type="radio" name="ss" value="yes"> Yes
                            <input type="radio" name="ss" value="no" checked> No
                            <br><i>Show tweet, Facebook like, pint it, google+1?</i>
                        </td>
                        <?php } ?>
         </tr>
         
         <tr valign="top">
                        <td width="25%" align="left"><strong>Show link to custom page:</strong></td>
                        <?php if($vmConfig['cp'] == 'yes'){ ?>
                        <td align="left">
                            <input type="radio" name="cp" value="yes" checked> Yes
                            <input type="radio" name="cp" value="no"> No
                            <br><i><span style = 'color:red'> experimental </span>Show link to custom page for the image:</i>
                        </td>
                        <?php }else{ ?>
                         <td align="left">
                            <input type="radio" name="cp" value="yes"> Yes
                            <input type="radio" name="cp" value="no" checked> No
                             <br><i><span style = 'color:red'> experimental </span>Show link to custom page for the image:</i>
                        </td>
                        <?php } ?>
         </tr>

                 
         
    </table>
    <p id="footerSub"><input class = "button-primary" type="submit" value="Save settings" /></p>
    </form>
</div>

<div class="postbox">
     <h3><label>Reset Video Mash Ratings</label></h3>
     <div class = 'inside'>

	Here you can reset the Video Mash Ratings. Any scores and ratings that your photos have built up will be lost. <b>This cannot be undone</b>. 

     <div id = 'vm-feedback'></div>
     
     <form id = "vm-reset" action="" method="post">
           <p id="footerSub">
              <input class = "button-primary" type="submit" value="Reset Scores" />
           </p>
     </form>
     
     </div>
     
</div>



<?php }




#Expand url function
function expand_url($url){
       $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_FOLLOWLOCATION => TRUE,  // the magic sauce
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYHOST => FALSE, // suppress certain SSL errors
        CURLOPT_SSL_VERIFYPEER => FALSE, 
    ));
    curl_exec($ch);
    $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return $url;
}

#} Save options changes
function videomash_html_save_settings(){
    
    global $wpdb, $videomash_urls, $videomash_slugs;  #} Req
    
    $vmConfig = array();
    $vmConfig['ShowScore'] 		=           $_POST['ShowScore'];
    $vmConfig['trans_id'] 		=           $_POST['pstrans_id'];
    $vmConfig['NEXTG'] 			=           $_POST['NEXTG'];
    $vmConfig['fb'] 			= 			$_POST['fb'];
    $vmConfig['fb2'] 			= 			$_POST['fb2'];
	$vmConfig['tr2'] 			= 			$_POST['tr2'];
	$vmConfig['ss'] 			= 			$_POST['ss'];
	$vmConfig['cp'] 			= 			$_POST['cp']; //custom page
	$vmConfig['tw'] 			= 			(int)$_POST['tw']; //custom page
    
    #} Save down
    update_option("vm_ShowScore", $vmConfig['ShowScore']);
    update_option("vm_pstrans_id", $vmConfig['trans_id']);
	update_option('vm_fbcomments',$vmConfig['fb']);
	update_option('vm_fbcomments2',$vmConfig['fb2']);
	update_option('vm_topratings',$vmConfig['tr2']);
	update_option('vm_socialshare',$vmConfig['ss']);
    update_option('vm_custompage', $vmConfig['cp']);
	update_option('vm_topwidth', $vmConfig['tw']);

    #} Msg
    videomash_html_msg(0,"Saved options");
    
    #} Run standard
    videomash_html_settings();
    
}


#} Outputs HTML message
function videomash_html_msg($flag,$msg,$includeExclaim=false){
    
    if ($includeExclaim){ $msg = '<div id="sgExclaim">!</div>'.$msg.''; }
    
    if ($flag == -1){
        echo '<div class="sgfail wrap">'.$msg.'</div>';
    }
    if ($flag == 0){ ?>
        <div id="message" class="updated fade below-h2"><p><strong>Settings saved!</strong></p></div>
    <?php }
    if ($flag == 1){
        echo '<div class="sgwarn wrap">'.$msg.'</div>';
    }
    if ($flag == 2){
        echo '<div class="sginfo wrap">'.$msg.'</div>';
    }
    if ($flag == 666){ ?>
        <div id="message" class="updated fade below-h2"><p><strong><?php echo $msg; ?>!</strong></p></div>
    <?php }
}

// Twitter like N min/sec ago timestamp in JS
function timeAgo($timestamp,$output = 'less than a minute ago') {
    $timestamp = time() - $timestamp;
    $units = array(604800=>'week',86400=>'day',3600=>'hour',60=>'minute');
    foreach($units as $seconds => $unit) {
        if($seconds<=$timestamp) {
            $value = floor($timestamp/$seconds);
            $output = 'about '.$value.' '.$unit.($value == 1 ? NULL : 's').' ago';
            break;
        }
    }
    return $output;
}



// HOME
// HOME
// HOME
// HOME
// HOME

function videomashLoop( $atts ) {
	extract( shortcode_atts( array(
		'author' => 'true',
		'cats' => 'true',
		'gallery' => 'true',
		'fromtime' => '',
	
	), $atts ) );
    
    global $wp_query,$paged,$post,$wp,$wpdb;
	
    $content = null;
$fromtime = (int)$fromtime;
    ob_start();
	
	
	
    echo "<br/>";
	
  ?>
     
        
	 <script type="text/javascript">var loadingImg = '<?php echo plugins_url( 'i/ps-vote.gif' , __FILE__ ); ?>';</script>
     
    <div class = 'videomashvoting2' style = 'overflow:hidden'>
    <?php $i = 0;

	$taxonomy = "'mash-cats'";
	$post_status = "'publish'";
	$post_type = "'videomash'";
	
	$query = "SELECT * FROM $wpdb->posts WHERE post_type = 'videomash' AND post_status = 'publish' AND DATE_SUB(UTC_TIMESTAMP(),INTERVAL 80 MINUTE) <= post_date ORDER BY RAND() LIMIT 0,2";
	
	  

	
	
	if($fromtime != ''){
		if($fromtime != '0'){
		$query = "SELECT * FROM $wpdb->posts
		LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
    	WHERE $wpdb->postmeta.meta_key = 'wpcf-utc-offset'
		AND HOUR(CONVERT_TZ(post_date, '+00:00',$wpdb->postmeta.meta_value)) = $fromtime 
		ORDER BY RAND() LIMIT 0,2";
		}
	}
	
	/*
	
	final
	
	AND (HOUR(post_date) + $wpdb->postmeta.meta_value) >= $fromtime  AND HOUR(post_date)+ $wpdb->postmeta.meta_value) <= $totime
	
	a tester
	
	
			AND $date = strtotime(post_date)
	AND $usertime = ($date + $utc);

	AND $heure = date('H:i', $date);
	
	dateadd(ss, $wpdb->postmeta.meta_value, post_date) 
	
	
	*/

elseif($cats == 'true' && $author == 'true'){
		
	}else{
	echo "You can only filter by either author or category";
	die();
	}	


	
	$pictures = $wpdb->get_results($query);

    
        $i=0;

        	foreach($pictures as $picture){
	        $thumb[$i] = get_post_meta($picture->ID,'iframv',true);
			$date[$i] = strtotime(get_post_meta($picture->ID, 'date', true));
	        $postid[$i] = $picture->ID;
	        $rating[$i] = get_post_meta($picture->ID,'rating',true);
	        $votes[$i] = get_post_meta($picture->ID,'wins',true);
	        $losses[$i] = get_post_meta($picture->ID,'losses',true);
		
		
			$origposttime[$i] = get_the_time($d, $post->ID);
		
		$twitterimg[$i] = get_post_meta($picture->ID,'wpcf-imageauthor',true);
		$twitterid[$i] = get_post_meta($picture->ID,'wpcf-guid',true);
		$gmttime[$i] = get_post_meta($picture->ID, 'date', true);
		$utc[$i] = get_post_meta($picture->ID,'wpcf-utc-offset',true);
	$localtime[$i] = sum_the_time(date('H:i', $date[$i]),$utc[$i]);
	$vine[$i] = get_post_meta($picture->ID,'wpcf-vine',true);
		//$vine[$i] = myo_youtube($thumb[$i]); 
		$vinemeta[$i] = getvinemeta($vine[$i]);
	$timeago[$i] = timeago($date[$i]);
$locfeed[$i] = get_post_meta($picture->ID,'wpcf-location',true);
$cattime = $fromtime.":00 ";
//$utctime[$i] = explode(":",$utc[$i])

$utctime[$i] = str_replace(':00',' hours',$utc[$i]);
$localphp[$i] = date('H:i',strtotime ( $utctime[$i] , $date[$i] ));
$more[$i] = get_permalink($postid[$i]);
//$localphp[$i]    =  strtotime(, ));


	        $i=$i+1;
	        }
        

    $ShowS = get_option('vm_ShowScore','yes','','yes');

    ?>
    
     <?php if(function_exists('ps_ajax_upload')){
    	$free = 'no';
    }else{
    	$free = 'yes';
    }
    
    ?>
    
    <div id = 'videomash'>

<?php responsive_container(); // before container hook ?>


		<div id="container" class="hfeed">
     
	

  <?php
  
  /*
$tid = explode( 'http://twitter.com/', $twitterid[0] ); 
$id = current( explode( '/statuses', $tid[1] ) );
$json = file_get_contents("https://api.twitter.com/1/statuses/user_timeline/$id.json?count=10", true); //getting the file content
$decode = json_decode($json, true); //getting the file content as array

echo "<pre>";
echo "<img src=\"".$decode[0][user][profile_image_url]."\"</img><br>"; //getting the profile image
echo "Name: ".$decode[0][user][name]."<br>"; //getting the username
echo "Location: ".$decode[0][user][location]."<br>"; //user location
echo "Time zone: ".$decode[0][user][timezone]."<br>"; //time zone
*/


?>
             

<div class="grid col-460">
      

      
      			<a href="#" class="mute1 mute-button off"></a>
<div class = "ps-img ps-img-left">
   
<video id='<?php echo $postid[0];?>' class='video1 video-js vjs-default-skin' width='420' height='420' poster='<?php echo $vinemeta[0][0]; ?>' autoplay preload='auto' loop>
  <source type='video/mp4' src='<?php echo $vinemeta[0][1]; ?>'>
</video>
   
  </div>
    
<div class="detail">
<span class= 'ps-ago-left timeago'><a href="<?php echo $more[0] ?>" title="Lightvote profile"> <?php echo $timeago[0]; ?></a></span>  <span class= 'ps-catname-left location'><?php echo $locfeed[0]; ?></span>

     	</div>
      		

    
 
            
          		  <div class="ps-vote-left"><div class = "ps-vote" data-ps-fromtime = "<?php echo $fromtime;?>" data-ps-winner = "<?php echo $postid[0];?>" data-ps-loser = "<?php echo $postid[1];?>" onclick = "pslikevote()">VOTE</div>
				</div>
       			
              </div>

<div class="grid col-460 fit"> 
         
          	  <a href="#" class="mute2 mute-button off"></a>
     <div class = "ps-img ps-img-right">
    
<video id='<?php echo $postid[1];?>' class='video2 video-js vjs-default-skin' width='420' height='420' poster='<?php echo $vinemeta[1][0]; ?>' autoplay preload='auto' loop>
  <source type='video/mp4' src='<?php echo $vinemeta[1][1]; ?>'>
</video>

	</div>
    
  
<div class="detail">
<span class= 'ps-ago-right timeago'><a href="<?php echo $more[1] ?>" title="Lightvote profile"> <?php echo $timeago[1]; ?></a></span>  <span class= 'ps-catname-right location'><?php echo $locfeed[1]; ?></span>

</div>
      		
          
          		 <div class="ps-vote-right"><div class = "ps-vote" data-ps-fromtime = "<?php echo $fromtime;?>" data-ps-winner = "<?php echo $postid[1];?>" data-ps-loser = "<?php echo $postid[0];?>" onclick = "pslikevote()">VOTE</div>
			</div>
  

</div>
    
      <script>


	jQuery(function () {



			var player = _V_('<?php echo $postid[0];?>').ready(function () {
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


			var player2 = _V_('<?php echo $postid[1];?>').ready(function () {
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
	</script>
    
    <?php
   
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode("videomash", "videomashLoop");

add_filter( 'manage_edit-videomash_columns', 'my_edit_videomash_columns' ) ;

function my_edit_videomash_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title' ),	
        'video' => __( 'Video' ),
        'rating' => __( 'Rating' ),
		'author' => __('Author'),
        'date' => __( 'Date' )
    );

    return $columns;
}

add_action( 'manage_videomash_posts_custom_column', 'my_manage_videomash_columns', 10, 2 );

function my_manage_videomash_columns( $column, $post_id ) {
    global $post;
	?>

	<?php
    switch( $column ) {

        /* If displaying the 'rating' column. */
            case 'video' :

             echo vm_iframe($post_id);

            break;
        
        
        case 'rating' :

            /* Get the post meta. */
            echo number_format(get_post_meta( $post_id, 'rating', true ),0);

            break;

        /* If displaying the 'genre' column. */
        case 'wins' :
            
            echo number_format(get_post_meta( $post_id, 'wins', true ),0);
            

            break;
        
        case 'losses' :
            
            echo number_format(get_post_meta( $post_id, 'losses', true ),0);
            
            break;


        /* Just break out of the switch statement for everything else. */
default:
            break;
    }
}

add_filter( 'manage_edit-videomash_sortable_columns', 'my_videomash_sortable_columns' );

function my_videomash_sortable_columns( $columns ) {

    $columns['rating'] = 'rating';
    $columns['wins'] = 'wins';
    $columns['losses'] = 'losses';
    

    return $columns;
}

function file_get_contents_curl($url) {
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_URL, $url);
	
	$data = curl_exec($ch);
	curl_close($ch);
	
	return $data;
}


/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'my_edit_videomash_load' );

function my_edit_videomash_load() {
    add_filter( 'request', 'my_sort_videomash' );
}

/* Sorts the pics. */
function my_sort_videomash( $vars ) {

    /* Check if we're viewing the 'videomash' post type. */
    if ( isset( $vars['post_type'] ) && 'videomash' == $vars['post_type'] ) {

        /* Check if 'orderby' is set to 'rating'. */
        if ( isset( $vars['orderby'] ) && 'rating' == $vars['orderby'] ) {

            /* Merge the query vars with our custom variables. */
            $vars = array_merge(
                $vars,
                array(
                    'meta_key' => 'rating',
                    'orderby' => 'meta_value_num'
                )
            );
        }
        
        if ( isset( $vars['orderby'] ) && 'wins' == $vars['orderby'] ) {

            /* Merge the query vars with our custom variables. */
            $vars = array_merge(
                $vars,
                array(
                    'meta_key' => 'wins',
                    'orderby' => 'meta_value_num'
                )
            );
        }
        
            if ( isset( $vars['orderby'] ) && 'losses' == $vars['orderby'] ) {

            /* Merge the query vars with our custom variables. */
            $vars = array_merge(
                $vars,
                array(
                    'meta_key' => 'losses',
                    'orderby' => 'meta_value_num'
                )
            );
        }
    }

    return $vars;
}





//code for the top rated and bottom rated


// the perfect day
// the perfect day

function ps_ajax_perfectday(){
	global $wp_query,$paged,$post,$wp,$wpdb;
	
	if(isset($_POST['perfectid'])){
		
	$perfectid = $_POST['perfectid'];
	
	}
	
	if ($perfectid == 24){
		$perfectid = 0;
	}
	 
   $query = "SELECT * FROM (SELECT ID,HOUR(CONVERT_TZ(d.post_date, '+00:00',meta2.meta_value)) as hour, meta1.meta_value as rating, meta3.meta_value as location, meta4.meta_value as iframv, meta5.meta_value as vine, meta6.meta_value as utctime from  $wpdb->posts d
					JOIN  $wpdb->postmeta meta2 ON meta2.post_id = d.ID
				JOIN  $wpdb->postmeta meta1 ON meta1.post_id = d.ID
				JOIN  $wpdb->postmeta meta3 ON meta3.post_id = d.ID
				JOIN  $wpdb->postmeta meta4 ON meta4.post_id = d.ID
				JOIN  $wpdb->postmeta meta5 ON meta5.post_id = d.ID
				JOIN  $wpdb->postmeta meta6 ON meta6.post_id = d.ID
				AND meta2.meta_key = 'wpcf-utc-offset'
				AND meta1.meta_key = 'rating'
				AND meta3.meta_key = 'wpcf-location'
				AND meta4.meta_key = 'iframv'
				AND meta5.meta_key = 'wpcf-vine'
				AND meta6.meta_key = 'wpcf-utc-time'

AND HOUR(CONVERT_TZ(d.post_date, '+00:00',meta2.meta_value)) = $perfectid

ORDER BY rating DESC) AS a

GROUP BY a.hour


		
		";
		
		/* backup groups
		ORDER by field(HOUR,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,0,1,2,3,4,5,6)
		
		*/
		
	
	$pictures = $wpdb->get_results($query);
    $i=$perfectid;
    foreach($pictures as $picture){
	    $thumb[$i] = $picture->iframv;
		//$date[$i] = strtotime(get_post_meta($picture->ID, 'date', true));
		$postid[$i] = $picture->ID;
		$link[$i] = get_permalink($postid[$i]); 
	//$vine[$i] = myo_youtube($thumb[$i]); 
	
	//$timeago[$i] = timeago($date[$i]);
	//$twitterimg[$i] = get_post_meta($picture->ID,'wpcf-imageauthor',true);
	//$twitterid[$i] = get_post_meta($picture->ID,'wpcf-guid',true);
	$locfeed[$i] = $picture->location;
	$cattime[$i] = $picture->hour;
	$vine[$i] = $picture->vine;
	$vinemeta[$i] = getvinemeta($vine[$i]);
	$utctime[$i] = $picture->utctime;

	

		$i++;
	}
	
	$response['thumb'] = $thumb;
	
	$response['postid'] = $postid;
	$response['link'] = $link;
	$response['locfeed'] = $locfeed;
	$response['cattime'] = $cattime;
	$response['perfectid'] = $perfectid;
	$response['vine'] = $vine;
	$response['vinemeta'] = $vinemeta;
	$response['utctime'] = $utctime;

	
	echo json_encode($response);
	
	die();
	
}
// the perfect day
// the perfect day

function vmtopratedpic( $atts ) {
	extract( shortcode_atts( array(
		'author' => 'true',
		'cats' => 'true',
		'gallery' => 'true',
		'limit' => '100',
		'leader' => 'true',
		'perfectday' => 'true',
		
	), $atts ) );
    
	global $wp_query,$paged,$post,$wp,$wpdb;

    $content = null;
	$limit = (int)$limit;

    ob_start();
    
	$trw = get_option('topwidth');
	$trw2 = $trw . "px";
    
    ?>

	
	
	<div class = 'videomashvoting'>
	<?php $i = 0;
	$taxonomy = "'mash-cats'";
	$post_status = "'publish'";
	$post_type = "'videomash'";
	
	
	$query = 	 "SELECT $wpdb->posts.* FROM $wpdb->posts 
				  INNER JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID
				  WHERE 1=1
				  AND $wpdb->posts.post_type = $post_type
				  AND $wpdb->posts.post_status = $post_status
				  AND $wpdb->postmeta.meta_key = 'rating'
				  GROUP BY $wpdb->posts.ID
				  ORDER BY $wpdb->postmeta.meta_value DESC LIMIT $limit";
	
	if($gallery == 'true' && $author == 'true' && $cats != 'true'){
		$query = "SELECT $wpdb->posts.* FROM $wpdb->posts 
				  INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
				  INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
				  INNER JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID
				  WHERE 1=1
				  AND $wpdb->term_taxonomy.taxonomy = $taxonomy
				  AND $wpdb->term_taxonomy.term_id = $cats
				  AND $wpdb->posts.post_type = $post_type
				  AND $wpdb->posts.post_status = $post_status
				  AND $wpdb->postmeta.meta_key = 'rating'
				  GROUP BY $wpdb->posts.ID
				  ORDER BY $wpdb->postmeta.meta_value DESC LIMIT $limit";
	}
	
	elseif($perfectday == 'true'){
		
		$query = "SELECT * FROM (SELECT ID,post_author, HOUR(CONVERT_TZ(d.post_date, '+00:00',meta2.meta_value)) as hour, meta1.meta_value as rating, meta3.meta_value as location, meta4.meta_value as iframv, meta5.meta_value as vine, meta6.meta_value as utctime from  $wpdb->posts d
					JOIN  $wpdb->postmeta meta2 ON meta2.post_id = d.ID
				JOIN  $wpdb->postmeta meta1 ON meta1.post_id = d.ID
				JOIN  $wpdb->postmeta meta3 ON meta3.post_id = d.ID
				JOIN  $wpdb->postmeta meta4 ON meta4.post_id = d.ID
				JOIN  $wpdb->postmeta meta5 ON meta5.post_id = d.ID
				JOIN  $wpdb->postmeta meta6 ON meta6.post_id = d.ID
				AND meta2.meta_key = 'wpcf-utc-offset'
				AND meta1.meta_key = 'rating'
				AND meta3.meta_key = 'wpcf-location'
				AND meta4.meta_key = 'iframv'
				AND meta5.meta_key = 'wpcf-vine'
				AND meta6.meta_key = 'wpcf-utc-time'

ORDER BY rating DESC) AS a

GROUP BY a.hour

ORDER by field(HOUR,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,0,1,2,3,4,5,6)

";

	}else{
	
	
	
	echo "You can only filter by either author, category or gallery";
	die();
	}	

	
	$pictures = $wpdb->get_results($query);
		?>
        
      <div id = 'videomash'>

<?php responsive_container(); // before container hook ?>


		<div id="container" class="hfeed">
   <div class="grid col-940 ">
	
	<?php //echo var_dump($pictures) ?>
  
	
   
  
       <div id="perfect-day-time" class="perfect-day-time">
       <div class="perfect-video"></div>
<div class="perfect-hour"></div>
<div class="perfect-location">"The perfect day"</div>
 <div class="ps-voteperfect"><div class = "ps-vote" data-perfectid = "6" onclick = "perfectday()">WATCH</div>
</div>

	
  
  
		</div>		</div>		</div>	
      
        <table class="leaderboard">
          <tr class="leaderboard-tr">
          <td colspan="3" class="leaderboard-title">CREDITS<br />   <span class="leaderboard-ago">updated live !</span></td>
          </tr>
        <?php

		if($leader == 'true'){
	   
    foreach($pictures as $picture){
		
		
	    $thumb[$i] = $picture->iframv;
		$date[$i] = strtotime(get_post_meta($picture->ID, 'date', true));
		$postid[$i] = $picture->ID;
		$link[$i] = get_permalink($postid[$i]); 
	//$vine[$i] = myo_youtube($thumb[$i]); 
		//$vinemeta[$i] = getvinemeta($vine[$i]);
	$timeago[$i] = timeago($date[$i]);
	$twitterimg[$i] = get_post_meta($picture->ID,'wpcf-imageauthor',true);
	//$twitterid[$i] = get_post_meta($picture->ID,'wpcf-guid',true);
	$locfeed[$i] = $picture->location;
	$cattime[$i] = sprintf('%02d',$picture->hour);
	$vine[$i] = $picture->vine; 
	$utctime[$i] = $picture->utctime; 
	$author_id[$i]= $picture->post_author;
	
	
//$cattime = sum_the_time(date('H', $date),$utc);

	
	?>
   

        <tr class="leaderboard-tr">
        
        <td class="leaderboard-time">
         <a href="../vote-for-the-best-<?php echo $cattime[$i] ?>00-moments/" title=""><?php echo $cattime[$i]?>:00</a><br />
         <span class="leaderboard-locfeed"><?php echo $locfeed[$i] ?></span>
        </td>
      
         <td class="leaderboard-name">
      <a href="<?php echo $link[$i]?>" title="Lightvote <?php echo the_author_meta( 'nickname',$author_id[$i]) ?>"><?php echo the_author_meta( 'nickname',$author_id[$i]) ?> <br /></a>
      <span class="leaderboard-ago"> <?php echo $timeago[$i]?></span><br />
        </td>
          <td class="leaderboard-img">
        <a href="<?php echo $link[$i]?>" title="Lightvote <?php echo the_author_meta( 'nickname',$author_id[$i]) ?>"><img src="<?php echo $twitterimg[$i]?>" title="Lightvote <?php echo the_author_meta( 'nickname',$author_id[$i]) ?>" alt="Lightvote <?php echo the_author_meta( 'nickname',$author_id[$i]) ?>"/></a>
        </td>
        
        </tr>
        
        
    <?php
		$i++;
	}
	
	
   ?> 
        
        </table>
        
        
        
        
<?php
		}
		
wp_reset_query();  // Restore global post data stomped by the_post().
 
   
   
?>


</div>
    
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode("vmtoprated", "vmtopratedpic");

//code for manually adding new

add_action( 'add_meta_boxes', 'vm_custom_meta_box' );

function vm_custom_meta_box() {
      add_meta_box( 'video link', 'Video URL', 'ps_video_rating', 'videomash', 'normal', 'high' );
}

function myo_youtube($url) {
	

preg_match('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', $url, $iframvfull);

if (empty($iframvfull[0])) {

} else {
	
$url = $iframvfull[0];

}

$url = expand_url($url);	
	
	 $pattern = 
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          t\.co/    # Either youtu.be,
        | vine\.co  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )            # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
        ;
    $result = preg_match($pattern, $url, $matches);
    if (false !== $result) {
        return $matches[0];
    }
    return false;
}

function getvinemeta($vineurl) {
	
	if (empty($vineurl)) {

} else {
	

$sites_html = file_get_contents_curl($vineurl);

$html = new DOMDocument();
@$html->loadHTML($sites_html);

$result = array();
//Get all meta tags and loop through them.
foreach($html->getElementsByTagName('meta') as $meta) {
    //If the property attribute of the meta tag is og:image
    if($meta->getAttribute('property')=='twitter:image'){ 
        //Assign the value from content attribute to $vineimg
        $result[] = $meta->getAttribute('content');
    }
 if($meta->getAttribute('property')=='twitter:player:stream'){ 
        //Assign the value from content attribute to $vinemp4
       $result[] = $meta->getAttribute('content');
    }
}

return $result;
 
}
}





function ps_video_rating() {
    global $wp, $wpdb, $post;

    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="ps_video_noncename" id="ps_video_noncename" value="' .
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    // Get the location data if its already been entered

    $iframv = get_post_meta($post->ID, 'iframv', true);

    $rating = get_post_meta($post->ID, 'rating', true);
    $win = get_post_meta($post->ID, 'wins', true);
    $lose = get_post_meta($post->ID, 'losses', true);

    
    if($rating == 0){
        $rating = 1400;
    }

    // Echo out the field
    ?>
	
	<div id = 'ps_hold_img' style = 'width:100%;overflow:hidden;'>

	<div id = 'ps_img' style = 'width:380px;float:left;padding:10px'>
	<?php if($iframv == false){ ?>

	<?php }else{
	
preg_match('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', $iframv, $iframvfull);

$iframv = $iframvfull[0];
$iframv = expand_url($iframv);



		
		?>
        
        	<iframe class="vine-embed" src="<?php echo $iframv ?>/embed/simple" width="320" height="320" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>

        
        <?php
			
		
			
		
		

     } ?>

	</div>

	<div id = 'ps_img' style = 'width:45%;float:right;padding:2.5%;text-align:center'>
	<?php if($iframv == false){ ?>

	<?php }else{ 
		
		if($url_check['host'] == 'vine.co/v/'){
			$img_s = plugins_url('/i/Vimeo.png',__FILE__);
			echo "<img src = '$img_s' alt = 'Vine' title = 'Vine' /><br/>";
		}elseif($url_check['host'] == 'vine.co'){
			$img_s = plugins_url('/i/Vimeo.png',__FILE__);
			echo "<img src = '$img_s' alt = 'Vine' title = 'Vine' /><br/>";
			
		}
		
		
		
		?>
		
		
		Rating<b>: <?php echo number_format($rating,0); ?> </b><br/>
		Wins: <b><?php echo number_format($win,0); ?> </b><br/>
		Losses: <b><?php echo number_format($lose,0); ?> </b><br/>
	<?php } ?>

	</div>
	</div>
    
    <script>

		jQuery(function () {

			

			var player = _V_('example_video_1').ready(function () {
			
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

        <tr valign="top">
        <th scope="row">Video URL (Twitter or Vine)</th>
        <td><label for="upload_image">
        <input id="video_url" type="text" size="36" name="video_url" value="<?php echo $iframv; ?>" />

	
	
<?php
    echo '<input type="hidden" name="rating" value="' . $rating  . '" class="widefat" />';
    echo '<input type="hidden" name="wins" value="' . $win  . '" class="widefat" />';
    echo '<input type="hidden" name="losses" value="' . $lose  . '" class="widefat" />';


}


function vm_iframe($ID){
	
	
    $content = null;
    $iframv = get_post_meta($ID, 'iframv', true);

    
    ob_start();
preg_match('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', $iframv, $iframvfull);

if (empty($iframvfull[0])) {

} else {
$iframv = $iframvfull[0];
}
	 $iframv = expand_url($iframv);
		$url_check = parse_url($iframv); 

	
	if($url_check['host'] == 't.co' || $url_check['host'] == 'vine.co'){
		//create the embed iframe from the entered URL
	if($url_check['host'] == 'vine.co'){
	
	$v = myo_youtube($iframv);
			
		if($v == NULL){
					
		}else{
			echo "<iframe class='vine-embed' src='$v/embed/simple' width='320' height='320' frameborder='0'></iframe><script async src='//platform.vine.co/static/scripts/embed.js' charset='utf-8'></script>";			
		}
				
	}elseif($url_check['host'] == 't.co'){
		$videoID = $url_check['path'];
						echo "
						
						<iframe src='<iframe class='vine-embed' src='$videoID/embed/simple' width='320' height='320' frameborder='0'></iframe><script async src='//platform.vine.co/static/scripts/embed.js' charset='utf-8'></script>";		
					}
				}
	
	$content = ob_get_contents();
    ob_end_clean();
    return $content;
	
	}



// Save the Metabox Data
function wpt_save_ps_video_meta($post_id, $post) {

     global $wp, $wpdb;  

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if( !isset($_POST['ps_video_noncename'])){
     return $post->ID;
    }
    if (  !wp_verify_nonce( $_POST['ps_video_noncename'], plugin_basename(__FILE__) )) {
    return $post->ID;
    }
    
    // Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;
    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.

    
    if(isset($_POST['video_url'])){



    $iframv		 	        = $_POST['video_url'];
    $rating				= $_POST['rating'];
    $wins	 			= $_POST['wins'];
    $losses	 			= $_POST['losses'];

   

    update_post_meta($post->ID, 'iframv', $iframv);
    update_post_meta($post->ID, 'rating', $rating);
    update_post_meta($post->ID, 'wins', $wins);
    update_post_meta($post->ID, 'losses', $losses);
    }
    

}
add_action('save_post', 'wpt_save_ps_video_meta', 1, 2); // save the custom fields


add_filter( 'add_menu_classes', 'vm_show_pending_number');
function vm_show_pending_number( $menu ) {
    $type = "videomash";
    $status = "pending";
    $num_posts = wp_count_posts( $type, 'readable' );
    $pending_count = 0;
    if ( !empty($num_posts->$status) )
        $pending_count = $num_posts->$status;

    // build string to match in $menu array
    if ($type == 'post') {
        $menu_str = 'edit.php';
    } else {
        $menu_str = 'edit.php?post_type=' . $type;
    }

    // loop through $menu items, find match, add indicator
    foreach( $menu as $menu_key => $menu_data ) {
        if( $menu_str != $menu_data[2] )
            continue;
        $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
    }
    return $menu;
}