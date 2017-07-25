<?php
/*
Plugin Name: Andio picture voting
Plugin URI: 
Description: Picture voting plugin for WordPress. The uploaded pictures are stored as a post, with the type "picture-vote". 
              The visitors can vote for the picture with nice up-down buttons. The votes for the pictures are stored.
Author: BT
Version: 1.0
Text Domain: p-vote
Author URI: https://andio.biz

*/ 

define( 'P_VOTE_PATH', plugin_dir_path( __FILE__ ) );
define( 'P_VOTE_LOCATION', plugin_basename(__FILE__) );
define( 'P_VOTE_VERSION', '1.0' );
define ( 'P_VOTE_URL', plugins_url( '' ,  __FILE__ ) );

require_once P_VOTE_PATH . 'innovo_api.php';


function create_pvote_post_types() {
    	 
    	 global $pvote_options;
    	 
    	 $labels = array(
    		'name' => _x( 'Picture Vote Categories', 'p-vote' ),
    		'singular_name' => _x( 'Picture Vote Category', 'p-vote'),
    		'search_items' =>  __( 'Search Picture Vote Categories', 'p-vote'),
    		'all_items' => __( 'All Picture Vote Categories', 'p-vote' ),
    		'parent_item' => __( 'Parent Picture Vote Category', 'p-vote' ),
    		'parent_item_colon' => __( 'Parent Picture Vote Category:', 'p-vote'),
    		'edit_item' => __( 'Edit Picture Vote Category', 'p-vote'), 
    		'update_item' => __( 'Update Picture Vote Category', 'p-vote'),
    		'add_new_item' => __( 'Add New Picture Vote Category', 'p-vote'),
    		'new_item_name' => __( 'New Picture Vote Category Name', 'p-vote')
      	);
    
      	
      	register_post_type( 'picture-vote',
    		array(
    			'labels' => array(
    				'name' => __( 'PVOTEs', 'p-vote' ),
    				'singular_name' => __( 'PVOTE', 'p-vote' ),
    				'edit_item'	=>	__( 'Edit PVOTE', 'p-vote'),
    				'add_new_item'	=>	__( 'Add PVOTE', 'p-vote')
    			),
    			'public' => true,
    			'show_ui' => true,
    			'capability_type' => 'post',
    			//'rewrite' => array( 'slug' => 'pvotes', 'with_front' => false ),
    			'rewrite' => false,
    			'taxonomies' => array( 'pvotes'),
    			'supports' => array('title','editor')	
    		)
    	); 	
      
      	register_taxonomy('pvote_category',array('picture-vote'), array(
    		'hierarchical' => true,
    		'labels' => $labels,
    		'show_ui' => true,
    		'query_var' => true,
    		'rewrite' => false
    		//'rewrite' => array( 'slug' => 'pvote-category' ),
      ));
    }
    

//create_pvote_post_types();
add_action( 'init', 'create_pvote_post_types', 0 );
	
class Picture_Vote {
    var $person_id = 0;
    var $email = '';
    /**
	 * Instantiating the plugin, adding actions, filters, and shortcodes
	 */
	function __construct() {
		// Init
		add_action( 'init', array( $this, 'action_init' ) );
	}


	/**
	 *  Load languages and a bit of paranoia
	 */
	function action_init() {

		load_plugin_textdomain( 'p-vote', false,  dirname( plugin_basename( __FILE__ ) )  . '/languages/' );
		
		wp_register_script( 'picture-voting-js', P_VOTE_URL.'/js/vote.js' );
		wp_localize_script( 'picture-voting-js', 'ajax_vote', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); 
		
		// Hooking to wp_ajax

		// Currently supported shortcodes
		add_shortcode( 'vote-receipts', array( $this, 'vote_receipts' ) ); //[vote-receipts]


		// Static assets
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		
        add_action( 'wp_ajax_voting_up', 'voting_up', 10, 2 );
        add_action( 'wp_ajax_nopriv_voting_up', 'voting_up', 10, 2 );
		
		// Unautop the shortcode
		add_filter( 'the_content', 'shortcode_unautop', 100 );

	}
	
    /**
	 * Enqueue our assets
	 */
	function enqueue_scripts() {
		wp_enqueue_style( 'picture-vote', P_VOTE_URL . '/css/picture_vote.css' );
		wp_enqueue_style( 'picture-vote-font', 'https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css');
		wp_enqueue_script( 'picture-vote-js', P_VOTE_URL . '/js/receipt_functions.js');
		wp_enqueue_style( 'picture-vote-modal', P_VOTE_URL . '/css/ouibounce.css' );

        wp_enqueue_script( 'picture-voting-js', P_VOTE_URL.'/js/vote.js', array('jquery')); 
	
	}
      
