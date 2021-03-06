<?php

/**
* Load core DB data. Only loaded during Activation
*/
if ( !class_exists('Classifieds_Core_Data') ):
class Classifieds_Core_Data {

	/**
	* Constructor.
	*
	* @return void
	**/

	function __construct() {
		add_action( 'init', array( &$this, 'load_data' ) );
		add_action( 'init', array( &$this, 'load_payment_data' ) );
		add_action( 'init', array( &$this, 'load_mu_plugins' ) );
		add_action( 'init', array( &$this, 'rewrite_rules' ) );
	}

	/**
	* Load initial Content Types data for plugin
	*
	* @return void
	*/
	function load_data() {
		/* Get setting options. If empty return an array */
		$options = ( get_site_option( CF_OPTIONS_NAME ) ) ? get_site_option( CF_OPTIONS_NAME ) : array();

		// Check whether post types are loaded

		if ( ! post_type_exists('kleinanzeigen') ) {

			$kleinanzeigen_default =
			array (
			'can_export' => true,
			'capability_type' => 'kleinanzeige',
			'description' => 'Typ des Kleinanzeigenbeitrags.',
			'has_archive' => 'kleinanzeigen',
			'hierarchical' => false,
			'map_meta_cap' => true,
			'menu_position' => '',
			'public' => true,
			'query_var' => true,
			'rewrite' => array ( 'slug' => 'kleinanzeige', 'with_front' => false, 'pages' => true),

			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments', 'revisions', /*'post-formats'*/ ),

			'labels' => array (
			'name'          => __('Kleinanzeigen', 'kleinanzeigen'),
			'singular_name' => __('Kleinanzeige', 'kleinanzeigen'),
			'add_new'       => __('Neue hinzufügen', 'kleinanzeigen'),
			'add_new_item'  => __('Neue Kleinanzeige hinzufügen', 'kleinanzeigen'),
			'edit_item'     => __('Kleinanzeige bearbeiten', 'kleinanzeigen'),
			'new_item'      => __('Neue Kleinanzeige', 'kleinanzeigen'),
			'view_item'     => __('Kleinanzeige anzeigen', 'kleinanzeigen'),
			'search_items'  => __('Kleinanzeigen suchen', 'kleinanzeigen'),
			'not_found'     => __('Keine Kleinanzeigen gefunden', 'kleinanzeigen'),
			'not_found_in_trash' => __('Keine Kleinanzeigen im Papierkorb gefunden', 'kleinanzeigen'),
			),
			);

			//Update custom post types
			if(is_network_admin()){
				$ct_custom_post_types = get_site_option( 'ct_custom_post_types' );
				$ct_custom_post_types['kleinanzeigen'] = $kleinanzeigen_default;
				update_site_option( 'ct_custom_post_types', $ct_custom_post_types );
			} else {
				$ct_custom_post_types = get_option( 'ct_custom_post_types' );
				$ct_custom_post_types['kleinanzeigen'] = $kleinanzeigen_default;
				update_option( 'ct_custom_post_types', $ct_custom_post_types );
			}

			// Update post types and delete tmp options
			flush_network_rewrite_rules();
		}

		/* Check whether taxonomies data is loaded */


		if ( ! taxonomy_exists('kleinanzeigen_tags') ){

			$kleinanzeigen_tags_default = array();
			$kleinanzeigen_tags_default['object_type'] = array ( 'kleinanzeigen');
			$kleinanzeigen_tags_default['args'] = array (
			'public' => true,
			'hierarchical' => false,
			'rewrite' => array ( 'slug' => 'cf-tags', 'with_front' => false, 'hierarchical' => false ),
			'query_var' => true,
			'capabilities' => array ('assign_terms' => 'edit_kleinanzeigen'),

			'labels' => array (
			'name'          => __( 'Kleinanzeigen Tags', 'kleinanzeigen' ),
			'singular_name' => __( 'Kleinanzeige Tag', 'kleinanzeigen' ),
			'search_items'  => __( 'Nach Kleinanzeigen Tags suchen', 'kleinanzeigen' ),
			'popular_items' => __( 'Beliebte Kleinanzeigen Tags', 'kleinanzeigen' ),
			'all_items'     => __( 'Alle Kleinanzeigen Tags', 'kleinanzeigen' ),
			'edit_item'     => __( 'Kleinanzeigen Tag bearbeiten', 'kleinanzeigen' ),
			'update_item'   => __( 'Kleinanzeigen Tag aktualisieren', 'kleinanzeigen' ),
			'add_new_item'  => __( 'Neues Kleinanzeigen Tag hinzufügen', 'kleinanzeigen' ),
			'new_item_name' => __( 'Neuer Kleinanzeigen Tag-Name', 'kleinanzeigen' ),
			'add_or_remove_items' => __( 'Kleinanzeigen Tags hinzufügen oder entfernen', 'kleinanzeigen' ),
			'choose_from_most_used' => __( 'Wähle aus den am häufigsten verwendeten Kleinanzeigen Tags', 'kleinanzeigen' ),
			'separate_items_with_commas' => __( 'Kleinanzeigen Tags durch Kommas trennen', 'kleinanzeigen' ),
			),
			);

			if(is_network_admin()){
				$ct_custom_taxonomies = get_site_option('ct_custom_taxonomies');
				$ct_custom_taxonomies['kleinanzeigen_tags'] = $kleinanzeigen_tags_default;
				update_site_option( 'ct_custom_taxonomies', $ct_custom_taxonomies );
			} else {
				$ct_custom_taxonomies = get_option('ct_custom_taxonomies');
				$ct_custom_taxonomies['kleinanzeigen_tags'] = $kleinanzeigen_tags_default;
				update_option( 'ct_custom_taxonomies', $ct_custom_taxonomies );
			}

			// Update post types and delete tmp options
			flush_network_rewrite_rules();

		}

		if ( ! taxonomy_exists('kleinanzeigen_categories') ){

			if(is_multisite()){
				$ct = get_option( 'ct_custom_taxonomies' ); // get the blog types
				if(isset($ct['kleinanzeigen_categories'])) unset($ct['kleinanzeigen_categories']);
				update_option( 'ct_custom_taxonomies', $ct ); //Remove from site specific and move to network options.
			}

			$kleinanzeigen_categories_default = array();
			$kleinanzeigen_categories_default['object_type'] = array ('kleinanzeigen');
			$kleinanzeigen_categories_default['args'] = array (
			'public' => true,
			'hierarchical'  => true,
			'rewrite' => array ('slug' => 'cf-categories', 'with_front' => false, 'hierarchical' => true),
			'query_var' => true,
			'capabilities' => array ( 'assign_terms' => 'edit_kleinanzeigen' ),

			'labels' => array (
			'name'          => __( 'Kleinanzeigen Kategorien', 'kleinanzeigen' ),
			'singular_name' => __( 'Kleinanzeigen Kategorie', 'kleinanzeigen' ),
			'search_items'  => __( 'Kleinanzeigen Kategorien durchsuchen', 'kleinanzeigen' ),
			'popular_items' => __( 'Beliebte Kleinanzeigen Kategorien', 'kleinanzeigen' ),
			'all_items'     => __( 'Alle Kleinanzeigen Kategorien', 'kleinanzeigen' ),
			'parent_item'   => __( 'Eltern-Kategorie', 'kleinanzeigen' ),
			'edit_item'     => __( 'Kleinanzeigen Kategorie bearbeiten', 'kleinanzeigen' ),
			'update_item'   => __( 'Kleinanzeigen Kategorie aktualisieren', 'kleinanzeigen' ),
			'add_new_item'  => __( 'Neue Kleinanzeigen Kategorie hinzufügen', 'kleinanzeigen' ),
			'new_item_name' => __( 'Neue Kleinanzeigen Kategorie', 'kleinanzeigen' ),
			'parent_item_colon'   => __( 'Eltern-Kategorie:', 'kleinanzeigen' ),
			'add_or_remove_items' => __( 'Kleinanzeigen Kategorien hinzufügen oder entfernen', 'kleinanzeigen' ),
			),
			);

			if(is_network_admin()){
				$ct_custom_taxonomies = get_site_option('ct_custom_taxonomies');
				$ct_custom_taxonomies['kleinanzeigen_categories'] = $kleinanzeigen_categories_default;
				update_site_option( 'ct_custom_taxonomies', $ct_custom_taxonomies );
			} else {
				$ct_custom_taxonomies = get_option('ct_custom_taxonomies');
				$ct_custom_taxonomies['kleinanzeigen_categories'] = $kleinanzeigen_categories_default;
				update_option( 'ct_custom_taxonomies', $ct_custom_taxonomies );
			}

			flush_network_rewrite_rules();
		}


		/* Check whether custom fields data is loaded */

		$ct_custom_fields = ( get_option( 'ct_custom_fields' ) );
		$ct_network_custom_fields = ( get_site_option( 'ct_custom_fields' ) );

		if ( empty($ct_custom_fields['selectbox_4cf582bd61fa4']) && empty($ct_network_custom_fields['selectbox_4cf582bd61fa4'])){

			$selectbox_4cf582bd61fa4_default =
			array (
			'field_title' => 'Dauer',
			'field_type' => 'selectbox',
			'field_sort_order' => 'default',
			'field_options' =>
			array (
			1 => '',
			2 => '1 Week',
			3 => '2 Weeks',
			4 => '3 Weeks',
			5 => '4 Weeks',
			),
			'field_default_option' => '1',
			'field_description' => 'Laufzeit dieser Anzeige ab heute. ',
			'object_type' => array ('kleinanzeigen'),
			'hide_type' => array (),

			'field_required' => NULL,
			'field_id' => 'selectbox_4cf582bd61fa4',
			);

			if( is_network_admin() ){
				$ct_network_custom_fields['selectbox_4cf582bd61fa4'] = $selectbox_4cf582bd61fa4_default;
				update_site_option( 'ct_custom_fields', $ct_network_custom_fields );
			} else {
				$ct_custom_fields['selectbox_4cf582bd61fa4'] = $selectbox_4cf582bd61fa4_default;
				update_option( 'ct_custom_fields', $ct_custom_fields );
			}
		}

		if ( empty($ct_custom_fields['text_4cfeb3eac6f1f']) && empty($ct_network_custom_fields['text_4cfeb3eac6f1f'])){

			$text_4cfeb3eac6f1f_default =
			array (
			'object_type' => array ('kleinanzeigen'),
			'hide_type' => array (),
			'field_title' => 'Preis',
			'field_type' => 'text',
			'field_sort_order' => 'default',
			'field_default_option' => NULL,
			'field_description' => 'Die Kosten des Artikels.',
			'field_required' => NULL,
			'field_id' => 'text_4cfeb3eac6f1f',
			);

			if( is_network_admin() ){
				$ct_network_custom_fields['text_4cfeb3eac6f1f'] = $text_4cfeb3eac6f1f_default;
				update_site_option( 'ct_custom_fields', $ct_network_custom_fields );
			} else {
				$ct_custom_fields['text_4cfeb3eac6f1f'] = $text_4cfeb3eac6f1f_default;
				update_option( 'ct_custom_fields', $ct_custom_fields );
			}
		}

		//Custompress specfic
		if(is_multisite()){
			update_site_option( 'allow_per_site_content_types', true );
			update_site_option( 'display_network_content_types', true );

		}

		flush_network_rewrite_rules();

	}

