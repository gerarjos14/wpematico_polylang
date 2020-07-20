<?php

/**
 * It will be used to manage all feature on the campaign fetching.
 *  @package WPeMatico Polylang
 * 	functions to add filters and parsers on campaign running
 * */
if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

class wpematico_polylangprocess {

	public static function hooks() {
		add_action('Wpematico_init_fetching', array(__CLASS__, 'init_fetching'), 10, 1);
	}

	public static function init_fetching($campaign) {
		//add_action('wpematico_inserted_post', array(__CLASS__, 'process'), 999, 3);
		add_filter('wpematico_allow_insertpost', array(__CLASS__, 'insert_all_languages'), 999, 3);
	}
	
	/**
	 * Assign campaign language to each inserted post
	 * 
	 * https://stackoverflow.com/questions/42020630/wordpress-saving-translation-post-when-creating-new-post 
	 * 
	 * https://maswordpress.info/questions/73613/traduccion-de-polylang-de-una-publicacion-personalizada-crea
	 * 
	 */
	public static function insert_all_languages($dontallowinsert, $fetchclass, $args) {
		//echo '<pre>'; print_r($fetchclass); echo '</pre>';
		$default_language = (function_exists('pll_default_language')) ? pll_default_language() : 'en';
		$campaign_language = (isset($fetchclass->campaign['campaign_language']) && !empty($fetchclass->campaign['campaign_language'])) ? $fetchclass->campaign['campaign_language'] : $default_language;

		//remove_filter('content_save_pre', 'wp_filter_post_kses');
//			remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
		
		//obtener los lenguajes y quitar el de la campaña del array
		//Insertar el post en cada lenguaje, obteniendo el post_id de cada uno 
		//   Eso lo hace pll_save_post_translations ? 
		//salvar las taxonomias en cada lenguaje
		
		//			$id = pll_get_post($post->ID, 'en');
		$post_id = wp_insert_post($args);
		pll_set_post_language($post_id, $campaign_language);
		//pll_save_post_translations(['es' => $post_id]);
		trigger_error('<b>' . sprintf(__('Polylang inserting post to %s language', 'polyglot'), PLL()->model->get_language($campaign_language)->name) . '</b>', E_USER_NOTICE);

		// If we have an id save new meta to the translated post
		if(!empty($id)) {
			//loopt_through_meta($item_meta, get_post($id));
		}		
		

		//follow the standard rules in core class
		if ($fetchclass->cfg['woutfilter'] && $fetchclass->campaign['campaign_woutfilter']) {
			global $wpdb, $wp_locale, $current_blog;
			$table_name = $wpdb->prefix . "posts";
			$blog_id = @$current_blog->blog_id;
			$fetchclass->current_item['content'] = $truecontent;
			trigger_error(__('** Adding unfiltered content **', 'wpematico'), E_USER_NOTICE);
			$wpdb->update($table_name, array('post_content' => $fetchclass->current_item['content'], 'post_content_filtered' => $fetchclass->current_item['content']), array('ID' => $post_id));
		}

		//$fetchclass->postProcessItem($id, $item);
		$fetchclass->postProcessItem($post_id, $args);


		// If pingback/trackbacks
		if ($fetchclass->campaign['campaign_allowpings']) {
			trigger_error(__('Processing item pingbacks', 'wpematico'), E_USER_NOTICE);
			require_once(ABSPATH . WPINC . '/comment.php');
			pingback($fetchclass->current_item['content'], $post_id);
		}

		return false; // do not process the core insert post
	}

