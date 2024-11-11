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
		require_once( 'inc/sanitizing.php' );

		//Breakpoints
		define( 'BP_LG', 992 );
		define( 'BP_MD', 768 );
		define( 'BP_SM', 576 );


		//Defaults
		define( 'ARROW_COLOR_DEF', '#56585E' );
		define( 'ARROW_COLOR_HOVER_DEF', '#3E6EA2' );
		define( 'ARROW_OPACITY_DEF', 0.7 );
		define( 'ARROW_OPACITY_HOVER_DEF', 1 );
		define( 'BG_SIZE_DEF', 80 );
		define( 'BG_SIZE_LG_DEF', 80 );
		define( 'BG_SIZE_MD_DEF', 80 );
		define( 'BG_SIZE_SM_DEF', 80 );
		define( 'ARROW_SIZE_DEF', 65 );
		define( 'ARROW_MARGIN_DEF', 40 );
		define( 'ARROW_MARGIN_LG_DEF', 40 );
		define( 'ARROW_MARGIN_MD_DEF', 30 );
		define( 'ARROW_MARGIN_SM_DEF', 20 );
		define( 'ARROW_TRANSLATE_DEF', 10 );
		define( 'BG_HEIGHT_DEF', 160 );
		define( 'BG_HEIGHT_LG_DEF', 160 );
		define( 'BG_HEIGHT_MD_DEF', 160 );
		define( 'BG_HEIGHT_SM_DEF', 160 );
		define( 'BG_COLOR_DEF', '#0D0C0E' );
		define( 'BG_OPACITY_DEF', 0.3 );


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
			wp_enqueue_style( 'rt-customstyle', get_template_directory_uri() . '/css/lins-.css', array(), '1.0.0', 'all' );
			$opacity = get_option( 'lins_scroll_arrow_opacity', ARROW_OPACITY_DEF );

			$arrow_color       = get_option( 'lins_scroll_arrow_color', ARROW_COLOR_DEF );
			list( $r, $g, $b ) = sscanf( $arrow_color, "#%02x%02x%02x" );

			$size    = get_option( 'lins_scroll_bg_size', BG_SIZE_DEF );
			$size_lg = get_option( 'lins_scroll_bg_size_lg', BG_SIZE_LG_DEF );
			$size_md = get_option( 'lins_scroll_bg_size_md', BG_SIZE_MD_DEF );
			$size_sm = get_option( 'lins_scroll_bg_size_sm', BG_SIZE_SM_DEF );

			$arrow_size = get_option( 'lins_scroll_arrow_size', ARROW_SIZE_DEF );

			$margin_size    = get_option( 'lins_scroll_arrow_margin', ARROW_MARGIN_DEF );
			$margin_size_lg = get_option( 'lins_scroll_arrow_margin_lg', ARROW_MARGIN_LG_DEF );
			$margin_size_md = get_option( 'lins_scroll_arrow_margin_md', ARROW_MARGIN_MD_DEF );
			$margin_size_sm = get_option( 'lins_scroll_arrow_margin_lg', ARROW_MARGIN_SM_DEF );

			$translate_size = get_option( 'lins_scroll_arrow_translate', ARROW_TRANSLATE_DEF );
			$opacity_hover  = get_option( 'lins_scroll_arrow_opacity_hover', ARROW_OPACITY_HOVER_DEF );

			$arrow_color_hover                   = get_option( 'lins_scroll_arrow_color_hover', ARROW_COLOR_HOVER_DEF );
			list( $r_hover, $g_hover, $b_hover ) = sscanf( $arrow_color_hover, "#%02x%02x%02x" );

			$bg_height    = get_option( 'lins_scroll_bg_height', BG_HEIGHT_DEF );
			$bg_height_lg = get_option( 'lins_scroll_bg_height_lg', BG_HEIGHT_LG_DEF );
			$bg_height_md = get_option( 'lins_scroll_bg_height_md', BG_HEIGHT_MD_DEF );
			$bg_height_sm = get_option( 'lins_scroll_bg_height_sm', BG_HEIGHT_SM_DEF );

			$bg_color                   = get_option( 'lins_scroll_bg_color', BG_COLOR_DEF );
			list( $r_bg, $g_bg, $b_bg ) = sscanf( $bg_color, "#%02x%02x%02x" );

			$bg_opacity = get_option( 'lins_scroll_bg_opacity', BG_OPACITY_DEF );

			$custom_css = ".scroll-arrow {
										background-color: rgba( {$r} , {$g} , {$b} , {$opacity} );
										width: {$size}px;
										height: {$size}px;
										right: {$margin_size}px;
										bottom: {$margin_size}px;
								    }";

			$custom_css .= ".scroll-arrow:hover, .scroll-arrow:focus-within { background: rgba( {$r_hover} , {$g_hover} , {$b_hover} , {$opacity_hover} ) ; }";
			$custom_css .= ".scroll-arrow svg {width: {$arrow_size}%;}";
			$custom_css .= ".scroll-arrow:hover svg, .scroll-arrow:focus-within svg { transform: rotate(180deg) translateY({$translate_size}px);}";


			$custom_css .= ".scroll-bottom-fade { height:{$bg_height}px; }";

			$custom_css .= "@media only screen and (max-width: " . BP_LG . "px) { .scroll-bottom-fade { height:{$bg_height_lg}px; } }";
			$custom_css .= "@media only screen and (max-width: " . BP_MD . "px) { .scroll-bottom-fade { height:{$bg_height_md}px; } }";
			$custom_css .= "@media only screen and (max-width: " . BP_SM . "px) { .scroll-bottom-fade { height:{$bg_height_sm}px; } }";

			$custom_css .= ".scroll-bottom-fade { background: linear-gradient(180deg, transparent, rgba( {$r_bg}, {$g_bg}, {$b_bg}, {$bg_opacity} ) ) ; }";

			$custom_css .= "@media only screen and (max-width: " . BP_LG . "px) { .scroll-arrow { width: {$size_lg}px; height:{$size_lg}px ;} }";
			$custom_css .= "@media only screen and (max-width: " . BP_MD . "px) { .scroll-arrow { width: {$size_md}px; height:{$size_md}px ;} }";
			$custom_css .= "@media only screen and (max-width: " . BP_SM . "px) { .scroll-arrow { width: {$size_sm}px; height:{$size_sm}px ;} }";

			$custom_css .= "@media only screen and (max-width: " . BP_LG . "px) { .scroll-arrow { right: {$margin_size_lg}px; bottom:{$margin_size_lg}px ;} }";
			$custom_css .= "@media only screen and (max-width: " . BP_MD . "px) { .scroll-arrow { right: {$margin_size_md}px; bottom:{$margin_size_md}px ;} }";
			$custom_css .= "@media only screen and (max-width: " . BP_SM . "px) { .scroll-arrow { right: {$margin_size_sm}px; bottom:{$margin_size_sm}px ;} }";

			wp_add_inline_style( 'rt-customstyle', $custom_css );
		}
		add_action( 'wp_enqueue_scripts', 'rt_custom_enqueue' );
	}

	function settings() {
		add_settings_section( 'scrollplugin_01', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_opacity', 'Arrow Opacity<br>(min. 0, max. 1)', array( $this, 'opacity_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_01' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_opacity', array( 'sanitize_callback' => array( $this, 'sanitize_opacity' ), 'default' => ARROW_OPACITY_DEF ) );

		add_settings_section( 'scrollplugin_02', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_color', 'Arrow Background Color', array( $this, 'color_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_02' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_color', array( 'sanitize_callback' => array( $this, 'sanitize_color' ), 'default' => ARROW_COLOR_DEF ) );

		add_settings_section( 'scrollplugin_03', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_opacity_hover', 'Arrow Opacity Hover<br>(min. 0, max. 1)', array( $this, 'opacity_hover_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_03' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_opacity_hover', array( 'sanitize_callback' => array( $this, 'sanitize_opacity_hover' ), 'default' => ARROW_OPACITY_HOVER_DEF ) );

		add_settings_section( 'scrollplugin_04', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_color', 'Arrow Background Color Hover', array( $this, 'color_hover_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_04' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_color_hover', array( 'sanitize_callback' => array( $this, 'sanitize_color_hover' ), 'default' => ARROW_COLOR_HOVER_DEF ) );

		add_settings_section( 'scrollplugin_05', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_size', 'Arrow Background Size', array( $this, 'bg_size_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_05' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_size', array( 'sanitize_callback' => array( $this, 'sanitize_size' ), 'default' => BG_SIZE_DEF ) );

		add_settings_section( 'scrollplugin_06', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_size_lg', 'Arrow Background Size (screen <= ' . BP_LG . 'px)', array( $this, 'bg_size_lg_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_06' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_size_lg', array( 'sanitize_callback' => array( $this, 'sanitize_size_lg' ), 'default' => BG_SIZE_LG_DEF ) );

		add_settings_section( 'scrollplugin_07', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_size_md', 'Arrow Background Size (screen <= ' . BP_MD . 'px)', array( $this, 'bg_size_md_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_07' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_size_md', array( 'sanitize_callback' => array( $this, 'sanitize_size_md' ), 'default' => BG_SIZE_MD_DEF ) );

		add_settings_section( 'scrollplugin_08', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_size_sm', 'Arrow Background Size (screen <= ' . BP_SM . 'px)', array( $this, 'bg_size_sm_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_08' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_size_sm', array( 'sanitize_callback' => array( $this, 'sanitize_size_sm' ), 'default' => BG_SIZE_SM_DEF ) );

		add_settings_section( 'scrollplugin_09', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_size', 'Arrow Size (% of Arrow Background Size)', array( $this, 'arrow_size_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_09' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_size', array( 'sanitize_callback' => array( $this, 'sanitize_size_min_max' ), 'default' => ARROW_SIZE_DEF ) );

		add_settings_section( 'scrollplugin_10', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_margin', 'Arrow Margin from Screen', array( $this, 'arrow_margin_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_10' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_margin', array( 'sanitize_callback' => array( $this, 'sanitize_margin' ), 'default' => ARROW_MARGIN_DEF ) );

		add_settings_section( 'scrollplugin_11', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_margin_lg', 'Arrow Margin from Screen (screen <= ' . BP_LG . 'px)', array( $this, 'arrow_margin_lg_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_11' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_margin_lg', array( 'sanitize_callback' => array( $this, 'sanitize_margin_lg' ), 'default' => ARROW_MARGIN_LG_DEF ) );

		add_settings_section( 'scrollplugin_12', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_margin_md', 'Arrow Margin from Screen (screen <= ' . BP_MD . 'px)', array( $this, 'arrow_margin_md_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_12' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_margin_md', array( 'sanitize_callback' => array( $this, 'sanitize_margin_md' ), 'default' => ARROW_MARGIN_MD_DEF ) );

		add_settings_section( 'scrollplugin_13', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_margin_sm', 'Arrow Margin from Screen (screen <= ' . BP_SM . 'px)', array( $this, 'arrow_margin_sm_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_13' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_margin_sm', array( 'sanitize_callback' => array( $this, 'sanitize_margin_sm' ), 'default' => ARROW_MARGIN_SM_DEF ) );

		add_settings_section( 'scrollplugin_14', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_translate', 'Arrow Translate Height (moving up when hovering)', array( $this, 'arrow_translate_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_14' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_translate', array( 'sanitize_callback' => array( $this, 'sanitize_translate' ), 'default' => ARROW_TRANSLATE_DEF ) );

		add_settings_section( 'scrollplugin_15', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_height', 'Background Shadow Height (showing up when hovering)', array( $this, 'bg_height_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_15' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_height', array( 'sanitize_callback' => array( $this, 'sanitize_bg_height' ), 'default' => BG_HEIGHT_DEF ) );

		add_settings_section( 'scrollplugin_16', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_height_lg', 'Background Shadow Height (screen <= ' . BP_LG . 'px)', array( $this, 'bg_height_lg_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_16' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_height_lg', array( 'sanitize_callback' => array( $this, 'sanitize_bg_height_lg' ), 'default' => BG_HEIGHT_LG_DEF ) );

		add_settings_section( 'scrollplugin_17', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_height_md', 'Background Shadow Height (screen <= ' . BP_MD . 'px)', array( $this, 'bg_height_md_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_17' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_height_md', array( 'sanitize_callback' => array( $this, 'sanitize_bg_height_md' ), 'default' => BG_HEIGHT_MD_DEF ) );

		add_settings_section( 'scrollplugin_18', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_height_sm', 'Background Shadow Height (screen <= ' . BP_SM . 'px)', array( $this, 'bg_height_sm_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_18' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_height_sm', array( 'sanitize_callback' => array( $this, 'sanitize_bg_height_sm' ), 'default' => BG_HEIGHT_SM_DEF ) );

		add_settings_section( 'scrollplugin_19', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_color', 'Background Shadow Color (showing up when hovering)', array( $this, 'bg_color_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_19' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_color', array( 'sanitize_callback' => array( $this, 'sanitize_bg_color' ), 'default' => BG_COLOR_DEF ) );

		add_settings_section( 'scrollplugin_20', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_opacity', 'Background Shadow Color (showing up when hovering)', array( $this, 'bg_opacity_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_20' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_opacity', array( 'sanitize_callback' => array( $this, 'sanitize_bg_opacity' ), 'default' => BG_OPACITY_DEF ) );
	}

	// Sanitzing
	function sanitize_min_max( $field_name, $input, $min, $max ) {
		if ( ! is_numeric( $input ) ) {
			add_settings_error( $field_name, "{$field_name}_number_error", "Input value of {$field_name} is not a number" );
			return false;
		}
		if ( ! is_numeric( $min ) || ! is_numeric( $max ) ) {
			add_settings_error( $field_name, "{$field_name}_min_max_number_error", 'Input value of the set minimum or maximum is not a number! The error is not caused by the input but by set maximum and minimum values within the plugin.' );
			return false;
		}
		if ( $input < $min or $input > $max ) {
			add_settings_error( $field_name, "{$field_name}_min_error", 'Input value of ' . $field_name . ' is either below the minimum or above the maximum value' );
			return false;
		}
		return true;
	}

	function sanitize_min( $field_name, $input, $min ) {
		if ( ! is_numeric( $input ) ) {
			add_settings_error( $field_name, "{$field_name}_number_error", "Input value of {$field_name} is not a number" );
			return false;
		}
		if ( ! is_numeric( $min ) ) {
			add_settings_error( $field_name, "{$field_name}_min_number_error", 'Input value of the set minimum is not a number! The error is not caused by the input but by a set minimum value within the plugin.' );
			return false;
		}
		if ( $input < $min ) {
			add_settings_error( $field_name, "{$field_name}_min_error", "Input value of {$field_name} is below the minimum value" );
			return false;
		}
		return true;
	}

	// Settings sanitizing
	function sanitize_opacity( $input ) {
		$field_name = 'lins_scroll_arrow_opacity';
		$min        = 0;
		$max        = 1;
		$sanitize   = Lins_Scroll_To_Top::sanitize_min_max( $field_name, $input, $min, $max );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_color( $input ) {
		$field_name = 'lins_scroll_arrow_color';
		$sanitize   = false;
		$input      = strtoupper( $input );
		$rest       = substr( $input, 1, strlen( $input ) );
		$hash_key   = substr( $input, 0, 1 );

		if ( $hash_key === '#' && strlen( $input ) === 7 && ctype_xdigit( $rest ) ) {
			$sanitize = true;
		}
		if ( $sanitize === false ) {
			add_settings_error( $field_name, "{$field_name}_invalid_hex_code", "Input value of {$field_name} is not a valid HEX code (for example: #FF0000)." );
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_opacity_hover( $input ) {
		$field_name = 'lins_scroll_arrow_opacity_hover';
		$min        = 0;
		$max        = 1;
		$sanitize   = Lins_Scroll_To_Top::sanitize_min_max( $field_name, $input, $min, $max );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_color_hover( $input ) {
		$field_name = 'lins_scroll_arrow_color_hover';
		$sanitize   = false;
		$input      = strtoupper( $input );
		$rest       = substr( $input, 1, strlen( $input ) );
		$hash_key   = substr( $input, 0, 1 );

		if ( $hash_key === '#' && strlen( $input ) === 7 && ctype_xdigit( $rest ) ) {
			$sanitize = true;
		}
		if ( $sanitize === false ) {
			add_settings_error( $field_name, "{$field_name}_invalid_hex_code", "Input value of {$field_name} is not a valid HEX code (for example: #FF0000)." );
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_size( $input ) {
		$field_name = 'lins_scroll_bg_size';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}
	function sanitize_size_lg( $input ) {
		$field_name = 'lins_scroll_bg_size_lg';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_size_md( $input ) {
		$field_name = 'lins_scroll_bg_size_md';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}
	function sanitize_size_sm( $input ) {
		$field_name = 'lins_scroll_bg_size_md';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_size_min_max( $input ) {
		$field_name = 'lins_scroll_arrow_size';
		$min        = 0;
		$max        = 100;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min_max( $field_name, $input, $min, $max );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_margin( $input ) {
		$field_name = 'lins_scroll_arrow_margin';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_margin_lg( $input ) {
		$field_name = 'lins_scroll_arrow_margin_lg';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_margin_md( $input ) {
		$field_name = 'lins_scroll_arrow_margin_md';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_margin_sm( $input ) {
		$field_name = 'lins_scroll_arrow_margin_sm';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_translate( $input ) {
		$field_name = 'lins_scroll_arrow_translate';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_bg_height( $input ) {
		$field_name = 'lins_scroll_bg_height';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_bg_height_lg( $input ) {
		$field_name = 'lins_scroll_bg_height_lg';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_bg_height_md( $input ) {
		$field_name = 'lins_scroll_bg_height_md';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_bg_height_sm( $input ) {
		$field_name = 'lins_scroll_bg_height_sm';
		$min        = 0;
		$input      = absint( $input );
		$sanitize   = Lins_Scroll_To_Top::sanitize_min( $field_name, $input, $min );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_bg_color( $input ) {
		$field_name = 'lins_scroll_bg_color';
		$sanitize   = false;
		$input      = strtoupper( $input );
		$rest       = substr( $input, 1, length: strlen( $input ) );
		$hash_key   = substr( $input, 0, 1 );

		if ( $hash_key === '#' && strlen( $input ) === 7 && ctype_xdigit( $rest ) ) {
			$sanitize = true;
		}
		if ( $sanitize === false ) {
			add_settings_error( $field_name, "{$field_name}_invalid_hex_code", "Input value of {$field_name} is not a valid HEX code (for example: #FF0000)." );
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	function sanitize_bg_opacity( $input ) {
		$field_name = 'lins_scroll_bg_opacity';
		$min        = 0;
		$max        = 1;
		$sanitize   = Lins_Scroll_To_Top::sanitize_min_max( $field_name, $input, $min, $max );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}

	// HTML Rendering
	function opacity_html() {
		?>
		<input type="number" name="lins_scroll_arrow_opacity" min="0.0" max="1" step="0.01"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_opacity' ) ); ?>" />
		<?php
	}

	function color_html() {
		?>
		<input type="text" name="lins_scroll_arrow_color"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_color' ) ); ?>" class="my-color-field" />
		<?php
	}

	function opacity_hover_html() {
		?>
		<input type="number" name="lins_scroll_arrow_opacity_hover" min="0.0" max="1" step="0.01"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_opacity_hover' ) ) ?>">
		<?php
	}

	function color_hover_html() {
		?>
		<input type="text" name="lins_scroll_arrow_color_hover"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_color_hover' ) ); ?>" class="my-color-field" />
		<?php
	}

	function bg_size_html() {
		?>
		<input type="number" name="lins_scroll_bg_size" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_bg_size' ) ) ?>"> px
		<?php
	}
	function bg_size_lg_html() {
		?>
		<input type="number" name="lins_scroll_bg_size_lg" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_bg_size_lg' ) ) ?>"> px
		<?php
	}

	function bg_size_md_html() {
		?>
		<input type="number" name="lins_scroll_bg_size_md" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_bg_size_md' ) ) ?>"> px
		<?php
	}
	function bg_size_sm_html() {
		?>
		<input type="number" name="lins_scroll_bg_size_sm" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_bg_size_sm' ) ) ?>"> px
		<?php
	}

	function arrow_size_html() {
		?>
		<input type="number" name="lins_scroll_arrow_size" min="0" max="100" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_size' ) ) ?>"> %
		<?php
	}

	function arrow_margin_html() {
		?>
		<input type="number" name="lins_scroll_arrow_margin" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_margin' ) ) ?>"> px
		<?php
	}

	function arrow_margin_sm_html() {
		?>
		<input type="number" name="lins_scroll_arrow_margin_sm" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_margin_sm' ) ) ?>"> px
		<?php
	}

	function arrow_margin_md_html() {
		?>
		<input type="number" name="lins_scroll_arrow_margin_md" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_margin_md' ) ) ?>"> px
		<?php
	}
	function arrow_margin_lg_html() {
		?>
		<input type="number" name="lins_scroll_arrow_margin_lg" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_margin_lg' ) ) ?>"> px
		<?php
	}

	function arrow_translate_html() {
		?>
		<input type="number" name="lins_scroll_arrow_translate" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_translate' ) ) ?>"> px
		<?php
	}
	function bg_height_html() {
		?>
		<input type="number" name="lins_scroll_bg_height" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_bg_height' ) ); ?>" /> px
		<?php
	}

	function bg_height_lg_html() {
		?>
		<input type="number" name="lins_scroll_bg_height_lg" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_bg_height_lg' ) ); ?>" /> px
		<?php

	}

	function bg_height_md_html() {
		?>
		<input type="number" name="lins_scroll_bg_height_md" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_bg_height_md' ) ); ?>" /> px
		<?php
	}

	function bg_height_sm_html() {
		?>
		<input type="number" name="lins_scroll_bg_height_sm" min="0" step="1"
			value="<?php echo esc_attr( get_option( 'lins_scroll_bg_height_sm' ) ); ?>" /> px
		<?php
	}

	function bg_color_html() {
		?>
		<input type="text" name="lins_scroll_bg_color" value="<?php echo esc_attr( get_option( 'lins_scroll_bg_color' ) ); ?>"
			class="my-color-field" />
		<?php
	}
	function bg_opacity_html() {
		?>
		<input type="number" name="lins_scroll_bg_opacity" min="0.0" max="1" step="0.01"
			value="<?php echo esc_attr( get_option( 'lins_scroll_bg_opacity' ) ); ?>" />
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