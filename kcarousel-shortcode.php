<?php

/*********************************
* Shortcode For KCarousel
*********************************/
add_shortcode('k-carousel', 'kcarousel');

function kcarousel()
{
	
?>
	<div class="image_carousel">
		<div id="kcarousel">
		
		<?php 
			
			 $posts=get_posts('post_type=kcarousel-img');
			 if ($posts) {
			 foreach($posts as $post) 
			 {
			 
				$image_id = get_post_meta( $post->ID, '_image_id', true );
				$image_src = wp_get_attachment_url( $image_id );
				$image_src_thumb = wp_get_attachment_thumb_url( $image_id );
				
		?>
			<a rel="fancybox" class="fancybox" href="<?php echo $image_src; ?>">
				<img src="<?php echo $image_src_thumb; ?>" alt="<?php the_title(); ?>"/>
			</a>
			
			<?php 
			 } 
		
			 }
			 else
			 {
				echo "There is no image in KCarousel. Please set image first.";
			 }
	
			?>
			
		</div>
		<div class="clearfix"></div>
	</div>

<?php
}

function my_kcarousel_header() {

	//Get Custom Option From Database Settings and Place them in bellow JS part
	$userDefinedEffect = get_option('kcarousel_effect');
	$userDefinedDirection = get_option('kcarousel_direction');
	$userDefinedTrTime = get_option('kcarousel_transition_time');
	$userDefinedTrPiece = get_option('kc_transiton_piece');
	
	$pg_transition = get_option('kc_pg_transition');
	if(isset($pg_transition) && $pg_transition == true){ 
		$pg_transition == TRUE;
	}
	else{
		$pg_transition == FALSE;
	}
	
	//Effect
	if(isset($userDefinedEffect) && $userDefinedEffect!= NULL){
		$kcarousel_effect = $userDefinedEffect;
	}
	else{
		$kcarousel_effect = "swing";
	}
	
	//Direction
	if(isset($userDefinedDirection) && $userDefinedDirection!= NULL){
		$kcarousel_direction = $userDefinedDirection;
	}
	else{
		$kcarousel_direction = "left";
	}
	
	//Transition Time
	if(isset($userDefinedTrTime) && $userDefinedTrTime!= NULL){
		$kcarousel_tr_time = $userDefinedTrTime;
	}
	else{
		$kcarousel_tr_time = "500";
	}
	
	//Transiton Item Piece
	if(isset($userDefinedTrPiece) && $userDefinedTrPiece!= NULL){
		$kcarousel_tr_piece = $userDefinedTrPiece;
	}
	else{
		$kcarousel_tr_piece = "1";
	}

?>

	<script>
		
		jQuery(function($) {
		
			var kcarousel_effect = '<?php echo $kcarousel_effect; ?>';
			var kcarousel_direction = '<?php echo $kcarousel_direction; ?>';
			var kcarousel_tr_time = '<?php echo $kcarousel_tr_time; ?>';
			var kcarousel_tr_items = '<?php echo $kcarousel_tr_piece; ?>';
			var manual_pg_tr = '<?php echo $pg_transition; ?>';

			$("#kcarousel").carouFredSel({
			
			
				auto : true,
				//pagination  : "#kcarousel_pag",
				
					direction: kcarousel_direction,
					prev : "left",
					next : "right",
					
					scroll : {
						items			: parseInt(kcarousel_tr_items),
						easing			: kcarousel_effect,
						duration		: parseInt(kcarousel_tr_time),							
						pauseOnHover	: true,
					}	
							
			});	
			
			$("#kcarousel a").fancybox({
				onStart	: function() {
					$("#kcarousel").trigger("stop", true);;
				},
				onClosed : function() {
					$("#kcarousel").trigger("play", true);
				}
			});

		});
		
	</script>
	
<?php
}

add_action('wp_head', 'my_kcarousel_header');