<?php

/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/27/2018
 * Time: 12:35 PM
 */

include(AK_TICKETMASTER_BASE_DIR . '/includes/classes/api/ank-wp-ticket-master-searchevents.php');
class ank_wp_ticket_api_call
{
    // Discover API
    private $ank_wp_discover_api_setting = array ();
    private $ank_wp_discover_api_json_response;

    /**
     * ank_wp_ticket_api_call constructor.
     */
    public function __construct($api_type)
    {
        $ank_wp_ticker_api_url_with_query_string = $this->ank_wp_ticket_get_api_url($api_type);
        switch ($api_type) {
            case 'search_events':
                $this->ank_wp_ticket_set_search_event($ank_wp_ticker_api_url_with_query_string);
                break;
            case 'default':
                $this->ank_wp_ticket_set_search_event($ank_wp_ticker_api_url_with_query_string); //keeping the default as search events only
                break;
        }
    }

    /**
     * Checks type of request and prepares API endpoint url based on request type
     *
     * @access  private
     * @since 	1.0
     *
     * @uses 	add_query_arg()
     *
     * @return  API URL with query strings
     */
    private function ank_wp_ticket_get_api_url($api_type){
        $ank_wp_ticket_api_url_with_query_string="";
        $title='API Wrapper Call';
        $message='API Wrapper Called with  ' . $api_type;
        $type= 'event';
        WP_Logging::add( $title, $message, $type );
        if ($api_type === 'search_events'){ //search events

            $this->ank_wp_discover_api_setting=array (
                "apikey"=>AK_TicketMaster_Wordpress::$ank_wp_ticket_api_key,
                "size"=>AK_TicketMaster_Wordpress::$ank_wp_ticket_event_count,
                "countryCode"=>AK_TicketMaster_Wordpress::$ank_wp_ticket_country_code,
                "classificationName"=>AK_TicketMaster_Wordpress::$ank_wp_ticket_classificationName,
                "dmaId"=>AK_TicketMaster_Wordpress::$ank_wp_ticket_dmaid,
                "sort"=>AK_TicketMaster_Wordpress::$ank_wp_ticket_event_sort_by
            );

            $args       = apply_filters( 'ank_wp_ticket_discovery_query_args', $this->ank_wp_discover_api_setting );
            //forming request to vendor API
            $ank_wp_discover_api_url = AK_TicketMaster_Wordpress::$ank_wp_ticket_api_url . 'discovery/v2/events.json';
            $ank_wp_ticket_api_url_with_query_string = add_query_arg( $args, $ank_wp_discover_api_url );
        }
        return $ank_wp_ticket_api_url_with_query_string;
    }

    /**
     * Calls discover API method to get events based on API URL and its query string. This method also "sets" resultant
     *
     * @access  private
     * @since 	1.0
     *
     * @uses 	add_query_arg(), sanitize_text_field()
     *
     * @return  void
     */
    private function ank_wp_ticket_set_search_event($api_url_with_query_string){
        if(isset($_GET['api_page'])){//request coming from pagination hyper links then additional parameter (i.e. ank-wp-event-details) comes in url
            $extracted_page_number=sanitize_text_field($_GET['api_page']);//extract page number coming in get request
            //$updated_args_page=str_replace("api_page","page",$_SERVER['QUERY_STRING']);
            $api_url_with_query_string=$api_url_with_query_string . "&page=" . $extracted_page_number;
        }
        $ank_wp_ticket_call_search_event=new ank_wp_ticket_searchevents();
        $this->ank_wp_discover_api_json_response = $ank_wp_ticket_call_search_event->ank_wp_ticket_get_searchevents($api_url_with_query_string);
    }

    public function ank_wp_get_search_event_result(){
        return $this->ank_wp_discover_api_json_response;
    }


}