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
	}

	function settings() {
		add_settings_section( 'scrollplugin_01', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'opacity', 'Opacity<br>(min. 0, max. 1)', array( $this, 'startZindexHTML' ), 'lins-scroll-to-top-settings', 'scrollplugin_01' );
		register_setting( 'lins_scroll_to_top_plugin', 'opacity', array( 'sanitize_callback' => array( $this, 'sanitizeStartZindex' ), 'default' => 0.8 ) );
	}

	function sanitizeMinMax( $fieldName, $input, $min, $max, ) {
		if ( $input < $min or $input > $max ) {
			add_settings_error( $fieldName, $fieldName . '_min_max_error', 'Input value of ' . $fieldName . ' is either below the minimum or above the maximum value' );
		}
		return true;
	}

	function sanitizeStartZindex( $input ) {
		$fieldName = 'opacity';
		$min       = 0;
		$max       = 1;
		$input     = absint( $input );
		$sanitze   = LinsScrollToTopPlugin::sanitizeMinMax( $fieldName, $input, $min, $max );
		if ( $sanitze === false ) {
			return get_option( $fieldName );
		} else {
			return $input;
		}
	}

	function startZindexHTML() { ?>
		<input type="number" name="opacity" min="0" max="1" step="0.1"
			value="<?php echo esc_attr( get_option( 'opacity' ) ) ?>">
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