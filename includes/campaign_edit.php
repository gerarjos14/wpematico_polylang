<?php
/**
 * It will be used to manage all feature on the campaign editing.
 * @package     WPeMatico Polylang
 * @subpackage  Campaign edit.
 * @since       1.0
 */
if(!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}
/**
 * Campaign Edit Class 
 * @since 1.0
 */
if(!class_exists('WPePLL_Campaign_Edit')) :

	class WPePLL_Campaign_Edit {

		public static $options	 = null;
		public static $cfg_core	 = null;

		/**
		 * Static function hooks
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		public static function hooks() {
			if(is_null(self::$cfg_core)) {
//				self::$cfg_core = get_option('WPeMatico_Options');
			}

			/** Scripts and styles for own functions */
//		add_action( 'admin_print_scripts-post-new.php', array(__CLASS__,'scripts'), 11 );
//		add_action( 'admin_print_scripts-post.php', array(__CLASS__,'scripts'), 11 );
		add_action('admin_print_styles-post-new.php', array(__CLASS__,'styles'));
		add_action('admin_print_styles-post.php', array(__CLASS__,'styles'));

			add_action('add_meta_boxes', array(__CLASS__, 'metaboxes'), 15, 0);
			add_filter('pro_check_campaigndata', array(__CLASS__, 'check_data'), 15, 2);
		}

		/**
		 * Call the metaboxes
		 */
		public static function metaboxes() {
			global $wp_meta_boxes;
			$icon = '<span class="dashicons dashicons-translation"> </span> ';
			add_meta_box('polylang-box', $icon . __('Polylang Language', 'wpematico_polylang'), array(__CLASS__, 'polylang_box'), 'wpematico', 'side', 'core');
			add_filter('get_terms' , array(__CLASS__, 'pll_get_terms_fix'), 999, 4);
		}

		public static function pll_get_terms_fix($terms, $taxonomy, $query_vars, $term_query) {
			global $campaign_data;
			$default_language = (function_exists('pll_default_language')) ? pll_default_language() : 'en';
			$campaign_language = (isset($campaign_data['campaign_language']) && !empty($campaign_data['campaign_language'])) ? $campaign_data['campaign_language'] : $default_language;

			if(in_array('category',$taxonomy)) {
				if(function_exists('pll_get_term_language') && isset($campaign_language)){
					foreach ($terms as $key => $term) {
						if(isset($term->taxonomy)) {
							if($term->taxonomy == 'category' && pll_get_term_language($term->term_id) !== $campaign_language){
								unset($terms[$key]);
							}
						}
					}
					$terms = array_values($terms);
				}
			}
			return $terms;
		}

		/**
		 * Static function polylang_box
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		public static function polylang_box() {
			global $post, $campaign_data, $helptip;
			$default_language = (function_exists('pll_default_language')) ? pll_default_language() : 'en';
			$campaign_language = (isset($campaign_data['campaign_language']) && !empty($campaign_data['campaign_language'])) ? $campaign_data['campaign_language'] : $default_language;
			?>
			<span class="left"><?php _e('Select language for Posts(types).', 'wpematico_polylang'); ?></span>
			<?php if (isset($helptip['campaign_language'])) : ?>
				<span class="dashicons dashicons-warning help_tip" title="<?php echo $helptip['campaign_language']; ?>"></span>
			<?php endif; ?>

			<div class="" style="background: #eef1ff none repeat scroll 0% 0%;border: 2px solid #cee1ef;padding: 0.5em;">
				<b><?php _e('Available languages on Polylang', 'wpematico_polylang'); ?>:</b><br /><br />
				<?php
					$radios			 = "";
					if(function_exists('PLL')) {
						$translations	 = PLL()->model->get_languages_list(['fields' => 'slug']);
						foreach($translations as $key => $lng) {
							$lang_slug	 = PLL()->model->get_language($lng)->slug;
							$lang_name	 = PLL()->model->get_language($lng)->name;
							$lang_flag	 = PLL()->model->get_language($lng)->flag;
							$radios		 .= "<label>$lang_flag ";
							$radios		 .= "<input " . checked($lang_slug, $campaign_language, false) . " class='radio lang_radio' type='radio' name='campaign_language' value='$lang_slug' id='campaign_language_$lang_slug'>";
							$radios		 .= " $lang_name</label><br />";
						}
						echo $radios;
					}else{
						_e('Something\'s going wrong. The PLL function of Polylang seems not to exist.','wpematico_polylang');
					}
					?>
			</div>
			<div class="clear"></div>
			<?php
		}

	public static function check_data($campaign_data, $post_data) {
		if(empty($campaign_data)){
			$campaign_data = array();
		}
		$default_language = (function_exists('pll_default_language')) ? pll_default_language() : 'en';
		$campaign_data['campaign_language'] = (isset($post_data['campaign_language']) && !empty($post_data['campaign_language'])) ? $post_data['campaign_language'] : $default_language;
		
		return $campaign_data;
	}

	/**
		 * Static function styles
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		public static function styles() {
			global $post_type;
			if($post_type == 'wpematico') {
				wp_enqueue_style('wpepll-campaigns-edit-css', WPEMATICO_POLYLANG_URL . 'assets/css/campaign_edit.css', array(), WPEMATICO_POLYLANG_VERSION);
			}
		}

		/**
		 * Static function scripts
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		public static function scripts() {
			global $post_type;
			if($post_type == 'wpematico') {
//				wp_enqueue_script('wpepll-campaign-edit', WPEMATICO_POLYLANG_URL . 'assets/js/campaign_edit.js', array('jquery', 'wp-util'), WPEMATICO_POLYLANG_VERSION, true);
			}
		}

	}

	endif;
WPePLL_Campaign_Edit::hooks();
?>
