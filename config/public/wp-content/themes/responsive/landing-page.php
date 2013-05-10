<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Landing Page Template
 *
   Template Name:  Landing Page (no menu)
 *
 * @file           landing-page.php
 * @package        Responsive 
 * @author         Emil Uzelac 
 * @copyright      2003 - 2011 ThemeID
 * @license        license.txt
 * @version        Release: 1.0
 * @filesource     wp-content/themes/responsive/landing-page.php
 * @link           http://codex.wordpress.org/Theme_Development#Pages_.28page.php.29
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

        <div id="content-full" class="grid col-940">
        
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
        
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
              <!--  <h1 class="post-title"><?php the_title(); ?></h1> -->
                
                
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'responsive')); ?>
                    <?php wp_link_pages(array('before' => '<div class="pagination">' . __('Pages:', 'responsive'), 'after' => '</div>')); ?>
                </div><!-- end of .post-entry -->
                
                <?php if ( comments_open() ) : ?>
                <div class="post-data">
				    <?php the_tags(__('Tagged with:', 'responsive') . ' ', ', ', '<br />'); ?> 
                    <?php the_category(__('Posted in %s', 'responsive') . ', '); ?> 
                </div><!-- end of .post-data -->
                <?php endif; ?>             
            
            <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div> 
            </div><!-- end of #post-<?php the_ID(); ?> -->
                        
        <?php endwhile; ?> 
        
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <div class="navigation">
			<div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
            <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
		</div><!-- end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1>
                    
        <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'responsive'); ?></p>
                    
        <h6><?php printf( __('You can return %s or search for the page you were looking for.', 'responsive'),
	            sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
		            esc_url( get_home_url() ),
		            esc_attr__('Home', 'responsive'),
		            esc_attr__('&larr; Home', 'responsive')
	                )); 
			 ?></h6>
                    
        <?php get_search_form(); ?>

<?php endif; ?>  
      
        </div><!-- end of #content-full -->
        
        
 <div class="photo-bar">
      <div class="container_24 large">
        <div class="photo-text"><span class="text-black-bg">YOU ARE THE CREATOR OF &quot;THE PERFECT DAY&quot;</span><br>
          IN OUR BRIGHT AND SHINING WORLD :<br />
          <span class="text-black-bg"></span>
         <a href="the-perfect-day/" title="Watch The Perfet Day"><div class = "ps-vote-pink">WATCH IT LIVE NOW</div></a>
        </div>
      </div>
    </div>  
<div class="timeline">
    <div class="timelinecontent">
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0700-moments/" title=" Vote for the best 07:00 moments">
    <div class="hour">
    07:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0800-moments/" title=" Vote for the best 08:00 moments">
    <div class="hour">
    08:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0900-moments/" title=" Vote for the best 09:00 moments">
    <div class="hour">
    09:00
    </div></a>
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1000-moments/" title=" Vote for the best 10:00 moments">
    <div class="hour">
    10:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1100-moments/" title=" Vote for the best 11:00 moments">
    <div class="hour">
    11:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1200-moments/" title=" Vote for the best 12:00 moments">
    <div class="hour">
    12:00
    </div></a>
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1300-moments/" title=" Vote for the best 13:00 moments">
    <div class="hour">
    13:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1400-moments/" title=" Vote for the best 14:00 moments">
    <div class="hour">
    14:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1500-moments/" title=" Vote for the best 15:00 moments">
    <div class="hour">
    15:00
    </div></a>
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1600-moments/" title=" Vote for the best 16:00 moments">
    <div class="hour">
    16:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1700-moments/" title=" Vote for the best 17:00 moments">
    <div class="hour">
    17:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1800-moments/" title=" Vote for the best 18:00 moments">
    <div class="hour">
    18:00
    </div></a>
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-1900-moments/" title=" Vote for the best 19:00 moments">
    <div class="hour">
    19:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-2000-moments/" title=" Vote for the best 20:00 moments">
    <div class="hour">
    20:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-2100-moments/" title=" Vote for the best 21:00 moments">
    <div class="hour">
    21:00
    </div></a>
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-2200-moments/" title=" Vote for the best 22:00 moments">
    <div class="hour">
    22:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-2300-moments/" title=" Vote for the best 23:00 moments">
    <div class="hour">
    23:00
    </div></a>
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0000-moments/" title=" Vote for the best 00:00 moments">
    <div class="hour">
    00:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0100-moments/" title=" Vote for the best 01:00 moments">
    <div class="hour">
    01:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0200-moments/" title=" Vote for the best 02:00 moments">
    <div class="hour">
    02:00
    </div></a>
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0300-moments/" title=" Vote for the best 03:00 moments">
    <div class="hour">
    03:00
    </div></a>
    <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0400-moments/" title=" Vote for the best 04:00 moments">
    <div class="hour">
    04:00
    </div></a>
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0500-moments/" title=" Vote for the best 05:00 moments">
    <div class="hour">
    05:00
    </div></a>
     <a href="http://s149687188.onlinehome.fr/lightvote/vote-for-the-best-0600-moments/" title=" Vote for the best 06:00 moments">
    <div class="hour">
    06:00
    </div></a>
    </div>
</div>
    

        

<?php get_footer(); ?>