      /**
	 * Enqueue scripts for admin
	 */
	function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( $screen && 'media_page_manage_frontend_uploader' == $screen->base ) {
			wp_enqueue_script( 'media', array( 'jquery' ) );
		}
	}
	
	function vote_receipts() {
	    if ( !empty( $_GET ) ) {
			$rc = $this->_handle_get( );
		}
	    $this->enqueue_scripts();
	    ob_start();
	    echo $this->list_view();
	    return ob_get_clean();
	}
	
	private function list_view() {
	    
	    $args = array(
			'order'         => 'DESC',
			'post_type'     => 'picture-vote',
			'post_status'   => 'publish',
			'posts_per_page' => 1000,
         'meta_key'      => 'vote',
		 'orderby' 		=> 'meta_value_num',
		 'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'vote',
                    'value'   => array(0, 10000 ),
			        'type'    => 'numeric',
			        'compare' => 'BETWEEN',
                )
             ),				
           
		);
		
		$email_address = '-';
		if ( !empty( $_GET ) ) {
			$email_address =  $this->email;
		}
				
	    $i = 1;
		$receipts = new WP_Query( $args );
		$receipts_output .=  '';
		
		while( $receipts->have_posts() ): $receipts->the_post();
			
			global $post;
			$receipt_id = get_the_ID();
			$author = get_post_meta( $receipt_id, 'firstname', true );
			$vote = get_post_meta( $receipt_id, 'vote', true );
			if ($vote == '') {
			    $vote = 0;
			}
			$img_src =  $this->receipt_the_product_image('', '', $receipt_id );
        	$post = get_post($receipt_id);
        	$slug = $post->post_name;			
        	
        	
            /*
            <div class="vote" data-action="down" title="'. __( 'Vote down: ', 'p-vote' ).'">
				<i class="icon-chevron-down"></i>
			</div><!--vote down--> */

			$receipts_output .= '<div class="receipt_content">';
		    $receipts_output .= '<div class="vote_number"> <div class="item" data-postid="'.$receipt_id.'" data-score="'.$vote.'" data-email="'.$email_address.'">';
            /*  $receipts_output .= '			<div class="vote-span"><!-- voting-->
        	                        
        	                        <div class="vote" data-action="up" title="'. __( 'Szavazok: ', 'p-vote' ).'">
                        					<i class="icon-chevron-up"></i>
                        				</div><!--vote up-->*/
                        				                         				
            $receipts_output .=  '         				<div class="vote-score">'.$vote.'</div>
                        				</div>';
                        			
            $receipts_output .=   '     		     </div><!--item-->';
			$receipts_output .=  ' <div class="receipt_number">' . $i . '.</div>';
			$receipts_output .=  '<h1 class="receipt-title">'. get_the_title().'</h1>';
			$receipts_output .=  '<div class="receipt-author">'  . __( 'Bekuldte: ', 'p-vote' ) . $author . '</div>';
			$receipts_output .= '<div class="clearfix"></div>';
			if ($img_src != '') {
			    $receipts_output .=  '<div class="receipt-image"><img src="' . $img_src .'" title = ' . __( 'Andio receipt contest: ', 'p-vote' ) . ' ' .get_the_title().' alt = '.get_the_title().'/></div>';
			}
			$receipts_output .= '<div class="receipt-text">' . apply_filters( 'the_content', get_the_content() );
			$receipts_output .= '</div><!--.receipt-text-->';
    	 
    	    // possible FB-like. Slow!
    	    // 	$receipts_output .= '	
            //      <fb:like href="http://andio.biz/?picture-vote=' . $slug . '" send="false" layout="standard" show_faces="true" font="arial" action="like" colorscheme="light"></fb:like>';
            $receipts_output .= '				</div><!--.receipt_content-->
				<br style="clear:both" />
			';
    		$i++;	
		endwhile; // end loop
		
		/* FB LIKE (slow)
		 <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/hu_HU/all.js#xfbml=1&appId=211513639002196";
                    fjs.parentNode.insertBefore(js, fjs);
                         }(document, \'script\', \'facebook-jssdk\'));</script>
                         
                         <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id)) return;
                            js = d.createElement(s); js.id = id;
                            js.src = "//connect.facebook.net/hu_HU/all.js#xfbml=1&appId=211513639002196";
                            fjs.parentNode.insertBefore(js, fjs);
                                 }(document, \'script\', \'facebook-jssdk\'));</script>
                                <div id="like"><fb:like href="http://andio.biz/legjobb-receptek" send="false" layout="standard" show_faces="true" font="arial" action="like" colorscheme="light"></fb:like></div>
    			                <iframe src="http://andio.eu/page.php?pa=4e5f9f4a-f590-c35f-4f42-5418064bf726&ct='.$this->person_id['id'].'" width="585" height="430"></iframe></div>
                         */
		
		// after voting: popup modal window for subscribing or offer
		if ($this->person_id['id'] != '') {
    		$receipts_output .= ' 
    		            <div id="ouibounce-modal" ><div class="underlay"></div>
    			        <div class="modal">
    			        <div class="modal-body">
    			        
    			            <iframe src="http://andio.eu/page.php?pa=c8ad4bdb-16d5-5029-5ded-566590bf8a35&ct='.$this->person_id['id'].'" width="585" height="430"></iframe></div>
    			        <div class="modal-footer">
                            <a class="close-reveal-modal"><img src="'.P_VOTE_URL.'/img/ico-close.png" alt=""/></a>    
                        </div></div></div>'; 
        } else {
            $receipts_output .= ' <div id="ouibounce-modal" ><div class="underlay"></div>
    			        <div class="modal">
    			        <div class="modal-body">
                           
    			            <iframe src="http://andio.eu/page.php?pa=db8be02d-6770-b8a2-f8e8-56655bc667f9" width="585" height="430"></iframe></div>
    			            
    			        <div class="modal-footer">
                            <a class="close-reveal-modal"><img src="'.P_VOTE_URL.'/img/ico-close.png" alt=""/></a>    
                        </div></div></div>'; 
        }
    	wp_reset_postdata();
    
    	return $receipts_output;			
	}
	
	function _handle_get() {
	    $email = '';
	    if (!empty($_GET['email'])) {
	        $this->email = $_GET['email'];
	    }
	    $innovo = new innovo_api();
		$this->person_id = $innovo->get_subscriber_id($this->email);        
	}
		
    /**
     * wpsc product image function
     * @return string - the URL to the thumbnail image
     */
    function receipt_the_product_image( $width='', $height='', $product_id='' ) {
    	if ( empty( $product_id ) )
    		$product_id = get_the_ID();
    
    
    	$product = get_post( $product_id );
    
    	if ( $product->post_parent > 0 )
    		$product_id = $product->post_parent;
    
    	$attached_images = (array)get_posts( array(
    				'post_type' => 'attachment',
    				'numberposts' => 1,
    				'post_status' => null,
    				'post_parent' => $product_id,
    				'orderby' => 'menu_order',
    				'order' => 'ASC'
    			) );
    
    
    	$post_thumbnail_id = get_post_thumbnail_id( $product_id );
    
    	$src = wp_get_attachment_image_src( $post_thumbnail_id, 'large' );
    
    	if ( ! empty( $src ) && is_string( $src[0] ) ) {
    		$src = $src[0];
    	} elseif ( ! empty( $attached_images ) ) {
    		$attached_image = wp_get_attachment_image_src( $attached_images[0]->ID, 'large' );
    		$src = $attached_image[0];
    	} else {
    		$src = false;
    	}
    
    	if ( is_ssl() && ! empty( $src ) )
    		$src = str_replace( 'http://', 'https://', $src );
    	//$src = apply_filters( 'wpsc_product_image', $src );
    
    	return $src;
    }
    


}	

