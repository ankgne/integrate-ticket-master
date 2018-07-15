<?php

/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/27/2018
 * Time: 1:09 PM
 */
class ank_wp_ticket_searchevents
{
    /**
     * ank_wp_ticket_searchevents constructor.
     */
    public function ank_wp_ticket_get_searchevents($api_url)
    {
        $title='Get Search Events API Call';
        $message='Api called with url ' . $api_url;
        $type= 'event';
        WP_Logging::add( $title, $message, $type );

        $response 	= wp_remote_get( $api_url );

        //echo $response['response']['code'];
        if( is_wp_error( $response ) ) {

            $title='Get Search Events API Call Failed';
            $message='Response Received is ' .  json_encode($response);
            $type= 'error';
            WP_Logging::add( $title, $message, $type );

            $response_json=null;
            return $response_json;
        }
        $data = wp_remote_retrieve_body( $response );
        if( is_wp_error( $data ) ) {

            $title='Get Search Events API Call Failed 2';
            $message='Response Received is ' . json_encode($response);
            $type= 'error';
            WP_Logging::add( $title, $message, $type );

            $response_json=null;
            return $response_json;
        }
        if ( $response['response']['code'] == 200 ) {

            $response_json = json_decode( $data );
            $title='Get Search Events API Call Successful';
            $message='Response Received is ' . $data;
            $type= 'event';
            WP_Logging::add( $title, $message, $type );

            //print_r($response_json);
            return $response_json;
        } else { //error
            $title='Get Search Events API Call Failed 3';
            $message='Response Received is ' . $data;
            $type= 'error';
            WP_Logging::add( $title, $message, $type );
            $response_json = null;
        }

    }
}
?>