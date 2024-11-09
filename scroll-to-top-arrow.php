<?php

/*
	Plugin Name: Scroll To Top Arrow by Fabian Lins
	Description: Creates a scroll to top arrow.
	Version: 1.0
	Author: Fabian Lins
	Autor URI: https://github.com/FabianLins/
*/

class Lins_Scroll_To_Top {
	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_page' ) );
		add_action( 'admin_init', array( $this, 'settings' ) );

		function add_css() {
			$plugin_url = plugin_dir_url( __FILE__ );
			wp_enqueue_style( 'style', $plugin_url . 'style/style.min.css' );
		}

		add_action( 'wp_enqueue_scripts', 'add_css' );

		function render_html() {
			echo
				'<div class="scroll-arrow" onclick="linsScrollToTop()">
					<svg fill="#000000" width="100%" height="100%" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330" xml:space="preserve">
					<path id="XMLID_225_" d="M325.607,79.393c-5.857-5.857-15.355-5.858-21.213,0.001l-139.39,139.393L25.607,79.393
						c-5.857-5.857-15.355-5.858-21.213,0.001c-5.858,5.858-5.858,15.355,0,21.213l150.004,150c2.813,2.813,6.628,4.393,10.606,4.393
						s7.794-1.581,10.606-4.394l149.996-150C331.465,94.749,331.465,85.251,325.607,79.393z"></path>
					</svg>
				</div>
				<div class="scroll-bottom-fade">
				</div>';
		}

		add_action( 'wp_footer', 'render_html' );

		function add_js() {
			$plugin_url = plugin_dir_url( __FILE__ );
			wp_enqueue_script( 'add_js',
				$plugin_url . 'script/script.js',
				array(),
				'1.0.0',
				array(
					'strategy' => 'defer',
				)
			);
		}

		add_action( 'wp_enqueue_scripts', 'add_js' );