global $picture_vote;
$picture_vote = new Picture_Vote;


function voting_up() {
    global $post;

    //check IP
    $post_id =  $_POST['postid'];
    $email = $_POST['email'];
     
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip_meta = get_post_meta( $post_id, 'voters', true );
    if ($ip_meta != '') {
        $ips = explode(";", $ip_meta);
        if (in_array($ip, $ips)) {
            wpsyslog('voting up', 'ip address ' . $ip . ' has already voted for post ' . $post_id . ' email: ' .$email, 0);
            return;
        }
    }

   
    $method =  $_POST['method'];
    $old_vote = get_post_meta( $post_id, 'vote', true );
    if ($old_vote == '') $old_vote = 0;
    $vote = $method == 'up' ?  $old_vote + 1 :  $old_vote - 1;
    update_post_meta($post_id, 'vote', $vote); 
    
    //push new ip_address
    if ($ip_meta != '') {
        $ip_meta = $ip . ';' . $ip_meta;
    } else {
        $ip_meta = $ip;
    }
    
    update_post_meta($post_id, 'voters', $ip_meta);    
    
    // vote_reg
    if ($email  != '') {
        $innovo = new innovo_api();
        $innovo->update_status_instance($innovo->KAR2015_SERVICE, $innovo->KAR2015_INSTANCE, $innovo->KAR2015_STATUS_REG_VOTE, $email);
    }
           
}