	function load_payment_data() {

		$options = ( get_option( CF_OPTIONS_NAME ) ) ? get_option( CF_OPTIONS_NAME ) : array();
		$options = ( is_array($options) ) ? $options : array();

		//General default
		if(empty($options['general']) ){
			$options['general'] = array(
			'member_role'             => 'subscriber',
			'moderation'              => array('publish' => 1, 'pending' => 1, 'draft' => 1 ),
			'custom_fields_structure' => 'table',
			'welcome_redirect'        => 'true',
			'key'                     => 'general'
			);
		}

		//Update from older version
		if (! empty($options['general_settings']) ) {
			$options['general'] = array_replace($options['general_settings']);
			unset($options['general_settings']);
		}

		//Default Payments settings
		if ( empty( $options['payments'] ) ) {
			$options['payments'] = array(
			'enable_recurring'    => '1',
			'recurring_cost'      => '9.99',
			'recurring_name'      => 'Abonnement',
			'billing_period'      => 'Monat',
			'billing_frequency'   => '1',
			'billing_agreement'   => 'Dem Kunden werden 2 Jahre lang &ldquo;9,99 pro Monat in Rechnung gestellt&rdquo;',
			'enable_one_time'     => '1',
			'one_time_cost'       => '99.99',
			'one_time_name'       => 'Nur einmal',
			'enable_credits'      => '1',
			'cost_credit'         => '.99',
			'credits_per_week'    => 1,
			'signup_credits'      => 0,
			'credits_description' => '',
			'tos_txt'             => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at sem libero. Pellentesque accumsan consequat porttitor. Curabitur ut lorem sed ipsum laoreet tempus at vel erat. In sed tempus arcu. Quisque ut luctus leo. Nulla facilisi. Sed sodales lectus ut tellus venenatis ac convallis metus suscipit. Vestibulum nec orci ut erat ultrices ullamcorper nec in lorem. Vivamus mauris velit, vulputate eget adipiscing elementum, mollis ac sem. Aliquam faucibus scelerisque orci, ut venenatis massa lacinia nec. Phasellus hendrerit lorem ornare orci congue elementum. Nam faucibus urna a purus hendrerit sit amet pulvinar sapien suscipit. Phasellus adipiscing molestie imperdiet. Mauris sit amet justo massa, in pellentesque nibh. Sed congue, dolor eleifend egestas egestas, erat ligula malesuada nulla, sit amet venenatis massa libero ac lacus. Vestibulum interdum vehicula leo et iaculis.',
			'key'                 => 'payments'
			);
		}

		if (! empty($options['payment_settings']) ) {
			$options['payments'] = array_replace($options['payment_settings']);
			unset($options['payment_settings']);
		}

		if(empty($options['payment_types']) ) {
			$options['payment_types'] = array(
			'use_free'         => 1,
			'use_paypal'       => 0,
			'use_authorizenet' => 0,
			'paypal'           => array('api_url' => 'sandbox', 'api_username' => '', 'api_password' => '', 'api_signature' => '', 'currency' => 'USD'),
			'authorizenet'     => array('mode' => 'sandbox', 'delim_char' => ',', 'encap_char' => '', 'email_customer' => 'yes', 'header_email_receipt' => 'Vielen Dank für Deine Zahlung!', 'delim_data' => 'yes'),
			);
		}

		if ( ! empty($options['paypal']) ){
			$options['payment_types']['paypal'] = array_replace($options['paypal']);
			unset($options['paypal']);
		}

		update_option( CF_OPTIONS_NAME, $options );
	}

	function load_mu_plugins(){

		if(!is_dir(WPMU_PLUGIN_DIR . '/logs')):
		mkdir(WPMU_PLUGIN_DIR . '/logs', 0755, true);
		endif;

		copy(	CF_PLUGIN_DIR . 'mu-plugins/gateway-relay.php', WPMU_PLUGIN_DIR .'/gateway-relay.php');
		copy(	CF_PLUGIN_DIR . 'mu-plugins/wpmu-assist.php', WPMU_PLUGIN_DIR .'/wpmu-assist.php');

	}

	function rewrite_rules() {

		add_rewrite_rule("kleinanzeigen/author/([^/]+)/page/?([2-9][0-9]*)",
		"index.php?post_type=kleinanzeigen&author_name=\$matches[1]&paged=\$matches[2]", 'top');

		add_rewrite_rule("kleinanzeigen/author/([^/]+)",
		"index.php?post_type=kleinanzeigen&author_name=\$matches[1]", 'top');

		flush_network_rewrite_rules();
	}

}

endif;
