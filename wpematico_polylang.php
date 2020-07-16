<?php
/*
  Plugin Name: WPeMatico Polylang
  Version: 1.0
  Plugin URI: https://etruel.com/downloads/wpematico_polylang
  Description: WPeMatico auto publishing posts support for Polylang.
  Author: etruel
  Author URI: https://www.netmdp.com
  Contributors: khaztiel
  ----

  Copyright 2020 Esteban Truelsegaard

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// don't load directly
if(!defined('ABSPATH')) {
	die('-1');
}
define('WPEMATICO_POLYLANG_VERSION', '1.0');
define('WPEMATICO_POLYLANG_MIN_PHP_VERSION', '5.6');

if(!class_exists('WPeMatico_polylang')) {

	/**
	 * Main Boilerplate class
	 *
	 * @since       1.0.0
	 */
	class WPeMatico_polylang {

		/**
		 * @var         Boilerplate $instance The one true Boilerplate
		 * @since       1.0.0
		 */
		private static $instance;

		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      object self::$instance The one true Boilerplate
		 */
		public static function instance() {
			if(!self::$instance) {
				self::$instance = new self();
				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->is_compatible();
				self::$instance->load_textdomain();
				if(!self::$instance->is_allowed()) return;
				self::$instance->hooks();
			}

			return self::$instance;
		}

		/**
		 * Setup plugin constants
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public static function setup_constants() {
			// Plugin URL and PATH
			define('WPEMATICO_POLYLANG_URL', plugin_dir_url(__FILE__));
			define('WPEMATICO_POLYLANG_DIR', plugin_dir_path(__FILE__));
			define('WPEMATICO_POLYLANG_MAIN_FILE_DIR', __FILE__);
			define('WPEMATICO_POLYLANG_PLUGIN_DIRNAME', basename(rtrim(dirname(__FILE__), '/')));
			if(!defined('WPEMATICO_POLYLANG_STORE_URL')) {
				define('WPEMATICO_POLYLANG_STORE_URL', 'https://etruel.com');
			}
			if(!defined('WPEMATICO_POLYLANG_ITEM_NAME')) {
				define('WPEMATICO_POLYLANG_ITEM_NAME', 'WPeMatico Polylang');
			}

		}

		/**
		 * Include necessary files
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public static function includes() {
			// Include scripts
//			require_once WPEMATICO_POLYLANG_DIR . 'includes/functions.php';
			/** Compatibilities requirements*/
			require_once WPEMATICO_POLYLANG_DIR . 'includes/compat.php';
			/** Technical requirements */
			require_once WPEMATICO_POLYLANG_DIR . 'includes/requirements.php';
			/**  */
			require_once WPEMATICO_POLYLANG_DIR . 'includes/campaign_edit.php';
			/**  */
			require_once WPEMATICO_POLYLANG_DIR . 'includes/campaign_fetch.php'; 
		}

		/**
		 * Check PHP compatinility and deactivate plugin if fail
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		public static function is_compatible() {
			// Check PHP min version
			if(version_compare(PHP_VERSION, WPEMATICO_POLYLANG_MIN_PHP_VERSION, '<')) {
				// possibly display a notice, trigger error
				add_action('admin_init', ['Compatibility', 'admin_init'] );

				// stop execution of this file
				return false;
			}
			
		}
		/**
		 * Check PHP compatinility and deactivate plugin if fail
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		public static function is_allowed() {
			$requirements = new Requirements;
			if(!$requirements->check()) {
				return false;
			}
			return true;
		}
		
		/**
		 * Run action and filter hooks
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 *
		 */
		public static function hooks() {
			// Register settings
//			add_action('wpematico_cronjob', array(__CLASS__, 'settings_cronjob'));
//			/add_filter('wpematico_before_get_content', 'wpematico_polylang_aux_curl', 10, 3);
		}

		/**
		 * Internationalization
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public static function load_textdomain() {
			// Set filter for language directory
			$lang_dir	 = WPEMATICO_POLYLANG_DIR . '/languages/';
			$lang_dir	 = apply_filters('wpematico_polylang_languages_directory', $lang_dir);

			// Traditional WordPress plugin locale filter
			$locale	 = apply_filters('plugin_locale', get_locale(), 'wpematico_polylang');
			$mofile	 = sprintf('%1$s-%2$s.mo', 'wpematico_polylang', $locale);

			// Setup paths to current locale file
			$mofile_local	 = $lang_dir . $mofile;
			$mofile_global	 = WP_LANG_DIR . '/wpematico_polylang/' . $mofile;

			if(file_exists($mofile_global)) {
				// Look in global /wp-content/languages/wpematico_polylang/ folder
				load_textdomain('wpematico_polylang', $mofile_global);
			}elseif(file_exists($mofile_local)) {
				// Look in local /wp-content/plugins/wpematico_polylang/languages/ folder
				load_textdomain('wpematico_polylang', $mofile_local);
			}else {
				// Load the default language files
				load_plugin_textdomain('wpematico_polylang', false, $lang_dir);
			}
		}

	}

}


add_action('plugins_loaded', 'wpematico_polylang_load', 100);

function wpematico_polylang_load() {
//	if(is_admin()) {
	WPeMatico_polylang::instance();
//	}
}
