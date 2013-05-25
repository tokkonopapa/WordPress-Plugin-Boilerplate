<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   PluginName
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin admin class.
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package PluginName
 * @author  Your Name <email@example.com>
 */
class PluginNameAdmin extends PluginName {

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Option name and group.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private static $option_name = array();
	private static $option_slug = array();

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	public function __construct( $file ) {
		parent::__construct( $file );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'add_plugin_admin_page' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Set menu properties
		foreach ( self::$option_table as $key => $value ) {
			self::$option_name[] = $key;
			self::$option_slug[] = $key;
		}

		// Add plugin meta links
		add_filter( 'plugin_action_links_' . $this->plugin_base, array( $this, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_meta_links' ), 10, 2 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance( $file ) {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance )
			self::$instance = new self( $file );

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles( $hook ) {
		if ( ! isset( $this->plugin_screen_hook_suffix ) )
			return;

		$screen = get_current_screen();
		if ( $screen->id === $this->plugin_screen_hook_suffix )
			wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'css/admin.css', $this->plugin_file ), array(), $this->version );
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts( $hook ) {
		if ( ! isset( $this->plugin_screen_hook_suffix ) )
			return;

		$screen = get_current_screen();
		if ( $screen->id === $this->plugin_screen_hook_suffix )
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', $this->plugin_file ), array( 'jquery' ), $this->version );
	}

	/**
	 * Add plugin links
	 *
	 * @since    1.0.0
	 */
	public function plugin_action_links( $links ) {
		return array_merge(
			array( sprintf( '<a href="options-general.php?page=%s">%s</a>', $this->plugin_slug, __('Settings') ) ),
			$links
		);
	}

	/**
	 * Add plugin meta links
	 *
	 * @since    1.0.0
	 */
	public function plugin_meta_links( $links, $file ) {
		if ( $file === $this->plugin_base ) {
			array_push(
				$links,
				'<a href="https://exapmple.com/">Example.com</a>'
			);
		}
		return $links;
	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * TODO:
		 *
		 * Change 'Page Title' to the title of your plugin admin page
		 * Change 'Menu Text' to the text for menu item for the plugin settings page
		 * Change 'plugin-name' to the name of your plugin
		 */
		/*$this->plugin_screen_hook_suffix = add_plugins_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);*/
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page( $active_tab = 0 ) {
		$active_tab = isset( $_GET['tab'] ) ? intval( $_GET['tab'] ) : 0;
		$active_tab = min( count( self::$option_name ) - 1, max( 0, $active_tab ) );
?>
<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<!-- TODO: Provide markup for your options page here. -->
	<h2 class="nav-tab-wrapper">
		<a href="?page=<?php echo $this->plugin_slug; ?>&amp;tab=0" class="nav-tab <?php echo $active_tab == 0 ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings 1', $this->plugin_slug ); ?></a>
		<a href="?page=<?php echo $this->plugin_slug; ?>&amp;tab=1" class="nav-tab <?php echo $active_tab == 1 ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings 2', $this->plugin_slug ); ?></a>
	</h2>
	<form method="post" action="options.php">
<?php
		settings_fields( self::$option_slug[ $active_tab ] );
		do_settings_sections( self::$option_slug[ $active_tab ] );
		submit_button();
?>
	</form>
	<p><?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. <?php echo memory_get_usage(); ?> bytes.</p>

</div>
<?php
	}

	/**
	 * Register the setting fields for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_page() {
		/*========================================*
		 * Settings 1
		 *========================================*/

		/**
		 * Register a setting and its sanitization callback.
		 * @link http://codex.wordpress.org/Function_Reference/register_setting
		 *
		 * register_setting( $option_group, $option_name, $sanitize_callback );
		 * @param string $option_group A settings group name.
		 * @param string $option_name The name of an option to sanitize and save.
		 * @param string $sanitize_callback A callback function that sanitizes the option's value.
		 */
		register_setting(
			self::$option_slug[0],
			self::$option_name[0],
			array( $this, 'sanitize_settings1' )
		);

		/*----------------------------------------*
		 * Options
		 *----------------------------------------*/
		$options = get_option( self::$option_name[0] );
		$fields = is_array( $options ) ? array_keys( $options ) : array();

		/**
		 * Add new section to a new page inside the existing page.
		 * @link http://codex.wordpress.org/Function_Reference/add_settings_section
		 *
		 * add_settings_section( $id, $title, $callback, $page );
		 * @param string $id String for use in the 'id' attribute of tags.
		 * @param string $title Title of the section.
		 * @param string $callback Function that fills the section with the desired content.
		 * @param string $page The menu page on which to display this section.
		 */
		/*----------------------------------------*
		 * Section 1
		 *----------------------------------------*/
		$section = $this->plugin_slug . 'section1';
		add_settings_section(
			$section,
			__( 'Section 1', $this->plugin_slug ),
			array( $this, 'callback_section1' ),
			self::$option_slug[0]
		);

		/**
		 * Register a settings field to the settings page and section.
		 * @link http://codex.wordpress.org/Function_Reference/add_settings_field
		 *
		 * add_settings_field( $id, $title, $callback, $page, $section, $args );
		 * @param string $id String for use in the 'id' attribute of tags.
		 * @param string $title Title of the field.
		 * @param string $callback Function responsible for rendering the option interface.
		 * @param string $page The menu page on which to display this field.
		 * @param string $section The section of the settings page in which to show the box.
		 * @param array $args Additional arguments that are passed to the $callback function.
		 */
		add_settings_field(
			self::$option_name[0] . "_$fields[0]",
			__( 'Field 1', $this->plugin_slug ),
			array( $this, 'callback_field' ),
			self::$option_slug[0],
			$section,
			array(
				'type' => 'checkbox',
				'option' => self::$option_name[0],
				'field' => $fields[0],
				'value' => $options[ $fields[0] ]
			)
		);

		add_settings_field(
			self::$option_name[0] . "_$fields[1]",
			__( 'Field 2', $this->plugin_slug ),
			array( $this, 'callback_field' ),
			self::$option_slug[0],
			$section,
			array(
				'type' => 'text',
				'option' => self::$option_name[0],
				'field' => $fields[1],
				'value' => $options[ $fields[1] ]
			)
		);

		/*----------------------------------------*
		 * Section 2
		 *----------------------------------------*/
		$section = $this->plugin_slug . '-section2';
		add_settings_section(
			$section,
			__( 'Section 2', $this->plugin_slug ),
			array( $this, 'callback_section2' ),
			self::$option_slug[0]
		);

		add_settings_field(
			self::$option_name[0] . "_$fields[2]",
			__( 'Field 3', $this->plugin_slug ),
			array( $this, 'callback_field' ),
			self::$option_slug[0],
			$section,
			array(
				'type' => 'checkbox',
				'option' => self::$option_name[0],
				'field' => $fields[2],
				'value' => $options[ $fields[2] ]
			)
		);

		add_settings_field(
			self::$option_name[0] . "_$fields[3]",
			__( 'Field 4', $this->plugin_slug ),
			array( $this, 'callback_field' ),
			self::$option_slug[0],
			$section,
			array(
				'type' => 'text',
				'option' => self::$option_name[0],
				'field' => $fields[3],
				'value' => $options[ $fields[3] ]
			)
		);

		/*========================================*
		 * Settings 2
		 *========================================*/
		register_setting(
			self::$option_slug[1],
			self::$option_name[1],
			array( $this, 'sanitize_settings2' )
		);

		/*----------------------------------------*
		 * Options
		 *----------------------------------------*/
		$options = get_option( self::$option_name[1] );
		$fields = is_array( $options ) ? array_keys( $options ) : array();

		/*----------------------------------------*
		 * Section 3
		 *----------------------------------------*/
		$section = $this->plugin_slug . '-section3';
		add_settings_section(
			$section,
			__( 'Section 3', $this->plugin_slug ),
			array( $this, 'callback_section3' ),
			self::$option_slug[1]
		);

		add_settings_field(
			self::$option_name[1] . "_$fields[0]",
			__( 'Field 5', $this->plugin_slug ),
			array( $this, 'callback_field' ),
			self::$option_slug[1],
			$section,
			array(
				'type' => 'checkbox',
				'option' => self::$option_name[1],
				'field' => $fields[0],
				'value' => $options[ $fields[0] ]
			)
		);

		add_settings_field(
			self::$option_name[1] . "_$fields[1]",
			__( 'Field 6', $this->plugin_slug ),
			array( $this, 'callback_field' ),
			self::$option_slug[1],
			$section,
			array(
				'type' => 'text',
				'option' => self::$option_name[1],
				'field' => $fields[1],
				'value' => $options[ $fields[1] ]
			)
		);
	}

	/**
	 * Function that fills the section with the desired content.
	 * The function should echo its output.
	 */
	// TODO: 
	public function callback_section1() {
		echo "<p>" . __( 'This is a explanation for section 1.', $this->plugin_slug ) . "</p>";
	}

	public function callback_section2() {
		echo "<p>" . __( 'This is a explanation for section 2.', $this->plugin_slug ) . "</p>";
	}

	public function callback_section3() {
		echo "<p>" . __( 'This is a explanation for section 3.', $this->plugin_slug ) . "</p>";
	}

	/**
	 * Function that fills the field with the desired inputs as part of the larger form.
	 * The 'id' and 'name' should match the $id given in the add_settings_field().
	 * @param array $args A value to be given into the field.
	 * @link http://codex.wordpress.org/Function_Reference/checked
	 */
	// TODO: 
	public function callback_field( $args ) {
		if ( ! empty( $args['before'] ) )
			echo $args['before'], "\n";

		$id   = "${args['option']}_${args['field']}";
		$name = "${args['option']}[${args['field']}]";

		switch ( $args['type'] ) {
			case 'checkbox':
?>
<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="1"<?php checked( esc_attr( $args['value'] ) ); ?> />
<label for="<?php echo $id; ?>"><?php _e( 'Enable', $this->plugin_slug ); ?></label>
<?php
				break;
			case 'text':
?>
<input type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo esc_attr( $args['value'] ); ?>" />
<?php
				break;
		}

		if ( ! empty( $args['after'] ) )
			echo $args['after'], "\n";
	}

	/**
	 * A callback function that validates the option's value.
	 *
	 * @param string $option_name The name of option table.
	 * @param array $input The values to be validated.
	 *
	 * @link http://codex.wordpress.org/Data_Validation
	 * @link http://codex.wordpress.org/Function_Reference/sanitize_option
	 * @link http://codex.wordpress.org/Function_Reference/sanitize_text_field
	 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/sanitize_option_$option
	 * @link http://core.trac.wordpress.org/browser/tags/3.5/wp-includes/formatting.php
	 */
	private function sanitize_options( $option_name, $input ) {
		$message = __( 'successfully updated: ', $this->plugin_slug );
		$status = 'updated';

		/**
		 * Sanitize a string from user input or from the db
		 *
		 * check for invalid UTF-8,
		 * Convert single < characters to entity,
		 * strip all tags,
		 * remove line breaks, tabs and extra white space,
		 * strip octets.
		 *
		 * @since 2.9.0
		 * @example sanitize_text_field( $str );
		 * @param string $str
		 * @return string
		 */
		// TODO Apply a proper validation for each field.
		$output = get_option( $option_name );
		foreach ( $output as $key => $value ) {
			if ( ! isset( $input[ $key ] ) )
				$value = false; // for checkbox

			switch( $key ) {
				default: // text or checkbox
					$output[ $key ] = isset( $input[ $key ] ) ?
						sanitize_text_field( trim( $input[ $key ] ) ) : $value;
					break;
			}
		}

		// This call is just for debug.
		// @param string $setting: Slug title of the setting to which this error applies.
		// @param string $code: Slug-name to identify the error.
		// @param string $message: The formatted message text to display to the user.
		// @param string $type: The type of message it is. 'error' or 'updated'.
		// @link: http://codex.wordpress.org/Function_Reference/add_settings_error
		add_settings_error(
			$this->plugin_slug,
			'sanitize_' . $option_name,
			$message . print_r( $output, true ),
			$status
		);

		return $output;
	}

	/**
	 * Sanitize options.
	 *
	 * @since    1.0.0
	 */
	public function sanitize_settings1( $input = array() ) {
		return $this->sanitize_options( self::$option_name[0], $input );
	}

	public function sanitize_settings2( $input = array() ) {
		return $this->sanitize_options( self::$option_name[1], $input );
	}
}
