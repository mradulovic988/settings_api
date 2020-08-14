<?php
/**
 * The settings of the plugin.
 *
 * @link       https://mlab-studio.com
 * @since      1.0.0
 *
 * @package    Dmc
 * @subpackage Dmc/admin
 */

class Settings_Api {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since      1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// add_action('admin_init', [ $this, 'add_admin_pages' ] );
		// add_action( 'admin_init', [ $this, 'dashboard_messaging_center' ] );
		add_action( 'admin_init', [ $this, 'dbi_register_settings' ] );
		add_action( 'admin_init', [ $this, 'dbi_register_settings_two' ] );

	}

	/**
	 * Declaring all of the admin and submenu pages
	 */
	public function add_admin_pages() {
		add_menu_page( 
			'Example plugin page', 
			'Example Plugin Menu', 
			'manage_options', 
			'dbi-example-plugin', 
			[ $this, 'dbi_render_plugin_settings_page' ] 
		);

		add_submenu_page(
			'dbi-example-plugin',
			__( 'Messaging Centre', 'dmc' ),
			__( 'Messaging Centre', 'dmc' ),
			'manage_options',
			'dbi-example-plugin',
			[
				$this, 'user_list_page'
			]
		);

		add_submenu_page(
			'dbi-example-plugin',
			__( 'Control Centre', 'dmc' ),
			__( 'Control Centre', 'dmc' ),
			'manage_options',
			'dmc_control_centre',
			[
				$this, 'control_centre'
			]
		);
		
	}
	
	public function user_list_page(){}

	/**
	 * First tab function
	 */
	public function dbi_render_plugin_settings_page() {
		?>
		<div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h1>Theme Options</h1>
           

            <?php
                //we check if the page is visited by click on the tabs or on the menu button.
                //then we get the active tab.
                $active_tab = "header-options";
                if(isset($_GET["tab"]))
                {
                    if($_GET["tab"] == "header-options")
                    {
                        $active_tab = "header-options";
                    }
                    else
                    {
                        $active_tab = "ads-options";
                    }
                }
            ?>
           
            <!-- wordpress provides the styling for tabs. -->
            <h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                <a href="?page=dbi-example-plugin" class="nav-tab <?php if($active_tab == 'header-options'){echo 'nav-tab-active';} ?> "><?php _e('Header Options', 'sandbox'); ?></a>
                <a href="?page=dmc_control_centre" class="nav-tab <?php if($active_tab == 'ads-options'){echo 'nav-tab-active';} ?>"><?php _e('Advertising Options', 'sandbox'); ?></a>
            </h2>

			<h2>Example Plugin Settings</h2>
			<form action="options.php" method="post">
				<?php 
				settings_fields( 'dbi_example_plugin_options' );
				do_settings_sections( 'dbi_example_plugin' );

				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Second tab function
	 */
	public function control_centre()
	{
		?>
		<div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h1>Control Centre</h1>

			<?php
                //we check if the page is visited by click on the tabs or on the menu button.
                //then we get the active tab.
                $active_tab = "ads-options";
                if(isset($_GET["tab"]))
                {
                    if($_GET["tab"] == "ads-options")
                    {
                        $active_tab = "ads-options";
                    }
                    else
                    {
                        $active_tab = "header-options";
                    }
                }
            ?>

			<h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                <a href="?page=dbi-example-plugin" class="nav-tab <?php if($active_tab == 'header-options'){echo 'nav-tab-active';} ?> "><?php _e('Header Options', 'sandbox'); ?></a>
                <a href="?page=dmc_control_centre" class="nav-tab <?php if($active_tab == 'ads-options'){echo 'nav-tab-active';} ?>"><?php _e('Advertising Options', 'sandbox'); ?></a>
            </h2>

			<form action="options.php" method="post">
				<?php 
				settings_fields( 'dbi_example_plugin_options_two' );
				do_settings_sections( 'dbi_example_plugin_two' );

				submit_button();
				?>
			</form>

		</div>
		<?php
	}

	/**
	 * Register second setting
	 */
	public function dbi_register_settings_two()
	{
		// Setting register
		register_setting( 'dbi_example_plugin_options_two', 'dbi_example_plugin_options_two', 'dbi_example_plugin_options_validate_two' );

		// Setting section
		add_settings_section( 'api_settings_two', 'API Settings_two', [ $this, 'dbi_plugin_section_text_two' ], 'dbi_example_plugin_two' );

		add_settings_field( 'dbi_plugin_setting_api_key_two', 'API Key', [ $this, 'dbi_plugin_setting_api_key_two' ], 'dbi_example_plugin_two', 'api_settings_two' );
	}

	/**
	 * Register seccond description
	 */
	public function dbi_plugin_section_text_two() {
		echo '<p>Here you can set all the options two for using the API</p>';
	}

	/**
	 * Register second option
	 */
	public function dbi_plugin_setting_api_key_two()
	{
		$options = get_option( 'dbi_example_plugin_options_two' );

		echo '<input id="dbi_plugin_setting_api_key_two" name="dbi_example_plugin_options_two[api_key_two]" type="text" placeholder="123456789" value="' . esc_attr( $options['api_key_two'] ) . '">';
	}

	/**
	 * Register first setting
	 */
	public function dbi_register_settings() {

		// Setting register
		register_setting( 'dbi_example_plugin_options', 'dbi_example_plugin_options', 'dbi_example_plugin_options_validate' );

		// Setting section
		add_settings_section( 'api_settings', 'API Settings', [ $this, 'dbi_plugin_section_text' ], 'dbi_example_plugin' );
	
		// Input fields
		add_settings_field( 'dbi_plugin_setting_api_key', 'API Key', [ $this, 'dbi_plugin_setting_api_key' ], 'dbi_example_plugin', 'api_settings' );
		add_settings_field( 'dbi_plugin_setting_results_limit', 'Results Limit', [ $this, 'dbi_plugin_setting_results_limit' ], 'dbi_example_plugin', 'api_settings' );
		add_settings_field( 'dbi_plugin_setting_start_date', 'Start Date', [ $this, 'dbi_plugin_setting_start_date' ], 'dbi_example_plugin', 'api_settings' );
		add_settings_field( 'dbi_plugin_setting_test', 'End Date', [ $this, 'dbi_plugin_setting_test' ], 'dbi_example_plugin', 'api_settings' );
		
		// Checkbox fields
		add_settings_field( 'dbi_plugin_setting_checkbox', 'Demo Checkbox', [ $this, 'dbi_plugin_setting_checkbox' ], 'dbi_example_plugin', 'api_settings' );

		// Dropdown fields
		add_settings_field('dbi_plugin_setting_select', __( 'Our Field 1 Title', 'dmc' ), [ $this, 'stp_api_select_field_1_render' ], 'dbi_example_plugin', 'api_settings' );
	}

	/**
	 * Validation input fields
	 */
	public function dbi_example_plugin_options_validate( $input ) {
		$newinput['api_key'] = trim( $input['api_key'] );
		if ( ! preg_match( '/^[a-z0-9]{32}$/i', $newinput['api_key'] ) ) {
			$newinput['api_key'] = '';
		}
	
		return $newinput;
	}

	/**
	 * Register first description
	 */
	public function dbi_plugin_section_text() {
		echo '<p>Here you can set all the options for using the API</p>';
	}

	/**
	 * Register first option
	 */
	public function dbi_plugin_setting_api_key()
	{
		$options = get_option( 'dbi_example_plugin_options' );

		echo '<input id="dbi_plugin_setting_api_key" name="dbi_example_plugin_options[api_key]" type="text" placeholder="123456789" value="' . esc_attr( $options['api_key'] ) . '">';
	}

	public function dbi_plugin_setting_results_limit()
	{
		$options = get_option( 'dbi_example_plugin_options' );
		echo '<input id="dbi_plugin_setting_results_limit" name="dbi_example_plugin_options[results_limit]" type="text" placeholder="30 days" value="' . esc_attr( $options['results_limit'] ) . '">';
	}

	public function dbi_plugin_setting_start_date()
	{
		$options = get_option( 'dbi_example_plugin_options' );
		echo '<input id="dbi_plugin_setting_start_date" name="dbi_example_plugin_options[start_date]" type="text" placeholder="08-14-2020" value="' . esc_attr( $options['start_date'] ) . '">';
	}

	public function dbi_plugin_setting_test()
	{
		$options = get_option( 'dbi_example_plugin_options' );
		echo '<input id="dbi_plugin_setting_test" name="dbi_example_plugin_options[test]" type="text" placeholder="08-14-2020" value="' . esc_attr( $options['test'] ) . '">';

	}

	public function dbi_plugin_setting_checkbox()
	{
		$options = get_option( 'dbi_example_plugin_options' );
		
		echo '<input type="checkbox" id="checkbox_example" name="dbi_example_plugin_options[checkbox]" value="1"' . checked( 1, $options['checkbox'], false ) . '/>';
		echo '<label for="checkbox_example">This is an example of a checkbox</label>';
	}

	public function stp_api_select_field_1_render() {
		$options = get_option( 'dbi_example_plugin_options' );
		?>
		<select name='dbi_example_plugin_options[dbi_plugin_setting_select]'>
			<option value='1' <?php selected( $options['dbi_plugin_setting_select'], 1 ); ?>>Option 1</option>
			<option value='2' <?php selected( $options['dbi_plugin_setting_select'], 2 ); ?>>Option 2</option>
			<option value='3' <?php selected( $options['dbi_plugin_setting_select'], 3 ); ?>>Option 3</option>
			<option value='4' <?php selected( $options['dbi_plugin_setting_select'], 4 ); ?>>Option 4</option>
			<option value='5' <?php selected( $options['dbi_plugin_setting_select'], 5 ); ?>>Option 5</option>
			<option value='6' <?php selected( $options['dbi_plugin_setting_select'], 6 ); ?>>Option 6</option>
			<option value='7' <?php selected( $options['dbi_plugin_setting_select'], 7 ); ?>>Option 7</option>
		</select>
	
	<?php
	}
}