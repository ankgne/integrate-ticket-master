<?php

/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/28/2018
 * Time: 2:16 AM
 */
class ank_wp_template_loader
{
    public static function init() {
        //This filter hook is executed immediately before WordPress includes the predetermined template file.
        // This can be used to override WordPress's default template behavior
        add_filter('template_include', array(__CLASS__, 'template_loader'));
    }

    public static function template_loader($template) {
        if (isset($_GET['ank-wp-venue-details'])) { // request coming from stripe checkout page
                //$template_file_path = ank_wp_ticket_locate_template("AK_Stripe_Thankyou.php");
        } elseif(isset($_GET['ank-wp-venue-details'])) {
            //$template_file_path = ank_wp_ticket_locate_template("AK_Stripe_Failed.php");
        }elseif(isset($_GET['ank-wp-event-details'])) {//used for pagination on events search results
            $template_file_path = ank_wp_ticket_locate_template('ank-wp-ticket-master-events-list.php');
        }
        else{// default page is event list page
            $template_file_path = ank_wp_ticket_locate_template('ank-wp-ticket-master-events-list.php');
        }
        require_once( $template_file_path );
    }
}