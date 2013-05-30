<?php

function my_kcarousel_init() 
{

	$assetsFolderPath = plugin_dir_url(__FILE__).'kcarousel_assets/';
	$kcarouselBasePath = plugin_dir_url(__FILE__);
	
	wp_enqueue_script('jquery');
	
	wp_enqueue_script('carouFredSel', $assetsFolderPath.'jquery.carouFredSel-6.2.1.js', array( 'jquery' ));
	wp_enqueue_script('carouFredSel-packed', $assetsFolderPath.'jquery.carouFredSel-6.2.1-packed.js', array( 'jquery' ));
	
	wp_enqueue_script('ba-throttle-debounce-script', $assetsFolderPath.'helper-plugins/jquery.ba-throttle-debounce.min.js', array( 'jquery' ));
	wp_enqueue_script('jquery-mousewheel-script', $assetsFolderPath.'helper-plugins/jquery.mousewheel.min.js', array( 'jquery' ));
	wp_enqueue_script('jquery-touchSwipe-script', $assetsFolderPath.'helper-plugins/jquery.touchSwipe.min.js', array( 'jquery' ));
	wp_enqueue_script('jquery-transit-script', $assetsFolderPath.'helper-plugins/jquery.transit.min.js', array( 'jquery' ));
	wp_enqueue_script('fancybox-script', $assetsFolderPath.'source/jquery.fancybox.js', array( 'jquery' ));
	wp_enqueue_style('fancybox-css', $assetsFolderPath.'source/jquery.fancybox.css');
	
	wp_enqueue_style('kcarousel-stylesheet-custom', $kcarouselBasePath.'css/kcarousel-style.css');
	
}

add_action('init', 'my_kcarousel_init');
