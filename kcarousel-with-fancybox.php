<?php
/*
Plugin Name: KCarousel With FancyBox
Plugin URI: http://kadirrazu.info/wp/demo/kcarousel-with-fancybox/
Description: This plugin can be used to display a group of images in Carousel format with Fancybox option for each image.
Version: 1.0.0
Author: Kadir Razu
Author URI: http://www.kadirrazu.info/
License: GPLv2 or later
*/

class KCarousel_Image_Upload {
	
	
	//Constructor Method For KCarousel
	
	public function __construct() 
	{
		
		add_action( 'init', array( &$this, 'kcarousel_init' ) );
		
		if ( is_admin() ) {
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
		}
		
		$plugins_url = plugins_url();
		
	}
	
	
	/**
	 * Register the custom post type
	 */
	 
	public function kcarousel_init() 
	{
	
	    register_post_type( 'kcarousel-img', array( 'public' => true, 'label' => 'KCarousel Images', 'menu_icon' =>  plugins_url( 'images/kcarousel.png', __FILE__ )) );
		
	}
	
	
	/** Admin methods **/
	
	
	/**
	 * Initialize the admin, adding actions to properly display and handle 
	 * the kcarousel custom post type add/edit page
	 */
	 
	public function admin_init() 
	{
		
		global $pagenow;
		
		if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) 
		{
			
			add_action( 'add_meta_boxes', array( &$this, 'meta_boxes' ) );
			
			add_filter( 'enter_title_here', array( &$this, 'enter_title_here' ), 1, 2 );
			
			add_action( 'save_post', array( &$this, 'meta_boxes_save' ), 1, 2 );
		}
	}
	
	
	/**
	 * Save meta boxes
	 * 
	 * Runs when a post is saved and does an action which the write panel save scripts can hook into.
	 */
	 
	public function meta_boxes_save( $post_id, $post ) 
	{
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( $post->post_type != 'kcarousel-img' ) return;
			
		$this->process_kcarousel_meta( $post_id, $post );
	}
	
	
	/**
	 * Function for processing and storing all kcarousel data.
	 */
	private function process_kcarousel_meta( $post_id, $post ) 
	{
		update_post_meta( $post_id, '_image_id', $_POST['upload_image_id'] );
	}
	
	
	/**
	 * Set a more appropriate placeholder text for the New kcarousel title field
	 */
	public function enter_title_here( $text, $post ) 
	{
		if ( $post->post_type == 'kcarousel-img' ) return __( 'KCarousel Image Title' );
		return $text;
	}
	
	
	/**
	 * Add and remove meta boxes from the edit page
	 */
	public function meta_boxes() 
	{
		add_meta_box( 'kcarousel-image', __( 'KCarousel Image' ), array( &$this, 'kcarousel_image_meta_box' ), 'kcarousel-img', 'normal', 'high' );
	}
	
	
	/**
	 * Display the image meta box
	 */
	public function kcarousel_image_meta_box() 
	{
		
		global $post;
		
		$image_src = '';
		
		$image_id = get_post_meta( $post->ID, '_image_id', true );
		$image_src = wp_get_attachment_url( $image_id );
		
		?>
		<img id="kcarousel_image" src="<?php echo $image_src ?>" style="max-width:100%;" />
		<input type="hidden" name="upload_image_id" id="upload_image_id" value="<?php echo $image_id; ?>" />
		<p>
			<a title="<?php esc_attr_e( 'Set KCaurosel image' ) ?>" href="#" id="set-kcarousel-image"><?php _e( 'Set KCarousel image' ) ?></a>
			<a title="<?php esc_attr_e( 'Remove KCarousel image' ) ?>" href="#" id="remove-kcarousel-image" style="<?php echo ( ! $image_id ? 'display:none;' : '' ); ?>"><?php _e( 'Remove KCarousel image' ) ?></a>
		</p>
		
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			
			// save the send_to_editor handler function
			window.send_to_editor_default = window.send_to_editor;
	
			$('#set-kcarousel-image').click(function(){
				
				// replace the default send_to_editor handler function with our own
				window.send_to_editor = window.attach_image;
				tb_show('', 'media-upload.php?post_id=<?php echo $post->ID ?>&amp;type=image&amp;TB_iframe=true');
				
				return false;
			});
			
			$('#remove-kcarousel-image').click(function() {
				
				$('#upload_image_id').val('');
				$('img').attr('src', '');
				$(this).hide();
				
				return false;
			});
			
			// handler function which is invoked after the user selects an image from the gallery popup.
			// this function displays the image and sets the id so it can be persisted to the post meta
			window.attach_image = function(html) {
				
				// turn the returned image html into a hidden image element so we can easily pull the relevant attributes we need
				$('body').append('<div id="temp_image">' + html + '</div>');
					
				var img = $('#temp_image').find('img');
				
				imgurl   = img.attr('src');
				imgclass = img.attr('class');
				imgid    = parseInt(imgclass.replace(/\D/g, ''), 10);
	
				$('#upload_image_id').val(imgid);
				$('#remove-kcarousel-image').show();
	
				$('img#kcarousel_image').attr('src', imgurl);
				try{tb_remove();}catch(e){};
				$('#temp_image').remove();
				
				// restore the send_to_editor handler function
				window.send_to_editor = window.send_to_editor_default;
				
			}
	
		});
		</script>
		<?php
	}
}


//Instantiate plugin class and add it to the set of globals
$GLOBALS['kcarousel_image_upload'] = new KCarousel_Image_Upload();

require_once('kcarousel-settings.php');
require_once('kcarousel-resources-init.php');
require_once('kcarousel-shortcode.php');