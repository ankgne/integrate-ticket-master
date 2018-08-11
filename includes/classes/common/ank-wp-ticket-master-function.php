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

/*
*  ank_wp_ticket_get_unique_genre_segment
*
*  Get unique genres from API response
*
*  @since	1.0.2
*
*  @param	JSON response
*  @return	array $ank_wp_json_response_genres
*/
if (!function_exists('ank_wp_ticket_get_unique_genre_segment')){
    function ank_wp_ticket_get_unique_genre_segment($ank_wp_json_response,$category){
        $ank_wp_json_response_genres=array();

        foreach ($ank_wp_json_response->_embedded->events as $events) {

            if ($category == "genre"){
                $ank_wp_response_genre=$events->classifications[0]->genre->name;
            }else{
                $ank_wp_response_genre=$events->classifications[0]->segment->name;
            }

            if (!in_array($ank_wp_response_genre, $ank_wp_json_response_genres))// to avoid duplication
            {
                if (($ank_wp_response_genre <> "Undefined") && (!empty($ank_wp_response_genre))){
                    array_push($ank_wp_json_response_genres,$ank_wp_response_genre);
                }

            }
        }
        sort($ank_wp_json_response_genres); //return name sorted results
        return $ank_wp_json_response_genres;
    }
}

/*
*  ank_wp_ticket_get_genre_segment_name
*
*  Get genre name
*
*  @since	1.0.2
*
*  @param	$ank_wp_ticket_classification_type
*  @return	name of genre
*/
if (!function_exists('ank_wp_ticket_get_genre_segment_name')){
    function ank_wp_ticket_get_genre_segment_name($events,$ank_wp_ticket_classification_type){
        if ($ank_wp_ticket_classification_type == "genre"){
            $ank_wp_data_category=$events->classifications[0]->genre->name;
        }else{
            $ank_wp_data_category=$events->classifications[0]->segment->name;
        }
        return $ank_wp_data_category;
    }
}


/*
*  ank_wp_ticket_get_event_price
*
*  Get genre name
*
*  @since	1.0.2
*
*  @param	$events
*  @return	price of event
*/
if (!function_exists('ank_wp_ticket_get_event_price')){
    function ank_wp_ticket_get_event_price($events){

        ob_start(); ?>

        <p>
            <span class="fa fa-ticket-alt"></span>
            <?php if (empty ($events->priceRanges[0]->min)) {
                echo "Price Range " . "Not Available";
            }
            else{
                echo "Price Range " . esc_html($events->priceRanges[0]->min) . "-" . esc_html($events->priceRanges[0]->max) . " " . esc_html($events->priceRanges[0]->currency);
            }?>
        </p>
        <?php

        $event_price = ob_get_contents();

        ob_end_clean();

        return $event_price;
    }
}


/*
*  ank_wp_ticket_get_event_address
*
*  Get genre name
*
*  @since	1.0.2
*
*  @param	$events
*  @return	Address of event
*/
if (!function_exists('ank_wp_ticket_get_event_address')){
    function ank_wp_ticket_get_event_address($events){

        ob_start(); ?>

        <p>
            <span class="fa fa-map-marker "> </span><?php echo(" " . esc_html($events->_embedded->venues[0]->name) . ",");
            echo(esc_html($events->_embedded->venues[0]->city->name) . ",");
            echo(esc_html($events->_embedded->venues[0]->state->stateCode)); ?>
        </p>

        <?php

        $event_address = ob_get_contents();

        ob_end_clean();

        return $event_address;
    }
}

/*
*  ank_wp_ticket_get_event_datetime
*
*  Get genre name
*
*  @since	1.0.2
*
*  @param	$events
*  @return	Address of event
*/
if (!function_exists('ank_wp_ticket_get_event_datetime')){
    function ank_wp_ticket_get_event_datetime($events){

        ob_start(); ?>

        <p><span
                class="fa fa-clock"></span><?php echo esc_html((date(" D M d Y @ h:ia", strtotime($events->dates->start->localDate . " " . $events->dates->start->localTime)))) ?>
        </p>

        <?php

        $event_datetime = ob_get_contents();

        ob_end_clean();

        return $event_datetime;
    }
}

/*
*  ank_wp_ticket_get_event_image
*
*  Returns the event image url based on width and ratio
*
*  @since	1.0.2
*
*  @param	$events
*  @param	$width
*  @param	$ratio
*
*  @return	array $log_args logging paramters
*/
if (!function_exists('ank_wp_ticket_get_event_image')){
    function ank_wp_ticket_get_event_image($events,$width,$ratio){
    $featured_image_url=$events->images[0]->url;  //default is first image
        foreach ($events->images as $images){
            if ($images->width == $width){
                $featured_image_url=$images->url;
                break;
            }
        }
        return $featured_image_url;
    }
}


/*
*  ank_wp_ticket_get_event_title
*
*  Returns the title set in shortcode
*
*  @since	1.0.2
*
*  @return	title
*/
if (!function_exists('ank_wp_ticket_get_event_title')){
    function ank_wp_ticket_get_event_title(){
        return (AK_TicketMaster_Wordpress::$ank_wp_ticket_event_title);
    }
}
