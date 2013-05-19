<?php
/**
 * A sample widget implementation
 *
 * @package TODO
 * @version 1.0
 * @author TODO
 * @copyright TODO
 */

// TODO: Rename this class to a proper name for your plugin
class PluginNameWidget extends WP_Widget {

	/*----------------------------------------*
	 * Properties
	 *----------------------------------------*/
	// TODO: replace with a unique value for your plugin
	const TEXTDOMAIN = 'plugin-name';

	/*----------------------------------------*
	 * Constructor
	 *----------------------------------------*/
	public function __construct() {

		/**
		 * parent::__construct( $id_base = false, $name, $widget_options = array(), $control_options = array() );
		 * @param string $id_base Optional Base ID for the widget, lower case,
		 * if left empty a portion of the widget's class name will be used. Has to be unique.
		 * @param string $name Name for the widget displayed on the configuration page.
		 * @param array $widget_options Optional Passed to wp_register_sidebar_widget()
		 *       - description: shown on the configuration page
		 *       - classname
		 * @param array $control_options Optional Passed to wp_register_widget_control()
		 *       - width: required if more than 250px
		 *       - height: currently not used but may be needed in the future
		 * @link http://core.trac.wordpress.org/browser/trunk/wp-includes/widgets.php
		 */
		// TODO: Change class name and 'description'.
		parent::__construct(
			false,
			'PluginNameWidget',
			array(
				'description' => __( 'Short description of the widget goes here.', self::TEXTDOMAIN )
			)
		);

	} // end constructor

	/**
	 * Delete options from database.
	 *
	 * NOTE: Multiple uninstall should be done by uninstall.php becase
	 *       'register_uninstall_hook()' should be only one per plugin.
	 * @link http://core.trac.wordpress.org/ticket/12754
	 */
	public static function uninstall() {
		$object = new self;
		delete_option( $object->option_name );
		unset( $object );
	} // end uninstall

	/*----------------------------------------*
	 * Widget API Functions
	/*----------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args The array of form elements
	 * @param array $instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		extract( $args, EXTR_SKIP );

		echo $before_widget;

		// TODO: Here is where you manipulate your widget's values based on their input fields
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		$sample_text = esc_attr( $instance['sample_text'] );
		if ( $sample_text ) {
			echo "<p>$sample_text</p>";
		}

		echo $after_widget;

	} // end widget

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array $new_instance The previous instance of values before the update.
	 * @param array $old_instance The new instance of values to be generated via the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// TODO: Here is where you update your widget's old values with the new, incoming values
		$instance['title'] = esc_attr( $new_instance['title'] );
		$instance['sample_text'] = esc_attr( $new_instance['sample_text'] );

		return $instance;

	} // end update

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array $instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		// TODO: Define default values for your variables
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => 'Sample',
				'sample_text' => 'Hello World !'
			)
		);

		// TODO: Store the values of the widget in their own variable
		$title = esc_attr( $instance['title'] );
		$sample_text = esc_attr( $instance['sample_text'] );
?>
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">
		<?php _e( 'Title:', self::TEXTDOMAIN); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>
<p>
	<label for="<?php echo $this->get_field_id('sample_text'); ?>">
		<?php _e( 'Sample Text:', self::TEXTDOMAIN); ?>
	</label>
	<input class="widefat" id="<?php echo $this->get_field_id('sample_text'); ?>" name="<?php echo $this->get_field_name('sample_text'); ?>" type="text" value="<?php echo $sample_text; ?>" />
</p>
<?php
	} // end form

} // end class

// TODO: Remember to rename 'PluginNameWidget' to match the class name definition
add_action( 'widgets_init', create_function( '', 'register_widget( "PluginNameWidget" );' ) );
