<?php
/**
 * @package Auspost
 * @version 1 
 * @ author: Aakash Dodiya
 *   This page will display option  for administrator to make shipping available to Australia
      only or international as well. Most of the time Australian shoppers do not want to sell their
	  products to international level. So once we define the Shipping type it would be much easier later
	  to calculate shipping price based on available methods of shipping for whatsoever shipping type.
 */
/*
*/

//Defining class
class ShippingOptions extends Auspost{
   
	function ShippingOptions(){
		wp_register_style('auspost.css', AUSPOST_URL . '/theme/auspost.css');
		wp_enqueue_style('auspost.css');
		//wp_register_script('akismet.js', AKISMET_PLUGIN_URL . 'akismet.js', array('jquery'));
		//wp_enqueue_script('akismet.js');
		// loading previously set option from database // WE DO NOT HAVE DATABASE TABLE CREATION CODE AT THIS MOMENT //
		$this->load_options();
		
		// this function will show option in html
		$this->show_options();
		 
	}
	
	function load_options(){ // we do not need database set up at this moment just test it using default data 
	}
	
	// from this function we will show HTML content in page
	function show_options(){
		// Showing HTML content
		require_once(AUSPOST_THEME .'/show_options.php');
	}
	
	
}

$shipOption = new ShippingOptions();
?>