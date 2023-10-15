<?php

/*
	Plugin Name: Scroll To Top Arrow by Fabian Lins
	Description: Creates a scroll to top arrow.
	Version: 1.0
	Author: Fabian Lins
	Autor URI: https://github.com/FabianLins/
*/

class LinsScrollToTopPlugin {
	function __construct() {
		add_action( 'admin_menu', array( $this, 'adminPage' ) );
		add_action( 'admin_init', array( $this, 'settings' ) );

		function add_css() {
			$plugin_url = plugin_dir_url( __FILE__ );
			wp_enqueue_style( 'style', $plugin_url . 'style/style.min.css' );
		}

		add_action( 'wp_enqueue_scripts', 'add_css' );
		function render_html() {
			$opacity = get_option( 'scrollplugin_01' );
			echo $opacity .
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
		wp_enqueue_style( 'my-stylesheet', false );
		function rt_custom_enqueue() {
			wp_enqueue_style( 'rt-customstyle', get_template_directory_uri() . '/css/custom.css', array(), '1.0.0', 'all' );
			$opacity    = get_option( 'scroll_arrow_opacity' );
			$custom_css = ".scroll-arrow{background-color: rgba(71,41,164,{$opacity}) ;}";
			wp_add_inline_style( 'rt-customstyle', $custom_css );
		}
		add_action( 'wp_enqueue_scripts', 'rt_custom_enqueue' );
	}

	function settings() {
		add_settings_section( 'scrollplugin_01', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'scroll_arrow_opacity', 'Opacity<br>(min. 0, max. 1)', array( $this, 'opacityHTML' ), 'lins-scroll-to-top-settings', 'scrollplugin_01' );
		register_setting( 'lins_scroll_to_top_plugin', 'scroll_arrow_opacity', array( 'sanitize_callback' => array( $this, 'sanitizeOpacity' ), 'default' => 0.8 ) );
	}

	function sanitizeMinMax( $fieldName, $input, $min, $max, ) {
		if ( $input < $min or $input > $max ) {
			add_settings_error( $fieldName, $fieldName . '_min_max_error', 'Input value of ' . $fieldName . ' is either below the minimum or above the maximum value' );
			return false;
		}
		return true;
	}

	function sanitizeOpacity( $input ) {
		$fieldName = 'scroll_arrow_opacity';
		$min       = 0;
		$max       = 1;
		$sanitze   = LinsScrollToTopPlugin::sanitizeMinMax( $fieldName, $input, $min, $max );
		if ( $sanitze === false ) {
			return get_option( $fieldName );
		} else {
			return $input;
		}
	}

	function opacityHTML() { ?>
		<input type="number" name="scroll_arrow_opacity" min="0.0" max="1" step="0.1"
			value="<?php echo esc_attr( get_option( 'scroll_arrow_opacity' ) ) ?>">
	<?php }

	function adminPage() {
		add_options_page( 'Scroll Top Settings', 'Scroll Top Settings', 'manage_options', 'lins-scroll-to-top-settings', array( $this, 'returnHTML' ) );
	}

	function returnHTML() { ?>
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
	<?php }
}

$linsScrollToTopPlugin = new LinsScrollToTopPlugin();