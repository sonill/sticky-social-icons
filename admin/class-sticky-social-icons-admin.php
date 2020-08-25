<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.sanil.com.np
 * @since      1.0.0
 *
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/admin
 * @author     Sanil Shakya <sanilshakya@gmail.com>
 */
class Sticky_Social_Icons_Admin {

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
	 * Default settings_page_slug
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	 private $settings_page_slug = 'sticky-social-icons';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	// enqueue styles
	public function enqueue_styles() {

		// load styles in plugin page only
		if( isset($_GET['page']) && $_GET['page'] == $this->settings_page_slug ){

			wp_enqueue_style( 'wp-color-picker' );

			// admin css
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/sticky-social-icons-admin.css', array(), time(), 'all' );

			// fontawesome5
			wp_enqueue_style( 'font-awesome', plugin_dir_url( __FILE__ ) . 'assets/fontawesome-5.14.0/css/all.min.css', array() );

		}

	}


	// enqueue scripts
	public function enqueue_scripts() {

		// load scripts in plugin plage only
		if( isset($_GET['page']) && $_GET['page'] == $this->settings_page_slug ){


			if( version_compare( get_bloginfo('version'),'3.5', '>=' ) ){
				$color_picker_is_available = 1;
			}

			wp_enqueue_script( 'fontawesome_icon_names', plugin_dir_url( __FILE__ ) . 'assets/build/js/fontawesome_icons.js', array('jquery'), $this->version );

			wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ) . 'assets/build/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ) );

			// use wp-color-picker-alpha as dependency
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/sticky-social-icons-admin.min.js', array('jquery', 'jquery-ui-sortable' ), time() );


			wp_localize_script( 
				$this->plugin_name, 
				'sanil_ssi_objects', 
				array( 
					'text_more_options' 		=> __( 'More Options', 'sticky-social-icons' ),
					'text_remove' 				=> __( 'Remove', 'sticky-social-icons' ),
					'text_drag' 				=> __( 'Drag', 'sticky-social-icons' ),
					'text_drag_msg' 			=> __( 'Click & drag up or down to change position', 'sticky-social-icons' ),
					'text_close' 				=> __( 'Close', 'sticky-social-icons' ),
					'text_url_to_open' 			=> __( 'URL to open', 'sticky-social-icons' ),
					'text_open_in_new_tab' 		=> __( 'Open In New Tab', 'sticky-social-icons' ),
					'text_colors' 				=> __( 'Colors', 'sticky-social-icons' ),
					'text_icon_color' 			=> __( 'Icon Color', 'sticky-social-icons' ),
					'text_icon_color_on_hover' 	=> __( 'Icon Color On Hover', 'sticky-social-icons' ),
					'text_bck_color' 			=> __( 'Background Color', 'sticky-social-icons' ),
					'text_bck_color_on_hover' 	=> __( 'Background Color On Hover', 'sticky-social-icons' ),
					'selected_icons_from_db'	=> get_option( 'sanil_ssi_db_selected_icons' )
				)
			);

		}

	}


	// callback function
	// admin menu
	public function add_menu_callback(){

		// inside settings menu
		add_submenu_page( 'options-general.php', 'Sticky Social Icons', 'Sticky Social Icons', 'manage_options', $this->settings_page_slug, array($this, 'get_settings_screen_contents'), 6 );
		

	}

	// callback function
	// add custom "settings" link in the plugins.php page
	public function custom_plugin_link_callback( $links, $file ){
		
		if ( $file == plugin_basename(dirname(__FILE__, 2) . '/sticky-social-icons.php') ) {
			// add "Settings" link
			$links[] = '<a href="options-general.php?page='. $this->settings_page_slug .'">' . __( 'Settings','sticky-social-icons') . '</a>';
		}
		
		return $links;
	}


	// callback function
	// get contents for settings page screen
	public function get_settings_screen_contents(){
		$current_tab = ( isset($_GET['tabs']) ) ? $_GET['tabs'] : 'settings';
		$tab_url = "admin.php?page=$this->settings_page_slug&tabs=";

		ob_start();
		require_once dirname( __FILE__ ) .'/templates/settings-screen.php';
		echo ob_get_clean();

	}

	// callback function
	// generate settings page form elements
	public function settings_page_ui() {

		// ---------------------------------------------
		// Settings
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name'	=> 'sticky_social_icons_settings',
			'section_id' 			=> 'settings',
			'section_label'			=> '',
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug.'-settings',
			'fields'				=> array(
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_animation',
					'field_label'			=> __( 'Enable Animation', 'sticky-social-icons' ),
					'field_callback'		=> array($this, "checkbox"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_animation', 
							'checked' 			=> 1,
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						)
					) 
				),

				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_tooltip',
					'field_label'			=> __( 'Enable Tooltip', 'sticky-social-icons' ),
					'field_callback'		=> array($this, "checkbox"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'enable_tooltip', 
							'checked' 			=> 1,
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						)
					) 
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'alignment',
					'field_label'			=> __( 'Alignment', 'sticky-social-icons' ),
					'field_callback'		=> array($this, "select"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'alignment', 
							'options' 			=> array(
								'left'			=> __( 'Left side of the screen', 'sticky-social-icons' ),
								'right' 		=> __( 'Right side of the screen', 'sticky-social-icons' ),
							) ,
							'sanitize_callback'			=> 'sanitize_text_field'
						),
					) 
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'offset_from_top',
					'field_label'			=> __( 'Offset From Top', 'sticky-social-icons' ),
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array( 
						array(
							'name'			 	=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'offset_from_top', 
							'default'		 	=> 150,
							'sanitize_callback'	=> 'sanitize_text_field',
							'css_class'			=> 'number',
							'end_label'			=> 'px'
						)
					), 
				),
			)
		);

		// create settings fields
		$this->create_settings( $settings_args );


		// ---------------------------------------------
		// Icons
		// ---------------------------------------------
		$settings_args = array(
			'settings_group_name'	=> 'sticky_social_icons_icons',
			'section_id' 			=> 'icons',
			'section_label'			=> '',
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug.'-icons',
			'fields'				=> array(
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_package',
					'field_label'			=> __( 'Select Icon Package', 'sticky-social-icons' ),
					'field_callback'		=> array($this, "select_icon_package_dropdown"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'icon_package', 
							'options' 			=> array(
								'fontawesome5'			=> __( 'Font Awesome 5', 'sticky-social-icons' ),
							) ,
							'sanitize_callback'			=> 'sanitize_text_field'
						),
					) 
				),
				array(
					'field_id'				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'selected_icons',
					'field_label'			=> '',
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> STICKY_SOCIAL_ICONS_DB_INITIALS . 'selected_icons', 
							'sanitize_callback' => 'sanitize_text_field',
							'css_class'			=> 'hidden'
						),
					) 
				),
				
			)
		);

		// create settings fields
		$this->create_settings( $settings_args );

		
	}

	
	// this function will create settings section, fields and register that settings in a database
	private function create_settings($args){
		
		// define section ---------------------------
		add_settings_section($args['section_id'], $args['section_label'], $args['section_callback'], $args['screen'] );

		foreach($args['fields'] as $field){
			
			// create label
			add_settings_field( $field['field_id'], $field['field_label'], $field['field_callback'], $args['screen'], $args['section_id'], $field['field_callback_args'] );
			
			foreach( $field['field_callback_args'] as $sub_field){
				register_setting( $args['settings_group_name'],  $sub_field['name'], array(
        			'sanitize_callback' => $sub_field['sanitize_callback']
				));
			}

		}
		
	}

	
	// -------------------------------------------------
	// form element helpers 
	// -------------------------------------------------

	public function select_icon_package_dropdown($arguments){
		ob_start();
		$this->select($arguments);
		echo '<p>More Icon Package will be added in future updates</p>';
		echo ob_get_clean();
	}

	public function text_box($arguments){
		foreach($arguments as $args){
			$default = isset( $args['default'] ) ? $args['default'] : '';
			$db_value = get_option($args['name'], $default);
			$css_class = isset( $args['css_class'] ) ? $args['css_class'] : '';
			$end_label = isset( $args['end_label'] ) ? $args['end_label'] : '';

			ob_start();
			require dirname( __FILE__ ) .'/templates/input_textbox.php';
			echo ob_get_clean();
		}
	}


	public function text_area($arguments){
		foreach($arguments as $args){
			$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
			$db_value = get_option($args['name'], $placeholder);
			$attr = isset( $args['attr'] ) ? $args['attr'] : '';

			ob_start();
			require dirname( __FILE__ ) .'/templates/input_textarea.php';
			echo ob_get_clean();
		}
	}



	public function color_picker_group($args){

		foreach($args as $arg){
			$default =  isset( $arg['default'] )  ? $arg['default'] : '';
			$db_value = ( get_option( $arg['name'] )) ? get_option( $arg['name'] ) : $default;

			ob_start();
			require dirname( __FILE__ ) .'/templates/input_colorpicker.php';
			echo ob_get_clean();
		}
	}


	public function checkbox_with_label($args){
		foreach($args as $arg){
			ob_start();
			require dirname( __FILE__ ) .'/templates/checkbox_group.php';
			echo ob_get_clean();
		}
	}


	public function checkbox($arguments){
		
		ob_start();
		foreach($arguments as $args){
			$default_state = ( array_key_exists('checked', $args) ) ? $args['checked'] : 1;
			$db_value = get_option( $args['name'], $default_state );
			$is_checked = ( $db_value ) ? 'checked' : '';
			$attr = ( array_key_exists('attr', $args) ) ? $args['attr'] : '';

			require dirname( __FILE__ ) .'/templates/input_checkbox.php';
		}
		echo ob_get_clean();
	}


	public function select($arguments){
		foreach($arguments as $args){

			$db_value = get_option($args['name']);
			$options = ( array_key_exists('options', $args) ) ? $args['options'] : array();
			
			ob_start();
			require dirname( __FILE__ ) .'/templates/input_select.php';
			echo ob_get_clean();
		}
	}


}

