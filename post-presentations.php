<?php
/*
Plugin Name: Post Presentations
Plugin URI: http://jancbeck.com
Description: Turns your WordPress posts into reveal.js powered presentations.
Version: 0.1
Author: Jan Beck
Author Email: mail@jancbeck.com
  
*/

class PostPresentations {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'Post Presentations';
	const slug = 'post_presentations';

	/**
	 * Holds all the plugin options to avoid db queries
	 * 
	 * @var array
	 */
	private $options = array();

	/**
	 * Holds all the default plugin options 
	 * 
	 * @var array
	 */
	private $default_options = array(
	    'defaults' => array(
	    	'controls' => true,
	    	'progress' => true,
	    	'history' => false,
	    	'keyboard' => true,
	    	'touch' => true,
	    	'overview' => true,
	    	'center' => true,
	    	'loop' => false,
	    	'rtl' => false,
	    	'autoSlide' => 0,
	    	'mouseWheel' => false,
	    	'rollingLinks' => true,
	    	'transition' => 'default', 
	    	'transitionSpeed' => 'default', 
	    	'backgroundTransition' => 'default' 
	    ),
	    'looks' => array(
	    	'active_theme' => 'default'
	    )
	);
	
	/**
	 * Constructor
	 */
	function __construct() {

		// Setup localization
		load_plugin_textdomain( 'post_presentations', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_post_presentations' ) );

		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_post_presentations' ) );

		add_filter( 'query_vars', array( &$this, 'add_query_vars') );
		add_action( 'template_redirect', array( &$this, 'custom_template' ) ); 

		add_action( 'presentation_head', array( &$this, 'presentation_styles' ) );   
		add_action( 'presentation_footer', array( &$this, 'presentation_scripts' ) ); 

		// Add the options page and menu item.
		add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );

		// Register the settings page for the options of this plugin
		add_action( 'admin_init', array( &$this, 'settings_api_init'));
	}

	/**
	 * Returns all options of the plugin or specific option if parameter $key given
	 *
	 * @since 1.0.0
	 * @param string $key
	 * @var array
	 */
	function get_options( $key = false ){
		
		$options = $this->options;

		if ( $key ) {
			$options = $options[ $key ];
		}

		return $options;
		
	}

	/**
	*   Add the 'presentation' query variable so WordPress
	*   won't mangle it.
	*/
	function add_query_vars( $vars ){
	    $vars[] = "presentation";
	    return $vars;
	}
  
	/**
	 * Runs when the plugin is activated
	 */  
	function install_post_presentations() {
		add_option( 'post_presentations', $this->default_options );
	}
  