	/**
	 * Assign campaign language to each inserted post
	 * @param type $post_id
	 * @param type $campaign
	 * @param type $item
	 */
	public static function process($post_id, $campaign, $item) {

		$default_language = (function_exists('pll_default_language')) ? pll_default_language() : 'en';
		$campaign_language = (isset($campaign['campaign_language']) && !empty($campaign['campaign_language'])) ? $campaign['campaign_language'] : $default_language;

		trigger_error('<b>' . sprintf(__('Polylang inserting post to %s language', 'polyglot'), PLL()->model->get_language($campaign_language)->name) . '</b>', E_USER_NOTICE);

		if (function_exists('pll_set_post_language')) {
			pll_set_post_language($post_id, $campaign_language);

			$taxonomies = get_post_taxonomies($post_id);
			$terms = wp_get_object_terms($post_id, $taxonomies);
			foreach ($terms as $term) {
//                if ($translation = Pll()->model->term->get_translation($term->term_id, $campaign_language)) {
//                    wp_set_post_terms($post_id, $translation, $term->taxonomy);
//                }
				pll_set_term_language($term->term_id, $campaign_language);
				trigger_error(sprintf(__('Inserting %s language to term %s', 'polyglot'), PLL()->model->get_language($campaign_language)->name, $term->slug), E_USER_NOTICE);
			}
		} else {
			trigger_error(__('Something\'s going wrong. The pll_set_post_language function of Polylang seems not to exist.', 'wpematico_polylang'), E_USER_WARNING);
		}
	}

	//////////  EJEMPLOS  revisa terms e imagenes antes de asignar idioma
	/**
	  public function saveLanguagesPost($languageSlug) {
	  $newPostArgs = apply_filters('bp_trapp_save_language_post_args', ['post_title' => $this->post->post_title, 'post_content' => $this->post->post_content, 'post_type' => $this->post->post_type], $this->post, $languageSlug);
	  $langPostId = wp_insert_post($newPostArgs);
	  pll_set_post_language($langPostId, $languageSlug);
	  $this->saveImages($langPostId, $languageSlug);
	  $this->saveTerms($langPostId, $languageSlug);
	  return $langPostId;
	  }

	  public function saveTerms($translationId, $languageSlug) {
	  $hook = sprintf('bp_trapp_save_%s_taxonomies', $this->post->post_type);
	  $taxonomies = apply_filters($hook, []);
	  $terms = wp_get_object_terms($this->post->ID, $taxonomies);
	  foreach ($terms as $term) {
	  if ($translation = Pll()->model->term->get_translation($term->term_id, $languageSlug)) {
	  wp_set_post_terms($translationId, $translation, $term->taxonomy);
	  }
	  }
	  }

	  public function saveImages($translationId, $languageSlug) {
	  $images = [];
	  if (has_post_thumbnail($this->post->ID)) {
	  $thumbnailId = get_post_thumbnail_id($this->post->ID);
	  $thumbnailPost = get_post($thumbnailId);
	  $images['featured_image'] = ['id' => $thumbnailId, 'post' => $thumbnailPost, 'type' => 'meta', 'key' => '_thumbnail_id'];
	  }
	  $images = apply_filters('bp_trapp_save_images', $images, $this->post->ID);
	  foreach ($images as $image) {
	  $this->saveImage($translationId, $languageSlug, $image);
	  }
	  }

	  public function saveImage($translationId, $languageSlug, $image) {
	  // Check if the translations already exists
	  if ($translation = Pll()->model->post->get_translation($image['id'], $languageSlug)) {
	  return update_post_meta($translationId, $image['key'], $translation);
	  }
	  $translationImagePost = $image['post'];
	  // Create a new attachment
	  $translationImagePost->ID = null;
	  $translationImagePost->post_parent = $translationId;
	  $translationImageId = wp_insert_attachment($translationImagePost);
	  add_post_meta($translationImageId, '_wp_attachment_metadata', get_post_meta($image['id'], '_wp_attachment_metadata', true));
	  add_post_meta($translationImageId, '_wp_attached_file', get_post_meta($image['id'], '_wp_attached_file', true));
	  add_post_meta($translationImageId, '_wp_attachment_image_alt', get_post_meta($image['id'], '_wp_attachment_image_alt', true));
	  $mediaTranslations = Pll()->model->post->get_translations($image['id']);
	  if (!$mediaTranslations && ($lang = Pll()->model->post->get_language($image['id']))) {
	  $mediaTranslations[$lang->slug] = $image['id'];
	  }
	  $mediaTranslations[$languageSlug] = $translationImageId;
	  pll_save_post_translations($mediaTranslations);
	  update_post_meta($translationId, $image['key'], $translationImageId);
	  do_action('bp_trapp_after_save_post_image', $translationImageId, $image);
	  }
	 */
}

wpematico_polylangprocess::hooks();

