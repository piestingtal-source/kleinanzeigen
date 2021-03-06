<?php

/**
 * Classifieds Core Main Class
 **/
if (!class_exists('Classifieds_Core_Main')):
    class Classifieds_Core_Main extends Classifieds_Core
    {

        public $cf_ads_per_page = 20;

        /**
         * Constructor.
         *
         * @return void
         **/

        function __construct()
        {

            parent::__construct(); //Get the inheritance right

            //add_action( 'init', array(&$this, 'init'));

            /* Handle requests for plugin pages */

            add_action('template_redirect', array(&$this, 'process_page_requests'));

            add_action('template_redirect', array(&$this, 'handle_page_requests'));

            /* Enqueue scripts */
            add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'), 99);

            add_filter('author_link', array(&$this, 'on_author_link'));

        }

        function init()
        {
            global $wp, $wp_rewrite;

            parent::init();

            //Listing author rewrite rule
            $wp->add_query_var('cf_author_name');
            $wp->add_query_var('cf_author_page');

            $result = add_query_arg(array(
                'cf_author_name' => '$matches[1]',
                'cf_author_page' => '$matches[3]',
            ), 'index.php');

            add_rewrite_rule('cf-author/(.+?)(/page/(.+?))?/?$', $result, 'top');
            $rules = get_option('rewrite_rules');
            if (!isset($rules['cf-author/(.+?)(/page/(.+?))?/?$'])) $wp_rewrite->flush_rules();

        }


        /**
         * Process $_REQUEST for main pages.
         *
         * @uses set_query_var() For passing variables to pages
         * @return void|die() if "_wpnonce" is not verified
         **/
        function process_page_requests()
        {

            //Manage Classifieds
            if (is_page($this->meine_kleinanzeigen_page_id)) {
                // If confirm button is pressed
                if (isset($_POST['confirm'])) {
                    // Verify _wpnonce field
                    if (wp_verify_nonce($_POST['_wpnonce'], 'verify')) {
                        // Process posts based on the action variables. End action
                        if ($_POST['action'] == 'end') {
                            $this->process_status((int)$_POST['post_id'], 'private');
                        } // Renew action
                        elseif ($_POST['action'] == 'renew') {
                            // The credits required to renew the kleinanzeige for the selected period
                            $duration = isset($_POST[$this->custom_fields['duration']]) ? $_POST[$this->custom_fields['duration']] : $_POST['duration'];

                            $credits_required = $this->get_credits_from_duration($duration);
                            // If user have more credits of the required credits proceed with renewing the ad
                            if ($this->is_full_access() || ($this->user_credits >= $credits_required)) {
                                // Process the status of the post
                                $this->process_status((int)$_POST['post_id'], 'publish');
                                // Save the expiration date
                                $this->save_expiration_date($_POST['post_id']);

                                if (!$this->is_full_access()) {
                                    /* Update new credits amount */
                                    $this->transactions->credits -= $credits_required;
                                } else {
                                    //Check one_time
                                    if ($this->transactions->billing_type == 'one_time') $this->transactions->status = 'used';
                                }

                            } else {
                                $error = __('Du hast nicht gen??gend Guthaben, um Deine Kleinanzeige f??r den gew??hlten Zeitraum zu ver??ffentlichen. Bitte w??hle einen k??rzeren Zeitraum, falls verf??gbar, oder kaufe mehr Credits. Deine Anzeige wurde als Entwurf gespeichert.', 'kleinanzeigen');
                                set_query_var('cf_error', $error);
                            }
                            //$this->process_credits()
                        } /* Delete action */
                        elseif ($_POST['action'] == 'delete') {
                            wp_delete_post($_POST['post_id']);
                            /* Set the proper step which will be loaded by "page-meine-kleinanzeigen.php" */
                            set_query_var('cf_action', 'meine-kleinanzeigen');
                        }
                    } else {
                        die(__('Sicherheits??berpr??fung fehlgeschlagen!', 'kleinanzeigen'));
                    }
                }

                //Updating Classifieds
            } elseif (is_page($this->add_kleinanzeige_page_id) || is_page($this->edit_kleinanzeige_page_id)) {

                if (isset($_POST['update_kleinanzeige'])) {
                    // The credits required to renew the kleinanzeige for the selected period

                    $credits_required = $this->get_credits_from_duration($_POST[$this->custom_fields['duration']]);
                    // If user have more credits of the required credits proceed with renewing the ad
                    if ($this->is_full_access() || ($this->user_credits >= $credits_required)) {
                        // Update ad
                        $this->update_ad($_POST);
                        // Save the expiration date
                        $this->save_expiration_date($_POST['post_id']);
                        // Set the proper step which will be loaded by "page-meine-kleinanzeigen.php"
                        set_query_var('cf_action', 'meine-kleinanzeigen');

                        if (!$this->is_full_access()) {
                            // Update new credits amount
                            $this->transactions->credits -= $credits_required;
                        } else {
                            //Check one_time
                            if ($this->transactions->billing_type == 'one_time') $this->transactions->status = 'used';
                        }

                        wp_redirect(get_permalink($_POST['post_id']));
                        exit;

                    } else {

                        //save ad if have no credits
                        $_POST['kleinanzeige_data']['post_status'] = 'draft';
                        /* Create ad */
                        $post_id = $this->update_ad($_POST);
                        set_query_var('cf_post_id', $_POST['post_id']);
                        /* Set the proper step which will be loaded by "page-meine-kleinanzeigen.php" */
                        set_query_var('cf_action', 'edit');
                        $error = __('Du hast nicht gen??gend Guthaben, um Deine Kleinanzeige f??r den gew??hlten Zeitraum zu ver??ffentlichen. Bitte w??hle einen k??rzeren Zeitraum, falls verf??gbar, oder kaufe mehr Credits.<br />Deine Anzeige wurde als Entwurf gespeichert.', 'kleinanzeigen');
                        set_query_var('cf_error', $error);
                    }
                }
            }
        }


        /**
         * Handle $_REQUEST for main pages.
         *
         * @uses set_query_var() For passing variables to pages
         * @return void|die() if "_wpnonce" is not verified
         **/
        function handle_page_requests()
        {
            global $wp_query;

            /* Handles request for kleinanzeigen page */
            $templates = array();
            $taxonomy = (empty($wp_query->query_vars['taxonomy'])) ? '' : $wp_query->query_vars['taxonomy'];

            //Check if a custom template is selected, if not or not a page, default to the one selected for the directory_listing virtual page.
            $id = get_queried_object_id();
            if (empty($id)) $id = $this->kleinanzeigen_page_id;
            $slug = get_page_template_slug($id);
            if (empty($slug)) $page_template = get_page_template();
            else $page_template = locate_template(array($slug, 'page.php', 'index.php'));

            if (is_feed()) {
                return;
            } elseif ('' != get_query_var('cf_author_name') || isset($_REQUEST['cf_author']) && '' != $_REQUEST['cf_author']) {
                $templates = array('page-author');
                if (!$this->kleinanzeigen_template = locate_template($templates)) {
                    $this->kleinanzeigen_template = $page_template;
                    $wp_query->post_count = 1;
                    add_filter('the_title', array(&$this, 'page_title_output'), 10, 2);
                    add_filter('the_content', array(&$this, 'kleinanzeigen_content'));
                }
// 			add_filter( 'template_include', array( &$this, 'custom_kleinanzeigen_template' ) );
                $this->is_kleinanzeigen_page = true;

            } elseif (is_post_type_archive('kleinanzeigen')) {
                global $wp_query;
                $p = get_post($this->kleinanzeigen_page_id);
                $wp_query->posts = array($p);
                $wp_query->post_count = 1;
                /* Set the proper step which will be loaded by "page-meine-kleinanzeigen.php" */
                $templates = array('archive-kleinanzeigen.php');
                if (!$this->kleinanzeigen_template = locate_template($templates)) {
                    $this->kleinanzeigen_template = $page_template;
                    $wp_query->post_count = 1;
                    add_filter('the_title', array(&$this, 'page_title_output'), 10, 2);
                    add_filter('the_content', array(&$this, 'kleinanzeigen_content'));
                }
                add_filter('template_include', array(&$this, 'custom_kleinanzeigen_template'));
                $this->is_kleinanzeigen_page = true;
            } elseif (is_archive() && in_array($taxonomy, array('kleinanzeigen_categories', 'kleinanzeigen_tags'))) {
                /* Set the proper step which will be loaded by "page-meine-kleinanzeigen.php" */
                $templates = array("taxonomy-{$taxonomy}.php");
                if (!$this->kleinanzeigen_template = locate_template($templates)) {
                    $this->kleinanzeigen_template = $page_template;
                    $wp_query->post_count = 1;
                    add_filter('the_title', array(&$this, 'page_title_output'), 10, 2);
                    add_filter('the_content', array(&$this, 'kleinanzeigen_content'));
                }
                add_filter('template_include', array(&$this, 'custom_kleinanzeigen_template'));
                $this->is_kleinanzeigen_page = true;
            } elseif (is_single() && 'kleinanzeigen' == get_query_var('post_type')) {
                $templates = array('single-kleinanzeigen.php');
                if (!$this->kleinanzeigen_template = locate_template($templates)) {
                    $this->kleinanzeigen_template = $page_template;
                    add_filter('the_content', array(&$this, 'single_content'));
                }
                add_filter('template_include', array(&$this, 'custom_kleinanzeigen_template'));
                $this->is_kleinanzeigen_page = true;
            } elseif (is_page($this->my_credits_page_id)) {
                $templates = array('page-my-credits.php');
                if (!$this->kleinanzeigen_template = locate_template($templates)) {
                    $this->kleinanzeigen_template = $page_template;
                    add_filter('the_content', array(&$this, 'my_credits_content'));
                }
                add_filter('template_include', array(&$this, 'custom_kleinanzeigen_template'));
                $this->is_kleinanzeigen_page = true;
            } elseif (is_page($this->checkout_page_id)) {
                $templates = array('page-checkout.php');
                if (!$this->kleinanzeigen_template = locate_template($templates)) {
                    $this->kleinanzeigen_template = $page_template;
                    add_filter('the_content', array(&$this, 'checkout_content'));
                }
                add_filter('template_include', array(&$this, 'custom_kleinanzeigen_template'));
                $this->is_kleinanzeigen_page = true;
            } elseif (is_page($this->signin_page_id)) {
                $templates = array('page-signin.php');
                if (!$this->kleinanzeigen_template = locate_template($templates)) {
                    $this->kleinanzeigen_template = $page_template;
                    add_filter('the_title', array(&$this, 'delete_post_title'), 11); //after wpautop
                    add_filter('the_content', array(&$this, 'signin_content'));
                }
                add_filter('template_include', array(&$this, 'custom_kleinanzeigen_template'));
                $this->is_kleinanzeigen_page = true;
            } //My Classifieds page
            elseif (is_page($this->meine_kleinanzeigen_page_id)) {
                $templates = array('page-meine-kleinanzeigen.php');
                if (!$this->kleinanzeigen_template = locate_template($templates)) {
                    $this->kleinanzeigen_template = $page_template;
                    add_filter('the_content', array(&$this, 'meine_kleinanzeigen_content'));
                }
                add_filter('template_include', array(&$this, 'custom_kleinanzeigen_template'));
                $this->is_kleinanzeigen_page = true;
            } //Classifieds update pages
            elseif (is_page($this->add_kleinanzeige_page_id) || is_page($this->edit_kleinanzeige_page_id)) {
                $templates = array('page-update-kleinanzeige.php');
                if (!$this->kleinanzeigen_template = locate_template($templates)) {
                    $this->kleinanzeigen_template = $page_template;
                    add_filter('the_content', array(&$this, 'update_kleinanzeige_content'));
                }
                add_filter('template_include', array(&$this, 'custom_kleinanzeigen_template'));
                $this->is_kleinanzeigen_page = true;
            } /* If user wants to go to My Classifieds main page  */
            elseif (isset($_POST['go_meine_kleinanzeigen'])) {
                wp_redirect(get_permalink($this->meine_kleinanzeigen_page_id));
            } /* If user wants to go to checkout page  */
            elseif (isset($_POST['purchase'])) {
                wp_redirect(get_permalink($this->checkout_page_id));
            } else {
                /* Set the proper step which will be loaded by "page-meine-kleinanzeigen.php" */
                set_query_var('cf_action', 'meine-kleinanzeigen');
            }

            //load  specific items
            if ($this->is_kleinanzeigen_page) {
                add_filter('edit_post_link', array(&$this, 'delete_edit_post_link'));
            }
        }

        /**
         * Enqueue scripts.
         *
         * @return void
         **/
        function enqueue_scripts()
        {
            if (is_page($this->add_kleinanzeige_page_id) || is_page($this->edit_kleinanzeige_page_id)) {
                wp_enqueue_script('thickbox');
                wp_enqueue_style('thickbox');
            }

            if (file_exists(get_template_directory() . '/style-kleinanzeigen.css')) {
                wp_enqueue_style('style-kleinanzeigen', get_template_directory() . '/style-kleinanzeigen.css');
            } elseif (file_exists($this->plugin_dir . 'ui-front/general/style-kleinanzeigen.css')) {
                wp_enqueue_style('style-kleinanzeigen', $this->plugin_url . 'ui-front/general/style-kleinanzeigen.css');
            }
        }

        function on_author_link($link = '')
        {
            global $post;

            if ($post->post_type == 'kleinanzeigen') {
                $link = str_replace('/author/', '/cf-author/', $link);
            }
            return $link;
        }

    }

    /* Initiate Class */
    global $Classifieds_Core;
    $Classifieds_Core = new Classifieds_Core_Main;

endif;
?>