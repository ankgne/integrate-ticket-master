<?php

/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/24/2018
 * Time: 11:11 AM
 */

include(AK_TICKETMASTER_BASE_DIR . '/includes/classes/admin/settings/ank-wp-ticket-master-settings-form.php');
class ank_wp_ticket_master_settings_page
{
    private $ank_wp_ticket_settings_form = '';

    /**
     * __construct
     * For setting-up the setting page of the plugin
     *
     * @access  private
     * @since 	1.0
     *
     * @uses 	add_action()
     *
     * @return  void
     */
    function __construct() {
        $this->ank_wp_ticket_settings_form = new ank_wp_ticket_settings_form();//core logic of the settings page is present in this file
        //Admin_menu fires before admin_init
        add_action('admin_menu', array($this, 'ank_wp_ticket_settings_setup'),9); // added priorty as per the note in "show_in_menu" section present at https://codex.wordpress.org/Function_Reference/register_post_type
        add_action('admin_init', array($this->ank_wp_ticket_settings_form, 'ank_wp_ticket_settings_display_options'));//display setting page options
    }

    /**
     * ank_wp_ticket_settings_setup
     * Added setting page named 'Ticket Master Settings' in admin section
     *
     * @access  public
     * @since 	1.0
     *
     * @uses 	add_menu_page()
     *
     * @return  void
     */
    public function ank_wp_ticket_settings_setup() {
        //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $output_function, $icon_url, $position )
        add_menu_page(__('Ticket Master Settings', 'ank_wp_ticket'), __('Ticket Master Settings', 'ank_wp_ticket'), 'manage_options', 'ticket-master-settings', array($this->ank_wp_ticket_settings_form, 'ank_wp_ticket_render_options_page'));
    }

}