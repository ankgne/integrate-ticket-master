<?php
/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/24/2018
 * Time: 11:04 AM
 */

/*
*  ank_wp_ticket_activate
*
*  Add plugin version on activation
*
*  @since	1.0
*
*  @param	N/A
*  @return	N/A
*/
if (!function_exists('ank_wp_ticket_activate')){
    function ank_wp_ticket_activate(){
        add_option(AK_TICKETMASTER_VERSION_KEY, AK_TICKETMASTER_VERSION);
    }
}

/*
*  ank_wp_ticket_uninstall
*
*  Delete all the options if the plugin is uninstalled
*
*  @since	1.0
*
*  @param	N/A
*  @return	N/A
*/
if (!function_exists('ank_wp_ticket_uninstall')){
    function ank_wp_ticket_deactivate(){
        delete_option(AK_TICKETMASTER_VERSION_KEY);
        delete_option('ank_wp_ticket_debug_mode');
        delete_option('ank_wp_ticket_api_key');
        delete_option('ank_wp_ticket_api_url');
    }
}

/*
*  ank_wp_ticket_logging_args
*
*  Filter for wp_logging_post_type_args
*  Used for enabling the logging
*
*  @since	1.0
*
*  @param	N/A
*  @return	array $log_args logging paramters
*/
if (!function_exists('ank_wp_ticket_logging_args')){
    function ank_wp_ticket_logging_args(){
        $log_args = array(
            'labels'          => array( 'name' => __( 'Logs', 'wp-logging' ) ),
            'public'          => true,
            'query_var'       => false,
            'rewrite'         => false,
            'capability_type' => 'post',
            'supports'        => array( 'title', 'editor' ),
            'can_export'      => false
        );

        return $log_args;
    }
}

