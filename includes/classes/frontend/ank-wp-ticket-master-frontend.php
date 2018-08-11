<?php

/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/27/2018
 * Time: 12:29 PM
 */
include_once AK_TICKETMASTER_BASE_DIR . '/includes/classes/common/ank-wp-ticket-master-scripts.php'; // because the sheets are required on front end only
include_once AK_TICKETMASTER_BASE_DIR . '/includes/classes/common/ank-wp-ticket-template-loader.php'; //this file is not used as of now but will be used in future release
require_once(AK_TICKETMASTER_BASE_DIR . '/includes/classes/api/ank-wp-ticket-master-api-call.php');


class Ank_Wp_Ticket_Frontend
{

    function __construct() {
        //add short code implementation logic
        add_shortcode('ank_wp_get_events', array($this, 'ank_wp_ticket_get_event_shortcode'));
    }

    /**
     * Implementation of short code to get event details based on the arguments set in short code
     *
     * @access  public
     * @since 	1.0
     *
     * @uses 	shortcode_atts()
     * @param   {array} $atts
     *
     * @return  void
     */
    public function ank_wp_ticket_get_event_shortcode($atts) {
        $atts = shortcode_atts(array('genre' => '', 'event_count' => '10', 'dmaid' => '', 'country_code' => 'US', 'title'=>''), $atts, 'ank_wp_ticket_get_event_shortcode');

        //shortcode overwrites the settings page values
        if (isset($atts['genre'])) {
            AK_TicketMaster_Wordpress::$ank_wp_ticket_classificationName=$atts['genre'];
        }if (isset($atts['dmaid'])) {
            AK_TicketMaster_Wordpress::$ank_wp_ticket_dmaid=$atts['dmaid'];
        } if (isset($atts['event_count']))  {
            AK_TicketMaster_Wordpress::$ank_wp_ticket_event_count=$atts['event_count'];
        }  if (isset($atts['country_code']))  {
            AK_TicketMaster_Wordpress::$ank_wp_ticket_country_code=$atts['country_code'];
        }if (isset($atts['title']))  {
            AK_TicketMaster_Wordpress::$ank_wp_ticket_event_title=$atts['title'];
        }
        //Displays details of the event based on agruements set above
        ank_wp_display_ticket_search_event();
    }

}


add_action( 'init', 'ank_wp_ticket_frontend_init', 11 );

/**
 * This function instantiates Ank_Wp_Ticket_Frontend class
 *
 * @type	action (init)
 * @since 	1.0
 *
 * @param   {array} $atts
 * @return  void
 */
function ank_wp_ticket_frontend_init(){
    $ank_wp_ticket_frontend = new Ank_Wp_Ticket_Frontend();
}

new ank_wp_ticket_scripts(); // load js and css
//ank_wp_template_loader::init(); // initiate the loaders