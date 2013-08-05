<?php
/*
Plugin Name: Post Presentations
Plugin URI: http://jancbeck.com
Description: Turns your WordPress posts into reveal.js powered presentations.
Version: 0.1
Author: Jan Beck
Author Email: mail@jancbeck.com
License: MIT
  
*/

class PostPresentations {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'Post Presentations';
	const slug = 'post_presentations';
	
	/**
	 * Constructor
	 */
	function __construct() {
		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_post_presentations' ) );

		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_post_presentations' ) );
	}
  
	/**
	 * Runs when the plugin is activated
	 */  
	function install_post_presentations() {
		// do not generate any output here
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_post_presentations() {
		// Setup localization
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

		// Register the shortcode [presentation]
		add_shortcode( 'presentation', array( &$this, 'render_shortcode' ) );
	
		if ( is_admin() ) {
			//this will run when in the WordPress admin
		} else {
			//this will run when on the frontend
		}

		add_filter( 'template_include', array( &$this, 'custom_template' ) );   
	}

	function custom_template() {
		$template = plugin_dir_path( __FILE__ ) . 'templates/reveal-js.php';
		return $template;
	}

	function render_shortcode($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'attr1' => 'foo', //foo is a default value
			'attr2' => 'bar'
			), $atts));
		// you can now access the attribute values using $attr1 and $attr2
	}
  
	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	private function register_scripts_and_styles() {
		if ( is_admin() ) {
			$this->load_file( self::slug . '-admin-script', '/js/admin.js', true );
			$this->load_file( self::slug . '-admin-style', '/css/admin.css' );
		} else {
			$this->load_file( self::slug . '-script', '/js/widget.js', true );
			$this->load_file( self::slug . '-style', '/css/widget.css' );
		} // end if/else
	} // end register_scripts_and_styles
	
	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file( $name, $file_path, $is_script = false ) {

		$url = plugins_url($file_path, __FILE__);
		$file = plugin_dir_path(__FILE__) . $file_path;

		if( file_exists( $file ) ) {
			if( $is_script ) {
				wp_register_script( $name, $url, array('jquery') ); //depends on jquery
				wp_enqueue_script( $name );
			} else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} // end if
		} // end if

	} // end load_file
  
} // end class
new PostPresentations();

?>