		add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );

		function mw_enqueue_color_picker() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'my-script-handle', plugins_url( 'script/color-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
		}

		wp_enqueue_style( 'my-stylesheet', false );

		function rt_custom_enqueue() {
			wp_enqueue_style( 'rt-customstyle', get_template_directory_uri() . '/css/custom.css', array(), '1.0.0', 'all' );
			$opacity           = get_option( 'scroll_arrow_opacity', 0.7 );
			$arrow_color       = get_option( 'scroll_arrow_color', '#56585E' );
			list( $r, $g, $b ) = sscanf( $arrow_color, "#%02x%02x%02x" );
			$size              = get_option( 'scroll_arrow_size', 80 );
			$custom_css        = ".scroll-arrow {
										background-color: rgba( {$r} , {$g} , {$b} , {$opacity} );
										width: {$size}px;
										height: {$size}px;
								    }";
			$opacity_hover     = get_option( 'scroll_arrow_opacity_hover', 1 );
			$arrow_color_hover = get_option( 'scroll_arrow_color_hover', '#3E6EA2' );
			list( $r, $g, $b ) = sscanf( $arrow_color_hover, "#%02x%02x%02x" );
			$custom_css .= ".scroll-arrow:hover, .scroll-arrow:focus-within { background: rgba( {$r} , {$g} , {$b} , {$opacity_hover} ) ; }";



			wp_add_inline_style( 'rt-customstyle', $custom_css );
		}
		add_action( 'wp_enqueue_scripts', 'rt_custom_enqueue' );
	}

	function settings() {
		add_settings_section( 'scrollplugin_01', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'scroll_arrow_opacity', 'Arrow Opacity<br>(min. 0, max. 1)', array( $this, 'opacity_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_01' );
		register_setting( 'lins_scroll_to_top_plugin', 'scroll_arrow_opacity', array( 'sanitize_callback' => array( $this, 'sanitize_opacity' ), 'default' => 0.8 ) );

		add_settings_section( 'scrollplugin_02', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'scroll_arrow_color', 'Arrow Background Color', array( $this, 'color_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_02' );
		register_setting( 'lins_scroll_to_top_plugin', 'scroll_arrow_color', array( 'sanitize_callback' => array( $this, 'sanitize_color' ), 'default' => '#56585E' ) );

		add_settings_section( 'scrollplugin_03', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'scroll_arrow_opacity_hover', 'Arrow Opacity Hover<br>(min. 0, max. 1)', array( $this, 'opacity_hover_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_03' );
		register_setting( 'lins_scroll_to_top_plugin', 'scroll_arrow_opacity_hover', array( 'sanitize_callback' => array( $this, 'sanitize_opacity_hover' ), 'default' => 1 ) );

		add_settings_section( 'scrollplugin_04', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'scroll_arrow_color', 'Arrow Background Color Hover', array( $this, 'color_hover_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_04' );
		register_setting( 'lins_scroll_to_top_plugin', 'scroll_arrow_color_hover', array( 'sanitize_callback' => array( $this, 'sanitize_color_hover' ), 'default' => '#3E6EA2' ) );

		add_settings_section( 'scrollplugin_05', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'scroll_arrow_size', 'Arrow Background Size', array( $this, 'size_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_05' );
		register_setting( 'lins_scroll_to_top_plugin', 'scroll_arrow_size', array( 'sanitize_callback' => array( $this, 'sanitize_size' ), 'default' => 80 ) );
	}

	function sanitize_min_max( $field_name, $input, $min, $max ) {
		if ( $input < $min or $input > $max ) {
			add_settings_error( $field_name, $field_name . '_min_max_error', 'Input value of ' . $field_name . ' is either below the minimum or above the maximum value' );
			return false;
		}
		return true;
	}

	function sanitize_min( $field_name, $input, $min ) {
		if ( $input < $min ) {
			add_settings_error( $field_name, $field_name . '_min_error', 'Input value of ' . $field_name . ' is below the minimum value' );
			return false;
		}
		return true;
	}

	function sanitize_opacity( $input ) {
		$field_name = 'scroll_arrow_opacity';
		$min        = 0;
		$max        = 1;
		$sanitize   = Lins_Scroll_To_Top::sanitize_min_max( $field_name, $input, $min, $max );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function opacity_html() {
		?>
		<input type="number" name="scroll_arrow_opacity" min="0.0" max="1" step="0.1"
			value="<?php echo esc_attr( get_option( 'scroll_arrow_opacity' ) ); ?>" />
		<?php
	}

	function sanitize_color( $input ) {
		$field_name = 'scroll_arrow_color';
		$sanitize   = false;
		$input      = strtoupper( $input );
		$rest       = substr( $input, 1, strlen( $input ) );
		$hash_key   = substr( $input, 0, 1 );

		if ( $hash_key === '#' && strlen( $input ) === 7 && ctype_xdigit( $rest ) ) {
			$sanitize = true;
		}
		if ( $sanitize === false ) {
			add_settings_error( $field_name, $field_name . '_invalid_hex_code', 'Input value of ' . $field_name . ' is not a valid HEX code (for example: #FF0000). ' );
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function color_html() {
		?>
		<input type="text" name="scroll_arrow_color" value="<?php echo esc_attr( get_option( 'scroll_arrow_color' ) ); ?>"
			data-default-color="#56585E" class="my-color-field" />
		<?php
	}

	function sanitize_opacity_hover( $input ) {
		$field_name = 'scroll_arrow_opacity_hover';
		$min        = 0;
		$max        = 1;
		$sanitize   = Lins_Scroll_To_Top::sanitize_min_max( $field_name, $input, $min, $max );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function opacity_hover_html() {
		?>
		<input type="number" name="scroll_arrow_opacity_hover" min="0.0" max="1" step="0.1"
			value="<?php echo esc_attr( get_option( 'scroll_arrow_opacity_hover' ) ) ?>">
		<?php
	}

	function sanitize_color_hover( $input ) {
		$field_name = 'scroll_arrow_color_hover';
		$sanitize   = false;
		$input      = strtoupper( $input );
		$rest       = substr( $input, 1, strlen( $input ) );
		$hash_key   = substr( $input, 0, 1 );

		if ( $hash_key === '#' && strlen( $input ) === 7 && ctype_xdigit( $rest ) ) {
			$sanitize = true;
		}
		if ( $sanitize === false ) {
			add_settings_error( $field_name, $field_name . '_invalid_hex_code', 'Input value of ' . $field_name . ' is not a valid HEX code (for example: #FF0000). ' );
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function color_hover_html() {
		?>
		<input type="text" name="scroll_arrow_color_hover"
			value="<?php echo esc_attr( get_option( 'scroll_arrow_color_hover' ) ); ?>" data-default-color="#3e6ea2"
			class="my-color-field" />
		<?php
	}

	function sanitize_size( $input ) {
		$field_name = 'scroll_arrow_size';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function size_html() {
		?>
		<input type="number" name="scroll_arrow_size" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'scroll_arrow_size' ) ) ?>"> px
		<?php
	}

	function admin_page() {
		add_options_page( 'Scroll Top Settings', 'Scroll Top Settings', 'manage_options', 'lins-scroll-to-top-settings', array( $this, 'return_html' ) );
	}

	function return_html() {
		?>
		<div class="wrap">
			<h1>Scroll To Top Arrow Global Settings (by Fabian Lins)</h1>
			<form action="options.php" method="POST">
				<?php
				settings_fields( 'lins_scroll_to_top_plugin' );
				do_settings_sections( 'lins-scroll-to-top-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}

$lins_scroll_to_top_plugin = new Lins_Scroll_To_Top();