	/**
	 * Runs when the plugin is initialized
	 */
	function init_post_presentations() {

		
		// load plugin options just once per instance
		if (  empty( $this->options ) ) {

			$db_options = get_option( 'post_presentations', array() );
			$this->options = wp_parse_args( $db_options, $this->default_options );
			
		}

		add_rewrite_endpoint( 'presentation', EP_PERMALINK );
	
		if ( is_admin() ) {
			//this will run when in the WordPress admin
		} else {
			//this will run when on the frontend
		}
		
	}
	/**
	 * Register the administration menu for this plugin into the WordPress Options menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {

		add_options_page( 'Post Presentations', 'Post Presentations', 'manage_options', 'post_presentations', array( $this, 'display_plugin_admin_page' ));

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'admin/settings.php' );
	}

	/**
	 * Initializing all settings to the admin panel
	 *
	 * @since 1.0.0
	 */
	public function settings_api_init() {

		register_setting( 'post_presentations', 'post_presentations' );

	 	add_settings_section( 
	 		'post_presentations' . '_defaults',
			__( 'Default options' ),
			array( $this, 'settings_section_default_cb' ),
			'post_presentations'
		);
	 	add_settings_field( 
	 		'post_presentations' . '_defaults_controls',
			__( 'Controls' ),
			array( $this, 'settings_field_boolean' ),
			'post_presentations',
			'post_presentations' . '_defaults',
			array( 
				'section' => 'defaults',
				'key' => 'controls',
				'description' => __( 'Display controls in the bottom right corner' )
			) 
		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_progress',
 			__( 'Progress' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'progress',
 				'description' => __( 'Display a presentation progress bar' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_history',
 			__( 'History' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'history',
 				'description' => __( 'Push each slide change to the browser history' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_keyboard',
 			__( 'Keyboard' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'keyboard',
 				'description' => __( 'Enable keyboard shortcuts for navigation' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_touch',
 			__( 'Touch' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'touch',
 				'description' => __( 'Enable touch events for navigation' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_overview',
 			__( 'Overview' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'overview',
 				'description' => __( 'Enable the slide overview mode' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_center',
 			__( 'Center' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'center',
 				'description' => __( 'Vertical centering of slides' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_loop',
 			__( 'Loop' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'loop',
 				'description' => __( 'Loop the presentation' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_rtl',
 			__( 'RTL' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'rtl',
 				'description' => __( 'Change the presentation direction to be RTL' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_mouseWheel',
 			__( 'Mouse wheel' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'mouseWheel',
 				'description' => __( 'Enable slide navigation via mouse wheel' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_rollingLinks',
 			__( 'Rolling links' ),
 			array( $this, 'settings_field_boolean' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'rollingLinks',
 				'description' => __( 'Apply a 3D roll to links on hover' )
 			) 
 		);
 	 	add_settings_field( 
 	 		'post_presentations' . '_defaults_autoSlide',
 			__( 'Auto slide' ),
 			array( $this, 'settings_field_intval' ),
 			'post_presentations',
 			'post_presentations' . '_defaults',
 			array( 
 				'section' => 'defaults',
 				'key' => 'autoSlide',
 				'description' => __( 'Number of milliseconds between automatically proceeding to the next slide. Disabled when set to 0. This value can be overwritten by using a data-autoslide attribute on your slides' )
 			) 
 		);
	 	add_settings_field( 
	 		'post_presentations' . '_defaults_transition',
			__( 'Transition style' ),
			array( $this, 'settings_field_select' ),
			'post_presentations',
			'post_presentations' . '_defaults',
			array( 
				'section' => 'defaults',
				'key' => 'transition',
				'description' => '',
				'options' => array( 'default', 'cube', 'page', 'concave', 'zoom', 'linear', 'fade', 'none' )
			) 
		);
	 	add_settings_field( 
	 		'post_presentations' . '_defaults_transitionSpeed',
			__( 'Transition speed' ),
			array( $this, 'settings_field_select' ),
			'post_presentations',
			'post_presentations' . '_defaults',
			array( 
				'section' => 'defaults',
				'key' => 'transitionSpeed',
				'description' => '',
				'options' => array( 'default', 'fast', 'slow' )
			) 
		);
	 	add_settings_field( 
	 		'post_presentations' . '_defaults_backgroundTransition',
			__( 'Background transition' ),
			array( $this, 'settings_field_select' ),
			'post_presentations',
			'post_presentations' . '_defaults',
			array( 
				'section' => 'defaults',
				'key' => 'backgroundTransition',
				'description' => '',
				'options' => array( 'default', 'linear' )
			) 
		);

	 	add_settings_section( 
	 		'post_presentations' . '_looks',
			__( 'Default options' ),
			array( $this, 'settings_section_looks_cb' ),
			'post_presentations'
		);

	 	add_settings_field( 
	 		'post_presentations' . '_looks_theme',
			__( 'Active theme' ),
			array( $this, 'settings_field_select' ),
			'post_presentations',
			'post_presentations' . '_looks',
			array( 
				'section' => 'looks',
				'key' => 'theme',
				'description' => '',
				'options' => array(
					'beige',
					'default',
					'moon',
					'night',
					'serif',
					'simple',
					'sky',
					'solarized'
				)
			) 
		);
	}

	/**
	 * The intro text for the skin settings section of the admin panel.
	 *
	 * @since 1.0.0
	 */
	
	function settings_section_default_cb() {
		echo '<p>'. __( 'These settings let you choose the default behavior of the presentations'). '</p>';
	}
	function settings_section_looks_cb() {
		echo '<p>'. __( 'These settings let you choose the looks of the presentations'). '</p>';
	}

	function settings_field_boolean( $args ) {
		
		extract( $args );
		$slug = 'post_presentations';
		$name = "{$slug}[{$section}][{$key}]";
		$section = $this->get_options($section);
		$value = $section[$key] == 1 ? true : false;
		?>
		<input type="hidden" value="false" name="<?php echo $name ?>">
		<label><input name="<?php echo $name ?>" type="checkbox" value="<?php echo $value; ?>" <?php echo checked( $value, true, false); ?> /><span> <?php echo $description ?></span></label>
	<?php }

	function settings_field_intval( $args ) { 
		extract( $args );
		$slug = 'post_presentations';
		$name = "{$slug}[{$section}][{$key}]";
		$section = $this->get_options( $section );
		$value = $section[$key];
		
		?>
		<label><input name="<?php echo $name ?>" type="number" min="0" class="small-text" value="<?php echo $value; ?>" /> <span><?php echo $description ?></span></label>
	<?php }

	function settings_field_select( $args ) {
		extract( $args );
		$slug = 'post_presentations';
		$name = "{$slug}[{$section}][{$key}]";
		$section = $this->get_options( $section );
		$value = $section[$key];
		$options = $args['options'];
		
		$html_option = '<option value="%s"%s>%s</option>';

		$html = '';
		$html .= "<select name='$name'>";

		foreach ($options as $option)
			$html .= sprintf( $html_option, $option, selected( $value, $option, false ), $option);

		$html .= '</select>';

		echo $html;
	}

	/**
	 * Conditional function. Returns true when current post is displayed as a presentation.
	 */
	function has_slides() {

		global $post;

		if ( false !== strpos( $post->post_content, '<!--nextslide-->' )) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Conditional function. Returns true when current post contains a '<!--nextslide-->' keyword string.
	 */
	function is_presentation( ) {

		global $wp_query;

		if ( isset( $wp_query->query['presentation'] ) && $this->has_slides() ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Loads custom template for reveal.js presentations
	 */
	function custom_template( ) {

		if ( $this->is_presentation() ) {
			include( plugin_dir_path( __FILE__ ) . 'presentation-template.php' );
			exit();
		} 
	}
	/**
	 * Returns an array containing all slides of the current post
	 */
	function get_slides( ) {

		global $post;

		$slides = array();

		if ( $this->is_presentation() ) {
			$slides = explode( '<!--nextslide-->', $post->post_content );
		} 

		return apply_filters( 'presentation_slides', $slides );
	}
	/**
	 * Renders the stylesheets into the custom template head. Hooked to 'presentation_head' action.
	 */
	function presentation_styles() {

		$path = plugin_dir_url( __FILE__ );
		$options = $this->get_options( 'looks' );

		$html = '';

		$html .= '<link rel="stylesheet" href="'. plugins_url( 'reveal.js/css/reveal.min.css' , __FILE__) .'" />';
		$html .= '<link rel="stylesheet" href="'. plugins_url( 'reveal.js/css/theme/'. $options['theme'] .'.css' , __FILE__) .'" id="theme" />';
		$html .= '<style>.reveal section img { height: auto; }</style>'; // maintain aspect ratio of images

		echo $html;

	}
	/**
	 * Renders the stylesheets into the custom template head. Hooked to 'presentation_footer' action.
	 */
	function presentation_scripts() {

		$html = '';

		$html .= '<script src="'. plugins_url( 'reveal.js/lib/js/head.min.js' , __FILE__) .'"></script>';
		$html .= '<script src="'. plugins_url( 'reveal.js/js/reveal.min.js' , __FILE__) .'"></script>';

		$options = $this->options;
		$options['defaults']['autoSlide'] = intval( $options['defaults']['autoSlide'] ); // lazy sanitization

		$options = apply_filters( 'presentation_options', json_encode( $options  ) );

		$html .= "<script>
			Reveal.initialize( $options );
		</script>";

		echo $html;
	}
  
} // end class
new PostPresentations();

?>