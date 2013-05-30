<?php

class kcarousel_admin_menu {

    public function __construct()
	{
		if(is_admin())
		{
			add_action('admin_menu', array($this, 'kcarousel_plugin_page'));
			add_action('admin_init', array($this, 'kcarousel_init'));
		}
    }
	
    public function kcarousel_plugin_page()
	{
        // This page will be under "Settings"
		add_options_page('KCarousel Settings', 'KCarousel Settings', 'manage_options', 'kcarousel-setting-admin', array($this, 'create_kcarousel_page'));
    }

    public function create_kcarousel_page()
	{
    ?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>KCarousel Settings</h2>			
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields('kcarousel_option_group');	
				do_settings_sections('kcarousel-setting-admin');
				?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
    }
	
    public function kcarousel_init()
	{		
	  register_setting('kcarousel_option_group', 'array_key', array($this, 'check_effect'));
	  register_setting('kcarousel_option_group', 'kc-direction', array($this, 'check_direction'));
	  register_setting('kcarousel_option_group', 'kc-transiton-time', array($this, 'check_transiton_time'));
	  register_setting('kcarousel_option_group', 'kc-transiton-piece', array($this, 'check_transiton_piece'));
	  register_setting('kcarousel_option_group', 'kc-pg-transiton', array($this, 'check_manual_pagination'));
		
        add_settings_section
		(
			'kcarousel_setting_id',
			'Custom Setting',
			array($this, 'print_section_info'),
			'kcarousel-setting-admin'
		);	
		
		add_settings_field
		(
			'kcarousel-effect', 
			'Carousel Effect', 
			array($this, 'create_an_effect_field'), 
			'kcarousel-setting-admin',
			'kcarousel_setting_id'			
		);

		add_settings_field
		(
			'kcarousel-direction', 
			'Carousel Direction', 
			array($this, 'create_an_direction_field'), 
			'kcarousel-setting-admin',
			'kcarousel_setting_id'			
		);
		
		add_settings_field
		(
			'kc-transiton-time', 
			'Transition Time (In Milisecond)', 
			array($this, 'create_an_transiton_time_field'), 
			'kcarousel-setting-admin',
			'kcarousel_setting_id'			
		);
		
		add_settings_field
		(
			'kc-transiton-piece', 
			'Number of Slice Per Transiton', 
			array($this, 'create_an_transiton_piece_field'), 
			'kcarousel-setting-admin',
			'kcarousel_setting_id'			
		);
		
		add_settings_field
		(
			'kc-manual-pagination', 
			'Enable Manual Transition With Pagination', 
			array($this, 'create_an_manual_transiton_pagination_field'), 
			'kcarousel-setting-admin',
			'kcarousel_setting_id'			
		);
    }
	
    //For Custom Effect Save or Update
	public function check_effect($input)
	{
		if(isset($input['kcarousel_effect']))
		{
			$mid = $input['kcarousel_effect'];
			
			if(get_option('kcarousel_effect') === FALSE)
			{
				add_option('kcarousel_effect', $mid);
			}
			else
			{
				update_option('kcarousel_effect', $mid);
			}
		}
		else
		{
			$mid = '';
		}
		
	  return $mid;
    }
	
	//For Custom Direction Save or Update
	public function check_direction($input)
	{	
		if(isset($input['kcarousel_direction']))
		{
			$kc_dir = $input['kcarousel_direction'];
			
			if(get_option('kcarousel_direction') === FALSE)
			{
				add_option('kcarousel_direction', $kc_dir);
			}
			else
			{
				update_option('kcarousel_direction', $kc_dir);
			}
		}
		else
		{
			$kc_dir = '';
		}
		
	  return $kc_dir;
    }
	
	//For Custom Transition Time
	public function check_transiton_time($input)
	{	
		if(isset($input['kc_transiton_time']))
		{
			$kc_tr_time = $input['kc_transiton_time'];
			
			if(get_option('kcarousel_transition_time') === FALSE)
			{
				add_option('kcarousel_transition_time', $kc_tr_time);
			}
			else
			{
				update_option('kcarousel_transition_time', $kc_tr_time);
			}
		}
		else
		{
			$kc_tr_time = '';
		}
		
	  return $kc_tr_time;
    }
	
