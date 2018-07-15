<?php
/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/24/2018
 * Time: 11:06 AM
 */

class ank_wp_ticket_scripts {
    /**
     * ank_wp_ticket_scripts constructor.
     */
    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'ank_wp_ticket_load_generic_scripts' ) );
    }

    function ank_wp_ticket_load_generic_scripts()
    {
        wp_register_style('ank-wp-ticket-bootstrap', AK_TICKETMASTER_BASE_URL . 'includes/css/bootstrap.min.css', false, AK_TICKETMASTER_VERSION, 'all');
        //webfonts folder is for awesome font
        wp_register_style('ank-wp-ticket-font-awesome', AK_TICKETMASTER_BASE_URL . 'includes/css/font-awesome.css', false, AK_TICKETMASTER_VERSION, 'all');
        wp_register_style('ank-wp-ticket-custom', AK_TICKETMASTER_BASE_URL . 'includes/css/ank-wp-ticket-custom.css', false, AK_TICKETMASTER_VERSION, 'all');
        wp_enqueue_style('ank-wp-ticket-bootstrap');
        wp_enqueue_style('ank-wp-ticket-custom');
        wp_enqueue_style('ank-wp-ticket-font-awesome');
        wp_enqueue_script('ank-wp-ticket-bootstrap-jquery', AK_TICKETMASTER_BASE_URL . 'includes/js/bootstrap.min.js', array('jquery'), AK_TICKETMASTER_VERSION);
    }
}