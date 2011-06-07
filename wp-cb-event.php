<?php
/*
Plugin Name: WP CB Event
Plugin URI: http://positivesum.org
Description: Allows to add event into pages
Version: 1.0

Author: Valera Satsura
Author URI: https://www.odesk.com/users/~~8995d17603281447
*/

// Init CB
if ( !class_exists( 'wp_cb_event' ) ) {
	class wp_cb_event {
		/**
		 * Initializes plugin variables and sets up wordpress hooks/actions.
		 *
		 * @return void
		 */
		function __construct( ) {
			$this->pluginDir		= basename(dirname(__FILE__));
			$this->pluginPath		= WP_PLUGIN_DIR . '/' . $this->pluginDir;
			add_action('cfct-modules-loaded',  array(&$this, 'wp_cb_event_load'));
		}

		function wp_cb_event_load() {
			require_once($this->pluginPath . "/event.php");
		}

	}
    
	$wp_cb_event = new wp_cb_event();
}