	//For Custom Transition Piece
	public function check_transiton_piece($input)
	{	
		if(isset($input['kc-transiton-piece']))
		{
			$kc_tr_piece = $input['kc-transiton-piece'];
			
			if(get_option('kc_transiton_piece') === FALSE)
			{
				add_option('kc_transiton_piece', $kc_tr_piece);
			}
			else
			{
				update_option('kc_transiton_piece', $kc_tr_piece);
			}
		}
		else
		{
			$kc_tr_piece = '';
		}
		
	  return $kc_tr_piece;
    }
	
	//For Custom Manual Pagination Transition
	public function check_manual_pagination($input)
	{	
		if(isset($input['kc_pg_transiton']))
		{
			$kc_pg_tr = $input['kc_pg_transiton'];
			
			if(get_option('kc_pg_transition') === FALSE)
			{
				add_option('kc_pg_transition', $kc_pg_tr);
			}
			else
			{
				update_option('kc_pg_transition', $kc_pg_tr);
			}
		}
		else
		{
			$kc_pg_tr = '';
		}
		
	  return $kc_pg_tr;
    }
	
	//Print Section Information In Admin Menu
    public function print_section_info()
	{
		print '<b>Custom Settings For KCarousel :</b>';
    }
	
    //Callback for Slide Effect
	public function create_an_effect_field()
	{
		$currentEffect = get_option('kcarousel_effect');
	
	?>
		<select name="array_key[kcarousel_effect]" id="kcarousel_effect">
			<option <?php if($currentEffect == 'swing') { echo "selected='selected'"; } ?>>swing</option>
			<option <?php if($currentEffect == 'linear') { echo "selected='selected'"; } ?>>linear</option>
			<option <?php if($currentEffect == 'quadratic') { echo "selected='selected'"; } ?>>quadratic</option>
			<option <?php if($currentEffect == 'cubic') { echo "selected='selected'"; } ?>>cubic</option>
			<option <?php if($currentEffect == 'elastic') { echo "selected='selected'"; } ?>>elastic</option>
		</select>
	<?php
    }
	
	//Callback for Slide Direction
	public function create_an_direction_field()
	{
		$currentDirection = get_option('kcarousel_direction');
	
	?>
		<select name="kc-direction[kcarousel_direction]" id="kcarousel_direction">
			<option <?php if($currentDirection == 'left') { echo "selected='selected'"; } ?>>left</option>
			<option <?php if($currentDirection == 'right') { echo "selected='selected'"; } ?>>right</option>
			<option <?php if($currentDirection == 'up') { echo "selected='selected'"; } ?>>up</option>
			<option <?php if($currentDirection == 'down') { echo "selected='selected'"; } ?>>down</option>
		</select>
	<?php
    }
	
	//Callback for Transition Time Field
	public function create_an_transiton_time_field()
	{
		$currentTrTime = get_option('kcarousel_transition_time');
	
	?>
		<input type="text" name="kc-transiton-time[kc_transiton_time]" id="kc-transiton-time" value="<?php if($currentTrTime != NULL) { echo $currentTrTime; } else{ echo "500";} ?>" />

	<?php
    }
	
	//Callback for Slide Piece
	public function create_an_transiton_piece_field()
	{
		$currentTrPiece = get_option('kc_transiton_piece');
	
	?>
		<input type="text" name="kc-transiton-piece[kc-transiton-piece]" id="kc-transiton-piece" value="<?php if($currentTrPiece != NULL) { echo $currentTrPiece; } else{ echo "1";} ?>" /> <i>Set how many image you want to pass out per transition.</i>
		
	<?php
    }
	
	//Callback Manual Transition With Pagination
	public function create_an_manual_transiton_pagination_field()
	{
		$currentPgTransition = get_option('kc_pg_transition');
	
	?>
		<!-- <input type="checkbox" name="kc-pg-transiton[kc_pg_transiton]" id="kc_pg_transiton" value="1"
		<?php if(isset($currentPgTransition) && $currentPgTransition == true) {echo 'checked="checked"';}?>
		/>--><i>This option is not currently unavailable. Use keyboard arrow key to navigate manually.</i>
		
	<?php
    }
}

$kcarousel_admin_menu = new kcarousel_admin_menu();