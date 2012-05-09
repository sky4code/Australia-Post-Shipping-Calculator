<?php
  /*Plugin Name: Austrlia Post
    Plugin URI: http://aakashdodiya.com 
    Description: Plugin for Australia post API
    Author: Aakash Dodiya
    Version: 1.0 
    Author URI: http://aakashdodiya.com 
	Description: Its the main class of Australia post plugin, everything starts from here.
    */
class Auspost{
	
	/* defining constructure */
	function Auspost(){
		add_action('admin_menu', array( $this, 'init' ));
	}
	
	/**
	 * Takes care of loading up Auspost
	 */
	function init() {
		// Previous to initializing
		//do_action( 'auspost_pre_init' );

		// Initialize
		$this->start();
		$this->auspost_start();
		// Finished initializing
		//do_action( 'auspost_init' );
	}
		
	/**
	 * Initialize the basic Auspost constants
	 */
	function start() {
		// Set the core file path
		define( 'AUSPOST_FILE_PATH', dirname( __FILE__ ) );
		
		// Define the path to the plugin folder
		define( 'AUSPOST_DIR_NAME',  basename( AUSPOST_FILE_PATH ) );

		// Define the URL to the plugin folder
		define( 'AUSPOST_FOLDER',    dirname( plugin_basename( __FILE__ ) ) );
		define( 'AUSPOST_URL',       plugins_url( '', __FILE__ ) );
		define('AUSPOST_THEME', (AUSPOST_FILE_PATH."/theme"));
		// Finished starting
		//do_action( 'auspost_started' );
	}
	
	function auspost_start(){
		// starting to add menu item in settings
		add_action('admin_menu', array( $this, 'auspost_admin_actions' ),8);
	}
	function auspost_admin_actions() {  
		// Assigning menu item
		add_options_page('Australia Post', 'Austrlia Post', 'manage_options', 'my-unique-identifier', array( $this, 'auspost_admin' ));
	}
	
	function auspost_admin() { 
		// including admin setting file - Bug#resolved 
		require_once(AUSPOST_FILE_PATH .'/auspost_import_admin.php'); 
	}  
}

// Start Auspost
$auspost = new Auspost();
?>