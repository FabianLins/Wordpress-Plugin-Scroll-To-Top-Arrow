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
		include 'inc/uuid.inc.php';

		// Blank UUID
		define( 'BLANK_UUID', '00000000-0000-0000-0000-000000000000' );

		//Breakpoints
		define( 'BP_LG', 992 );
		define( 'BP_MD', 768 );
		define( 'BP_SM', 576 );

		//Defaults
		define( 'ARROW_FILL_DEF', '#F5F9F9' );
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

		add_action( 'wp_ajax_update_preset', 'update_preset' );
		add_action( 'wp_ajax_nopriv_update_preset', 'update_preset' );

		add_action( 'wp_ajax_edit_preset_name', 'edit_preset_name' );
		add_action( 'wp_ajax_nopriv_edit_preset_name', 'edit_preset_name' );

		add_action( 'wp_ajax_check_preset_name', 'check_preset_name' );
		add_action( 'wp_ajax_nopriv_check_preset_name', 'check_preset_name' );

		add_action( 'wp_ajax_remove_preset', 'remove_preset' );
		add_action( 'wp_ajax_nopriv_remove_preset', 'remove_preset' );

		add_action( 'wp_ajax_save_preset', 'save_preset' );
		add_action( 'wp_ajax_nopriv_save_preset', 'save_preset' );

		add_action( 'wp_ajax_load_preset', 'load_preset' );
		add_action( 'wp_ajax_nopriv_load_preset', 'load_preset' );

		add_action( 'wp_ajax_reload_preset_select', 'reload_preset_select' );
		add_action( 'wp_ajax_nopriv_reload_preset_select', 'reload_preset_select' );

		add_action( 'wp_ajax_reload_preset_select_remove', 'reload_preset_select_remove' );
		add_action( 'wp_ajax_nopriv_reload_preset_select_remove', 'reload_preset_select_remove' );


		function sanitize_opacity_db( $input ) {
			$input = floatval( $input );
			if ( is_float( $input ) ) {
				$input = round( $input, 2 );
				$input = strtoupper( $input );
				if ( $input >= 0 && $input <= 1 ) {
					return $input;
				}
			}
			return false;
		}
		function sanitize_hex_db( $input ) {
			$input    = strtoupper( $input );
			$rest     = substr( $input, 1, strlen( $input ) );
			$hash_key = substr( $input, 0, 1 );

			if ( $hash_key === '#' && strlen( $input ) === 7 && ctype_xdigit( $rest ) ) {
				return substr( strtoupper( $input ), 1 );
			}
			return false;
		}

		function update_preset() {
			global $wpdb;
			$preset = $_POST['ajax_data'];
			if ( $preset['uuid'] !== BLANK_UUID ) {
				$table_name = $wpdb->prefix . 'lins_scroll_arrow_presets';

				$errors = array();
				//if $preset['presetName'] exist (fetch active presets), give error and check if it is not an empty string
				$preset['scrollArrowFill'] = sanitize_hex_db( $preset['scrollArrowFill'] );
				if ( $preset['scrollArrowFill'] === false ) {
					$errors[] = 'Hex Code Arrow Fill Wrong (Error 702)';
				}

				$preset['scrollArrowOpacity'] = sanitize_opacity_db( $preset['scrollArrowOpacity'] );
				if ( $preset['scrollArrowOpacity'] === false ) {
					$errors[] = 'Scroll Arrow Opacity Wrong (Error 703)';
				}

				$preset['scrollArrowBg'] = sanitize_hex_db( $preset['scrollArrowBg'] );
				if ( $preset['scrollArrowBg'] === false ) {
					$errors[] = 'Hex Code Arrow Background Wrong (Error 704)';
				}

				$preset['scrollArrowOpacityHover'] = sanitize_opacity_db( $preset['scrollArrowOpacityHover'] );
				if ( $preset['scrollArrowOpacityHover'] === false ) {
					$errors[] = 'Scroll Arrow Opacity Hover Wrong (Error 705)';
				}

				$preset['scrollArrowBgHover'] = sanitize_hex_db( $preset['scrollArrowBgHover'] );
				if ( $preset['scrollArrowBgHover'] === false ) {
					$errors[] = 'Hex Code Arrow Background Hover Wrong (Error 706)';

				}

				if ( ! is_int( (int) $preset['scrollArrowBgSize'] ) ) {
					$errors[] = 'Arrow Background Size Wrong (Error 707)';
				}

				if ( ! is_int( (int) $preset['scrollArrowBgSizeLg'] ) ) {
					$errors[] = 'Arrow Background Size LG Wrong (Error 708)';

				}

				if ( ! is_int( (int) $preset['scrollArrowBgSizeMd'] ) ) {
					$errors[] = 'Arrow Background Size MD Wrong (Error 709)';
				}

				if ( ! is_int( (int) $preset['scrollArrowBgSizeSm'] ) ) {
					$errors[] = 'Arrow Background Size SM Wrong (Error 710)';
				}
				if ( ! is_int( (int) $preset['scrollArrowSize'] ) || (int) $preset['scrollArrowSize'] > 100 ) {
					$errors[] = 'Arrow Size Wrong (Error 711)';
				}

				if ( ! is_int( (int) $preset['scrollArrowMargin'] ) || $preset['scrollArrowMargin'] < 0 ) {
					$errors[] = 'Arrow Margin Wrong (Error 712)';
				}

				if ( ! is_int( (int) $preset['scrollArrowMarginLg'] ) || $preset['scrollArrowMarginLg'] < 0 ) {
					$errors[] = 'Arrow Margin LG Wrong (Error 713)';
				}

				if ( ! is_int( (int) $preset['scrollArrowMarginMd'] ) || $preset['scrollArrowMarginMd'] < 0 ) {
					$errors[] = 'Arrow Margin MD Wrong (Error 714)';
				}

				if ( ! is_int( (int) $preset['scrollArrowMarginSm'] ) || $preset['scrollArrowMarginSm'] < 0 ) {
					$errors[] = 'Arrow Margin SM Wrong (Error 715)';
				}

				if ( ! is_int( (int) $preset['scrollArrowTranslate'] ) || $preset['scrollArrowTranslate'] < 0 ) {
					$errors[] = 'Arrow Translate Wrong (Error 716)';
				}

				if ( ! is_int( (int) $preset['scrollBgHeight'] ) || $preset['scrollBgHeight'] < 0 ) {
					$errors[] = 'Background Height Wrong (Error 717)';
				}

				if ( ! is_int( (int) $preset['scrollBgHeightLg'] ) || $preset['scrollBgHeightLg'] < 0 ) {
					$errors[] = 'Background Height LG Wrong (Error 718)';
				}

				if ( ! is_int( (int) $preset['scrollBgHeightMd'] ) || $preset['scrollBgHeightMd'] < 0 ) {
					$errors[] = 'Background Height MD Wrong (Error 719)';
				}

				if ( ! is_int( (int) $preset['scrollBgHeightSm'] ) || $preset['scrollBgHeightSm'] < 0 ) {
					$errors[] = 'Background Height SM Wrong (Error 720)';
				}

				$preset['scrollBgColor'] = sanitize_hex_db( $preset['scrollBgColor'] );
				if ( $preset['scrollBgColor'] === false ) {
					$errors[] = 'Hex Code Background Color (Error 721)';
				}

				$preset['scrollBgOpacity'] = sanitize_opacity_db( $preset['scrollBgOpacity'] );
				if ( $preset['scrollBgOpacity'] === false ) {
					$errors[] = 'Scroll Background Opacity (Error 722)';
				}
				if ( count( $errors ) > 0 ) {
					echo json_encode( $errors );
					exit();
				} else {

					$sql_args = array(
						$preset['scrollArrowFill'],
						$preset['scrollArrowOpacity'],
						$preset['scrollArrowBg'],
						$preset['scrollArrowOpacityHover'],
						$preset['scrollArrowBgHover'],
						$preset['scrollArrowBgSize'],
						$preset['scrollArrowBgSizeLg'],
						$preset['scrollArrowBgSizeMd'],
						$preset['scrollArrowBgSizeSm'],
						$preset['scrollArrowSize'],
						$preset['scrollArrowMargin'],
						$preset['scrollArrowMarginLg'],
						$preset['scrollArrowMarginMd'],
						$preset['scrollArrowMarginSm'],
						$preset['scrollArrowTranslate'],
						$preset['scrollBgHeight'],
						$preset['scrollBgHeightLg'],
						$preset['scrollBgHeightMd'],
						$preset['scrollBgHeightSm'],
						$preset['scrollBgColor'],
						$preset['scrollBgOpacity'],
						$preset['uuid'],
						true // `settings_active`
					);
					//var_dump( $sql_args );
					$safe_sql       = $wpdb->prepare( "UPDATE `$table_name`
															SET 
																`arrow_fill` = %s,
																`arrow_opacity` = %d,
																`arrow_bg` = %s,
																`arrow_opacity_hover` = %d,
																`arrow_bg_hover` = %s,
																`arrow_bg_size` = %d,
																`arrow_bg_size_lg` = %d,
																`arrow_bg_size_md` = %d,
																`arrow_bg_size_sm` = %d,
																`arrow_size` = %d,
																`arrow_margin` = %d,
																`arrow_margin_lg` = %d,
																`arrow_margin_md` = %d,
																`arrow_margin_sm` = %d,
																`arrow_translate` = %d,
																`arrow_shadow_height` = %d,
																`arrow_shadow_height_lg` = %d,
																`arrow_shadow_height_md` = %d,
																`arrow_shadow_height_sm` = %d,
																`arrow_shadow_color` = %s,
																`arrow_shadow_opacity` = %d
															WHERE `uuid` = %s AND `settings_active` = %d", $sql_args );
					$updated_preset = $wpdb->query( $safe_sql );
					//var_dump( $safe_sql );
				}
				die;
			}
		}

		function reload_preset_select() {
			global $wpdb;
			$table_name  = $wpdb->prefix . 'lins_scroll_arrow_presets';
			$safe_sql    = $wpdb->prepare( "SELECT `uuid`, `preset_name`
												FROM `$table_name`
												WHERE `settings_active` = %d ORDER BY `database_timestamp` ASC", array( true ) );
			$all_presets = $wpdb->get_results( $safe_sql );
			if ( $all_presets ) {
				echo json_encode( $all_presets );
			}
			exit();
		}

		function reload_preset_select_remove() {
			global $wpdb;
			$table_name  = $wpdb->prefix . 'lins_scroll_arrow_presets';
			$safe_sql    = $wpdb->prepare( "SELECT `uuid`, `preset_name`
												FROM `$table_name`
												WHERE `settings_active` = %d AND NOT `uuid` = %s ORDER BY `database_timestamp` ASC", array( true, BLANK_UUID ) );
			$all_presets = $wpdb->get_results( $safe_sql );
			if ( $all_presets ) {
				echo json_encode( $all_presets );
			}
			exit();
		}



		function load_preset() {
			global $wpdb;
			$preset         = $_POST['ajax_data'];
			$table_name     = $wpdb->prefix . 'lins_scroll_arrow_presets';
			$safe_sql       = $wpdb->prepare( "SELECT *
												FROM `$table_name`
												WHERE `settings_active` = %d AND `uuid` = %s", array( true, $preset['uuid'] ) );
			$loaded_presets = $wpdb->get_results( $safe_sql );

			if ( $loaded_presets ) {
				echo json_encode( $loaded_presets[0] );
			}
			exit();
		}

		function check_preset_name() {
			global $wpdb;
			$preset         = $_POST['ajax_data'];
			$table_name     = $wpdb->prefix . 'lins_scroll_arrow_presets';
			$safe_sql       = $wpdb->prepare( "SELECT `preset_name`
												FROM `$table_name`
												WHERE `settings_active` = %d AND UPPER(`preset_name`) = UPPER(%s)", array( true, ( $preset['newName'] ) ) );
			$loaded_presets = $wpdb->get_results( $safe_sql );
			if ( $loaded_presets ) {
				echo 'Name already exists. (Error 600) ';
			}
			exit();
		}
		function edit_preset_name() {
			global $wpdb;
			$preset = $_POST['ajax_data'];
			if ( $preset['uuid'] !== BLANK_UUID ) {
				$table_name       = $wpdb->prefix . 'lins_scroll_arrow_presets';
				$safe_sql         = $wpdb->prepare( "UPDATE `$table_name`
													SET `preset_name` = %s
													WHERE `uuid` = %s", array( $preset['newName'], $preset['uuid'] ) );
				$existing_presets = $wpdb->query( $safe_sql );
				echo ( $existing_presets );
			}
			exit();
		}

		function remove_preset() {
			global $wpdb;
			$preset = $_POST['ajax_data'];
			if ( $preset['uuid'] !== BLANK_UUID ) {

				$table_name    = $wpdb->prefix . 'lins_scroll_arrow_presets';
				$safe_sql      = $wpdb->prepare( "UPDATE `$table_name`
												SET `settings_active` = %d
												WHERE `uuid` = %s", array( false, $preset['uuid'] ) );
				$remove_preset = $wpdb->query( $safe_sql );
			}
			exit();
		}


		function save_preset() {
			global $wpdb;
			$preset           = $_POST['ajax_data'];
			$table_name       = $wpdb->prefix . 'lins_scroll_arrow_presets';
			$safe_sql         = $wpdb->prepare( "SELECT `preset_name`
												FROM `$table_name`
												WHERE `settings_active` = %d", true );
			$existing_presets = $wpdb->get_results( $safe_sql );

			//echo '<pre>';
			//print_r( $existing_presets );
			//echo '</pre>';

			$errors = array();

			$preset['presetName'] = sanitize_text_field( $preset['presetName'] );
			$lower_case_preset    = strtolower( $preset['presetName'] );
			foreach ( $existing_presets as $curr_preset ) {
				$curr_lower_case_preset = strtolower( $curr_preset->preset_name );
				if ( $curr_lower_case_preset === $lower_case_preset ) {
					$errors[] = 'Preset Name already exists (Error 100)';
				}
			}
			if ( ! $preset['presetName'] ) {
				$errors[] = 'Preset Name is empty (Error 101)';
			}

			//if $preset['presetName'] exist (fetch active presets), give error and check if it is not an empty string
			$preset['scrollArrowFill'] = sanitize_hex_db( $preset['scrollArrowFill'] );
			if ( $preset['scrollArrowFill'] === false ) {
				$errors[] = 'Hex Code Arrow Fill Wrong (Error 102)';
			}

			$preset['scrollArrowOpacity'] = sanitize_opacity_db( $preset['scrollArrowOpacity'] );
			if ( $preset['scrollArrowOpacity'] === false ) {
				$errors[] = 'Scroll Arrow Opacity Wrong (Error 103)';
			}

			$preset['scrollArrowBg'] = sanitize_hex_db( $preset['scrollArrowBg'] );
			if ( $preset['scrollArrowBg'] === false ) {
				$errors[] = 'Hex Code Arrow Background Wrong (Error 104)';
			}

			$preset['scrollArrowOpacityHover'] = sanitize_opacity_db( $preset['scrollArrowOpacityHover'] );
			if ( $preset['scrollArrowOpacityHover'] === false ) {
				$errors[] = 'Scroll Arrow Opacity Hover Wrong (Error 105)';
			}

			$preset['scrollArrowBgHover'] = sanitize_hex_db( $preset['scrollArrowBgHover'] );
			if ( $preset['scrollArrowBgHover'] === false ) {
				$errors[] = 'Hex Code Arrow Background Hover Wrong (Error 106)';

			}

			if ( ! is_int( (int) $preset['scrollArrowBgSize'] ) ) {
				$errors[] = 'Arrow Background Size Wrong (Error 107)';
			}

			if ( ! is_int( (int) $preset['scrollArrowBgSizeLg'] ) ) {
				$errors[] = 'Arrow Background Size LG Wrong (Error 108)';

			}

			if ( ! is_int( (int) $preset['scrollArrowBgSizeMd'] ) ) {
				$errors[] = 'Arrow Background Size MD Wrong (Error 109)';
			}

			if ( ! is_int( (int) $preset['scrollArrowBgSizeSm'] ) ) {
				$errors[] = 'Arrow Background Size SM Wrong (Error 110)';
			}
			if ( ! is_int( (int) $preset['scrollArrowSize'] ) || (int) $preset['scrollArrowSize'] > 100 ) {
				$errors[] = 'Arrow Size Wrong (Error 111)';
			}

			if ( ! is_int( (int) $preset['scrollArrowMargin'] ) || $preset['scrollArrowMargin'] < 0 ) {
				$errors[] = 'Arrow Margin Wrong (Error 112)';
			}

			if ( ! is_int( (int) $preset['scrollArrowMarginLg'] ) || $preset['scrollArrowMarginLg'] < 0 ) {
				$errors[] = 'Arrow Margin LG Wrong (Error 113)';
			}

			if ( ! is_int( (int) $preset['scrollArrowMarginMd'] ) || $preset['scrollArrowMarginMd'] < 0 ) {
				$errors[] = 'Arrow Margin MD Wrong (Error 114)';
			}

			if ( ! is_int( (int) $preset['scrollArrowMarginSm'] ) || $preset['scrollArrowMarginSm'] < 0 ) {
				$errors[] = 'Arrow Margin SM Wrong (Error 115)';
			}

			if ( ! is_int( (int) $preset['scrollArrowTranslate'] ) || $preset['scrollArrowTranslate'] < 0 ) {
				$errors[] = 'Arrow Translate Wrong (Error 116)';
			}

			if ( ! is_int( (int) $preset['scrollBgHeight'] ) || $preset['scrollBgHeight'] < 0 ) {
				$errors[] = 'Background Height Wrong (Error 117)';
			}

			if ( ! is_int( (int) $preset['scrollBgHeightLg'] ) || $preset['scrollBgHeightLg'] < 0 ) {
				$errors[] = 'Background Height LG Wrong (Error 118)';
			}

			if ( ! is_int( (int) $preset['scrollBgHeightMd'] ) || $preset['scrollBgHeightMd'] < 0 ) {
				$errors[] = 'Background Height MD Wrong (Error 119)';
			}

			if ( ! is_int( (int) $preset['scrollBgHeightSm'] ) || $preset['scrollBgHeightSm'] < 0 ) {
				$errors[] = 'Background Height SM Wrong (Error 120)';
			}

			$preset['scrollBgColor'] = sanitize_hex_db( $preset['scrollBgColor'] );
			if ( $preset['scrollBgColor'] === false ) {
				$errors[] = 'Hex Code Background Color (Error 121)';
			}

			$preset['scrollBgOpacity'] = sanitize_opacity_db( $preset['scrollBgOpacity'] );
			if ( $preset['scrollBgOpacity'] === false ) {
				$errors[] = 'Scroll Background Opacity (Error 122)';
			}

			if ( count( $errors ) > 0 ) {
				echo json_encode( $errors );
				exit();
			} else {
				$uuid    = UUID::v4();
				$query   = $wpdb->prepare( "SELECT `uuid` FROM `$table_name` WHERE `uuid` = %s", $uuid );
				$results = $wpdb->get_results( $query );
				while ( $results ) {
					$uuid    = UUID::v4();
					$query   = $wpdb->prepare( "SELECT `uuid` FROM `$table_name` WHERE `uuid` = %s", $uuid );
					$results = $wpdb->get_results( $query );
				}

				$form_data = array(
					'uuid'                   => UUID::v4(),
					'preset_name'            => $preset['presetName'],
					'arrow_fill'             => $preset['scrollArrowFill'],
					'arrow_opacity'          => $preset['scrollArrowOpacity'],
					'arrow_bg'               => $preset['scrollArrowBg'],
					'arrow_opacity_hover'    => $preset['scrollArrowOpacityHover'],
					'arrow_bg_hover'         => $preset['scrollArrowBgHover'],
					'arrow_bg_size'          => $preset['scrollArrowBgSize'],
					'arrow_bg_size_lg'       => $preset['scrollArrowBgSizeLg'],
					'arrow_bg_size_md'       => $preset['scrollArrowBgSizeMd'],
					'arrow_bg_size_sm'       => $preset['scrollArrowBgSizeSm'],
					'arrow_size'             => $preset['scrollArrowSize'],
					'arrow_margin'           => $preset['scrollArrowMargin'],
					'arrow_margin_lg'        => $preset['scrollArrowMarginLg'],
					'arrow_margin_md'        => $preset['scrollArrowMarginMd'],
					'arrow_margin_sm'        => $preset['scrollArrowMarginSm'],
					'arrow_translate'        => $preset['scrollArrowTranslate'],
					'arrow_shadow_height'    => $preset['scrollBgHeight'],
					'arrow_shadow_height_lg' => $preset['scrollBgHeightLg'],
					'arrow_shadow_height_md' => $preset['scrollBgHeightMd'],
					'arrow_shadow_height_sm' => $preset['scrollBgHeightSm'],
					'arrow_shadow_color'     => $preset['scrollBgColor'],
					'arrow_shadow_opacity'   => $preset['scrollBgOpacity'],
					'database_timestamp'     => current_time( 'mysql' )
				);
				//var_dump( $preset );
				$table_name = $wpdb->prefix . 'lins_scroll_arrow_presets';
				$wpdb->insert( $table_name, $form_data );
			}
		}


		function add_admin_js() {
			if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] === 'lins-scroll-to-top-settings' ) {

				$plugin_url = plugin_dir_url( __FILE__ );

				wp_enqueue_script( 'add_admin_js',
					$plugin_url . 'script/admin.js',
					array(),
					'1.0.0',
					array(
						'strategy' => 'defer',
					)
				);
			}
		}

		add_action( 'admin_enqueue_scripts', 'add_admin_js' );

		function add_admin_js_modal() {
			if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] === 'lins-scroll-to-top-settings' ) {

				$plugin_url = plugin_dir_url( __FILE__ );

				wp_enqueue_script( 'add_admin_js_modal',
					$plugin_url . 'script/modalbox.js',
					array(),
					'1.0.0',
					array(
						'strategy' => 'defer',
					)
				);
			}
		}

		add_action( 'admin_enqueue_scripts', 'add_admin_js_modal' );

		function add_admin_css() {
			if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] === 'lins-scroll-to-top-settings' ) {
				$plugin_url = plugin_dir_url( __FILE__ );
				wp_register_style( 'custom_wp_admin_css', $plugin_url . 'style/admin.min.css', false, '1.0.0' );
				wp_enqueue_style( 'style', $plugin_url . 'style/admin.min.css' );
			}
		}

		add_action( 'admin_enqueue_scripts', 'add_admin_css' );


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

			$arrow_fill = get_option( 'lins_scroll_arrow_fill', ARROW_FILL_DEF );

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
			$custom_css .= ".scroll-arrow svg { fill: {$arrow_fill}; }";
			wp_add_inline_style( 'rt-customstyle', $custom_css );
		}
		add_action( 'wp_enqueue_scripts', 'rt_custom_enqueue' );


		function create_the_custom_table() {
			global $wpdb;

			$charset_collate = $wpdb->get_charset_collate();

			$table_name = $wpdb->prefix . 'lins_scroll_arrow_presets';

			$sql = "CREATE TABLE $table_name (
						uuid VARCHAR(36) NOT NULL UNIQUE,
						preset_name VARCHAR(255) NOT NULL,
						arrow_fill VARCHAR(6) NOT NULL,
						arrow_opacity DOUBLE(255, 2) NOT NULL,
						arrow_bg VARCHAR(6) NOT NULL,
						arrow_opacity_hover DOUBLE(255, 2) NOT NULL,
						arrow_bg_hover VARCHAR(6) NOT NULL,
						arrow_bg_size INT NOT NULL,
						arrow_bg_size_lg INT NOT NULL,
						arrow_bg_size_md INT NOT NULL,
						arrow_bg_size_sm INT NOT NULL,
						arrow_size  INT NOT NULL,
						arrow_margin INT NOT NULL,
						arrow_margin_lg INT NOT NULL,
						arrow_margin_md INT NOT NULL,
						arrow_margin_sm INT NOT NULL,
						arrow_translate INT NOT NULL,
						arrow_shadow_height INT NOT NULL,
						arrow_shadow_height_lg INT NOT NULL,	
						arrow_shadow_height_md INT NOT NULL,	
						arrow_shadow_height_sm INT NOT NULL,
						arrow_shadow_color VARCHAR(6) NOT NULL,
						arrow_shadow_opacity DOUBLE(255, 2) NOT NULL,
						database_timestamp DATETIME NOT NULL,
						settings_active BOOLEAN DEFAULT TRUE NOT NULL,
						PRIMARY KEY  (uuid),
						CHECK(arrow_opacity >= 0 AND arrow_opacity <= 1),
						CHECK(arrow_opacity_hover >= 0 AND arrow_opacity_hover <= 1),
						CHECK(arrow_bg_size >= 0),
						CHECK(arrow_bg_size_lg >= 0),
						CHECK(arrow_bg_size_md >= 0),
						CHECK(arrow_bg_size_sm >= 0),
						CHECK(arrow_size >= 0 AND arrow_size <= 100),
						CHECK(arrow_margin >= 0),
						CHECK(arrow_margin_lg >= 0),
						CHECK(arrow_margin_md >= 0),
						CHECK(arrow_margin_sm >= 0),
						CHECK(arrow_translate >= 0),
						CHECK(arrow_shadow_height >= 0),
						CHECK(arrow_shadow_height_lg >= 0),
						CHECK(arrow_shadow_height_md >= 0),
						CHECK(arrow_shadow_height_sm >= 0),
						CHECK(arrow_shadow_opacity >= 0 AND arrow_shadow_opacity <= 1)
					) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			$form_data = array(
				'uuid'                   => BLANK_UUID,
				'preset_name'            => 'Default Preset',
				'arrow_fill'             => ltrim( ARROW_FILL_DEF, '#' ),
				'arrow_opacity'          => ARROW_OPACITY_DEF,
				'arrow_bg'               => ltrim( ARROW_COLOR_DEF, '#' ),
				'arrow_opacity_hover'    => ARROW_OPACITY_HOVER_DEF,
				'arrow_bg_hover'         => ltrim( ARROW_COLOR_HOVER_DEF, '#' ),
				'arrow_bg_size'          => BG_SIZE_DEF,
				'arrow_bg_size_lg'       => BG_SIZE_LG_DEF,
				'arrow_bg_size_md'       => BG_SIZE_MD_DEF,
				'arrow_bg_size_sm'       => BG_SIZE_SM_DEF,
				'arrow_size'             => ARROW_SIZE_DEF,
				'arrow_margin'           => ARROW_MARGIN_DEF,
				'arrow_margin_lg'        => ARROW_MARGIN_LG_DEF,
				'arrow_margin_md'        => ARROW_MARGIN_MD_DEF,
				'arrow_margin_sm'        => ARROW_MARGIN_SM_DEF,
				'arrow_translate'        => ARROW_TRANSLATE_DEF,
				'arrow_shadow_height'    => BG_HEIGHT_DEF,
				'arrow_shadow_height_lg' => BG_HEIGHT_LG_DEF,
				'arrow_shadow_height_md' => BG_HEIGHT_MD_DEF,
				'arrow_shadow_height_sm' => BG_HEIGHT_SM_DEF,
				'arrow_shadow_color'     => ltrim( BG_COLOR_DEF, '#' ),
				'arrow_shadow_opacity'   => BG_OPACITY_DEF,
				'database_timestamp'     => '2000-01-01 00:00:00.000'
			);

			//var_dump( $preset );
			$table_name = $wpdb->prefix . 'lins_scroll_arrow_presets';
			$wpdb->insert( $table_name, $form_data );
		}

		register_activation_hook( __FILE__, 'create_the_custom_table' );
	}

	function settings() {
		$scrollplugin_01_title = __( 'Arrow Color Fill', 'scrollplugin_01_title' );
		$scrollplugin_02_title = __( 'Arrow Opacity<br>(min. 0, max. 1)', 'scrollplugin_02_title' );
		$scrollplugin_03_title = __( 'Arrow Background Color', 'scrollplugin_03_title' );
		$scrollplugin_04_title = __( 'Arrow Opacity Hover<br>(min. 0, max. 1)', 'scrollplugin_04_title' );
		$scrollplugin_05_title = __( 'Arrow Background Color Hover', 'scrollplugin_05_title' );
		$scrollplugin_06_title = __( 'Arrow Background Size', 'scrollplugin_06_title' );
		$scrollplugin_07_title = __( 'Arrow Background Size (screen <= ' . BP_LG . 'px)', 'scrollplugin_07_title' );
		$scrollplugin_08_title = __( 'Arrow Background Size (screen <= ' . BP_MD . 'px)', 'scrollplugin_08_title' );
		$scrollplugin_09_title = __( 'Arrow Background Size (screen <= ' . BP_SM . 'px)', 'scrollplugin_09_title' );
		$scrollplugin_10_title = __( 'Arrow Size (% of Arrow Background Size)', 'scrollplugin_10_title' );
		$scrollplugin_11_title = __( 'Arrow Margin from Screen', 'scrollplugin_11_title' );
		$scrollplugin_12_title = __( 'Arrow Margin from Screen (screen <= ' . BP_LG . 'px)', 'scrollplugin_12_title' );
		$scrollplugin_13_title = __( 'Arrow Margin from Screen (screen <= ' . BP_MD . 'px)', 'scrollplugin_13_title' );
		$scrollplugin_14_title = __( 'Arrow Margin from Screen (screen <= ' . BP_SM . 'px)', 'scrollplugin_14_title' );
		$scrollplugin_15_title = __( 'Arrow Translate Height (moving up when hovering)', 'scrollplugin_15_title' );
		$scrollplugin_16_title = __( 'Background Shadow Height (showing up when hovering)', 'scrollplugin_16_title' );
		$scrollplugin_17_title = __( 'Background Shadow Height (screen <= ' . BP_LG . 'px)', 'scrollplugin_17_title' );
		$scrollplugin_18_title = __( 'Background Shadow Height (screen <= ' . BP_MD . 'px)', 'scrollplugin_18_title' );
		$scrollplugin_19_title = __( 'Background Shadow Height (screen <= ' . BP_SM . 'px)', 'scrollplugin_19_title' );
		$scrollplugin_20_title = __( 'Background Shadow Color (showing up when hovering)', 'scrollplugin_20_title' );
		$scrollplugin_21_title = __( 'Background Shadow Opacity (showing up when hovering)', 'scrollplugin_21_title' );

		add_settings_section( 'scrollplugin_01', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_fill', $scrollplugin_01_title, array( $this, 'fill_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_01' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_fill', array( 'sanitize_callback' => array( $this, 'sanitize_fill' ), 'default' => ARROW_FILL_DEF ) );

		add_settings_section( 'scrollplugin_02', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_opacity', $scrollplugin_02_title, array( $this, 'opacity_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_02' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_opacity', array( 'sanitize_callback' => array( $this, 'sanitize_opacity' ), 'default' => ARROW_OPACITY_DEF ) );

		add_settings_section( 'scrollplugin_03', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_color', $scrollplugin_03_title, array( $this, 'color_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_03' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_color', array( 'sanitize_callback' => array( $this, 'sanitize_color' ), 'default' => ARROW_COLOR_DEF ) );

		add_settings_section( 'scrollplugin_04', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_opacity_hover', $scrollplugin_04_title, array( $this, 'opacity_hover_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_04' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_opacity_hover', array( 'sanitize_callback' => array( $this, 'sanitize_opacity_hover' ), 'default' => ARROW_OPACITY_HOVER_DEF ) );

		add_settings_section( 'scrollplugin_05', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_color', $scrollplugin_05_title, array( $this, 'color_hover_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_05' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_color_hover', array( 'sanitize_callback' => array( $this, 'sanitize_color_hover' ), 'default' => ARROW_COLOR_HOVER_DEF ) );

		add_settings_section( 'scrollplugin_06', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_size', $scrollplugin_06_title, array( $this, 'bg_size_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_06' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_size', array( 'sanitize_callback' => array( $this, 'sanitize_size' ), 'default' => BG_SIZE_DEF ) );

		add_settings_section( 'scrollplugin_07', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_size_lg', $scrollplugin_07_title, array( $this, 'bg_size_lg_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_07' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_size_lg', array( 'sanitize_callback' => array( $this, 'sanitize_size_lg' ), 'default' => BG_SIZE_LG_DEF ) );

		add_settings_section( 'scrollplugin_08', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_size_md', $scrollplugin_08_title, array( $this, 'bg_size_md_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_08' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_size_md', array( 'sanitize_callback' => array( $this, 'sanitize_size_md' ), 'default' => BG_SIZE_MD_DEF ) );

		add_settings_section( 'scrollplugin_09', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_size_sm', $scrollplugin_09_title, array( $this, 'bg_size_sm_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_09' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_size_sm', array( 'sanitize_callback' => array( $this, 'sanitize_size_sm' ), 'default' => BG_SIZE_SM_DEF ) );

		add_settings_section( 'scrollplugin_10', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_size', $scrollplugin_10_title, array( $this, 'arrow_size_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_10' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_size', array( 'sanitize_callback' => array( $this, 'sanitize_size_min_max' ), 'default' => ARROW_SIZE_DEF ) );

		add_settings_section( 'scrollplugin_11', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_margin', $scrollplugin_11_title, array( $this, 'arrow_margin_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_11' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_margin', array( 'sanitize_callback' => array( $this, 'sanitize_margin' ), 'default' => ARROW_MARGIN_DEF ) );

		add_settings_section( 'scrollplugin_12', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_margin_lg', $scrollplugin_12_title, array( $this, 'arrow_margin_lg_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_12' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_margin_lg', array( 'sanitize_callback' => array( $this, 'sanitize_margin_lg' ), 'default' => ARROW_MARGIN_LG_DEF ) );

		add_settings_section( 'scrollplugin_13', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_margin_md', $scrollplugin_13_title, array( $this, 'arrow_margin_md_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_13' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_margin_md', array( 'sanitize_callback' => array( $this, 'sanitize_margin_md' ), 'default' => ARROW_MARGIN_MD_DEF ) );

		add_settings_section( 'scrollplugin_14', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_margin_sm', $scrollplugin_14_title, array( $this, 'arrow_margin_sm_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_14' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_margin_sm', array( 'sanitize_callback' => array( $this, 'sanitize_margin_sm' ), 'default' => ARROW_MARGIN_SM_DEF ) );

		add_settings_section( 'scrollplugin_15', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_arrow_translate', $scrollplugin_15_title, array( $this, 'arrow_translate_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_15' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_arrow_translate', array( 'sanitize_callback' => array( $this, 'sanitize_translate' ), 'default' => ARROW_TRANSLATE_DEF ) );

		add_settings_section( 'scrollplugin_16', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_height', $scrollplugin_16_title, array( $this, 'bg_height_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_16' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_height', array( 'sanitize_callback' => array( $this, 'sanitize_bg_height' ), 'default' => BG_HEIGHT_DEF ) );

		add_settings_section( 'scrollplugin_17', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_height_lg', $scrollplugin_17_title, array( $this, 'bg_height_lg_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_17' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_height_lg', array( 'sanitize_callback' => array( $this, 'sanitize_bg_height_lg' ), 'default' => BG_HEIGHT_LG_DEF ) );

		add_settings_section( 'scrollplugin_18', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_height_md', $scrollplugin_18_title, array( $this, 'bg_height_md_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_18' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_height_md', array( 'sanitize_callback' => array( $this, 'sanitize_bg_height_md' ), 'default' => BG_HEIGHT_MD_DEF ) );

		add_settings_section( 'scrollplugin_19', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_height_sm', $scrollplugin_19_title, array( $this, 'bg_height_sm_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_19' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_height_sm', array( 'sanitize_callback' => array( $this, 'sanitize_bg_height_sm' ), 'default' => BG_HEIGHT_SM_DEF ) );

		add_settings_section( 'scrollplugin_20', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_color', $scrollplugin_20_title, array( $this, 'bg_color_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_20' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_color', array( 'sanitize_callback' => array( $this, 'sanitize_bg_color' ), 'default' => BG_COLOR_DEF ) );

		add_settings_section( 'scrollplugin_21', null, null, 'lins-scroll-to-top-settings' );
		add_settings_field( 'lins_scroll_bg_opacity', $scrollplugin_21_title, array( $this, 'bg_opacity_html' ), 'lins-scroll-to-top-settings', 'scrollplugin_21' );
		register_setting( 'lins_scroll_to_top_plugin', 'lins_scroll_bg_opacity', array( 'sanitize_callback' => array( $this, 'sanitize_bg_opacity' ), 'default' => BG_OPACITY_DEF ) );
	}

	// Sanitzing
	function sanitize_min_max( $field_name, $input, $min, $max ) {
		if ( ! is_numeric( $input ) ) {
			$sanitize_min_max_num_error = __( "Input value of {$field_name} is not a number.", 'sanitize_min_max_num_error' );
			add_settings_error( $field_name, "{$field_name}_number_error", $sanitize_min_max_num_error );
			return false;
		}
		if ( ! is_numeric( $min ) || ! is_numeric( $max ) ) {
			$sanitize_min_max_num_error2 = __( 'Input value of the set minimum or maximum is not a number! The error is not caused by the input but by set maximum and minimum values within the plugin.', 'sanitize_min_max_num_error2' );
			add_settings_error( $field_name, "{$field_name}_min_max_number_error", $sanitize_min_max_num_error2 );
			return false;
		}
		if ( $input < $min or $input > $max ) {
			$sanitize_min_max_val_error = __( "Input value of {$field_name} is either below the minimum or above the maximum value.", 'sanitize_min_max_val_error' );
			add_settings_error( $field_name, "{$field_name}_min_error", $sanitize_min_max_val_error );
			return false;
		}
		return true;
	}

	function sanitize_min( $field_name, $input, $min ) {
		if ( ! is_numeric( $input ) ) {
			$sanitize_min_num_error = __( "Input value of {$field_name} is not a number.", 'sanitize_min_num_error' );
			add_settings_error( $field_name, "{$field_name}_number_error", $sanitize_min_num_error );
			return false;
		}
		if ( ! is_numeric( $min ) ) {
			$sanitize_min_num_error2 = __( 'Input value of the set minimum is not a number! The error is not caused by the input but by a set minimum value within the plugin.', 'sanitize_min_num_error2' );
			add_settings_error( $field_name, "{$field_name}_min_number_error", $sanitize_min_num_error2 );
			return false;
		}
		if ( $input < $min ) {
			$sanitize_min_val_error = __( "Input value of {$field_name} is below the minimum value", 'sanitize_min_val_error' );
			add_settings_error( $field_name, "{$field_name}_min_error", );
			return false;
		}
		return true;
	}

	function sanitize_hex( $field_name, $input ) {
		$input    = strtoupper( $input );
		$rest     = substr( $input, 1, strlen( $input ) );
		$hash_key = substr( $input, 0, 1 );

		if ( $hash_key === '#' && strlen( $input ) === 7 && ctype_xdigit( $rest ) ) {
			return true;
		} else {
			$sanitize_hex_error = __( "{$field_name}_invalid_hex_code", "Input value of {$field_name} is not a valid HEX code (for example: #FF0000).", 'sanitize_hex_error' );
			add_settings_error( $field_name, $sanitize_hex_error );
			return false;
		}
	}
	// Settings sanitizing
	function sanitize_fill( $input ) {
		$field_name = 'lins_scroll_arrow_fill';
		$sanitize   = Lins_Scroll_To_Top::sanitize_hex( $field_name, $input );
		if ( $sanitize === false ) {
			return get_option( $field_name );
		} else {
			return $input;
		}
	}
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
		$sanitize   = Lins_Scroll_To_Top::sanitize_hex( $field_name, $input );
		if ( $sanitize === false ) {
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
		$sanitize   = Lins_Scroll_To_Top::sanitize_hex( $field_name, $input );
		if ( $sanitize === false ) {
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
		$sanitize   = Lins_Scroll_To_Top::sanitize_hex( $field_name, $input );
		if ( $sanitize === false ) {
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
	function fill_html() {
		?>
		<input type="text" name="lins_scroll_arrow_fill"
			value="<?php echo esc_attr( get_option( 'lins_scroll_arrow_fill' ) ); ?>" class="my-color-field" />
		<?php
	}
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
		$scrollplugin_main_title = __( 'Scroll Top Settings', 'scrollplugin_main_title' );
		add_options_page( $scrollplugin_main_title, $scrollplugin_main_title, 'manage_options', 'lins-scroll-to-top-settings', array( $this, 'return_html' ) );
	}

	function return_html() {
		?>
		<?php
		$scrollplugin_settings_title = __( 'Scroll To Top Arrow Global Settings (by Fabian Lins)', 'scrollplugin_settings_title' );
		global $wpdb;
		$table_name = $wpdb->prefix . 'lins_scroll_arrow_presets';
		//$sql        = "SELECT uuid, preset_name FROM $table_name WHERE settings_active = true ORDER BY database_timestamp ASC";
		$query   = $wpdb->prepare( "SELECT `uuid`, `preset_name` FROM `$table_name` WHERE `settings_active` = %d ORDER BY `database_timestamp` ASC", true );
		$results = $wpdb->get_results( $query );

		?>
		<div class="wrap">
			<h1>
				<?php
				echo esc_attr( $scrollplugin_settings_title );
				?>
			</h1>

			<div class="edit-name-modal">
				<div class="modal-content">
					<h2>Edit Preset Name</h2>
					<h3 class="old-preset-name">Old Preset Name</h3>
					<div class="form-combo">
						<label for="lins-scroll-preset-edit">New Preset Name</label>
						<input type="text" name="lins_scroll_preset_edit" id="lins-scroll-preset-edit">
						<button class="button button-primary" onclick="linsScrollEditPreset()">Save Preset</button>
						<div class="button button-secondary js-close-modal-btn" tabindex="0"
							onclick="linsScrollTopCloseModal()">
							Cancel</div>
					</div>
				</div>
				<div class="modal-bg" onclick="linsScrollTopCloseModal()">
				</div>
			</div>

			<div class="confirm-remove-modal">
				<div class="modal-content">
					<h2>Confirm Preset Removal</h2>
					<div class="form-combo">
						<div class="button-container">
							<p class="current-preset-modal">Remove Preset: <span></span></p>
							<p>Do you really want remove the preset?</p>
							<button class="button button-danger" onclick="linsScrollRemoveConfirm()">Confirm Removal</button>
							<div class="button button-secondary js-close-modal-btn-confirm" tabindex="0"
								onclick="linsScrollTopRemoveConfirmCloseModal()">
								Cancel</div>
						</div>
					</div>
				</div>
				<div class="modal-bg" onclick="linsScrollTopRemoveConfirmCloseModal()">
				</div>
			</div>

			<div class="remove-modal">
				<div class="modal-content">
					<div class="form-maxw">

						<h2>Remove Preset</h2>
						<div class="form-combo">
							<?php
							if ( count( $results ) > 0 ) {
								echo '<select name="remove_preset" id="remove-preset">';
								foreach ( $results as $curr_preset ) {
									$value = $curr_preset->uuid;
									if ( $value !== BLANK_UUID ) {
										$name = $curr_preset->preset_name;
										echo ( "<option value='{$value}'>{$name}</option>" );
									}
								}
								echo '</select>';
							}
							?>
							<div class="button-container">
								<button class="button button-danger" onclick="linsScrollRemovePreset()">Remove Preset</button>
								<div class="button button-secondary js-close-modal-btn" tabindex="0"
									onclick="linsScrollTopCloseModal()">
									Cancel</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-bg" onclick="linsScrollTopCloseModal()">
				</div>
			</div>

			<div class="name-modal">
				<div class="modal-content">
					<h2>Save Preset</h2>
					<div class="form-combo">
						<label for="lins-scroll-preset-name">Preset Name</label>
						<input type="text" name="lins_scroll_preset_name" id="lins-scroll-preset-name">
						<button class="button button-primary" onclick="linsScrollTopSavePreset()">Save Preset</button>
						<div class="button button-secondary js-close-modal-btn" tabindex="0"
							onclick="linsScrollTopCloseModal()">
							Cancel</div>
					</div>
				</div>
				<div class="modal-bg" onclick="linsScrollTopCloseModal()">
				</div>
			</div>

			<div class="alert-boxes">

			</div>

			<h3>Presets</h3>
			<div>
				<?php
				if ( count( $results ) > 0 ) {
					echo '<select name="select_preset" id="select-preset">';
					foreach ( $results as $curr_preset ) {
						$value = $curr_preset->uuid;
						$name  = $curr_preset->preset_name;
						echo ( "<option value='{$value}'>{$name}</option>" );
					}
					echo '</select>';
				}
				?>
			</div>
			<br>
			<div>
				<button class="button button-primary load-preset-btn" onclick="linsScrollLoadPreset()">Load Preset</button>
				<button class="button button-secondary edit-preset-btn" onclick="linsScrollTopEditAlert()"
					style="display:none;">Edit Preset Name</button>
				<button class="button button-secondary update-preset-btn" onclick="linsScrollUpdatePreset()" style="
					display:none;">Update
					Preset</button>

				<button class="button button-danger-outline remove-preset-btn" onclick="linsScrollRemoveAlert()">Remove
					Preset</button>
			</div>
			<div class="current-preset">
				<h2>Loaded preset: <span class="preset-name">No preset selected.</span></h2>
			</div>
			<form action="options.php" method="POST">
				<?php
				settings_fields( 'lins_scroll_to_top_plugin' );
				do_settings_sections( 'lins-scroll-to-top-settings' );
				submit_button();

				?>
				<div class="button button-secondary" onclick="linsScrollTopShowModal()">Save As Preset</div>

			</form>

		</div>
		<?php
	}
}

$lins_scroll_to_top_plugin = new Lins_Scroll_To_Top();