<?php

class Requirements {

	/**
	 * All about requirements checks
	 *
	 * @return bool
	 */
	public function check() {
		if(!class_exists('WPeMatico') || !function_exists('pll_current_language')) {
			$this->display_error(__('WPeMatico and Polylang are required plugins.', 'wpematico_polylang'));
			return false;
		}

		if('2.5' > WPEMATICO_VERSION ) {
			$this->display_error(__('WPeMatico should be on version 2.5 or above.', 'wpematico_polylang'));
			return false;
		};
		return true;
	}

	// Display message and handle errors
	public function display_error($message) {
//		trigger_error($message);

		add_action('admin_notices', function () use ($message) {
			printf('<div class="notice error is-dismissible"><p>%s</p></div>', $message);
		});

		// Deactive self
		add_action('admin_init', function () {
			deactivate_plugins(WPEMATICO_POLYLANG_MAIN_FILE_DIR);
			unset($_GET['activate']);
		});
	}

}
