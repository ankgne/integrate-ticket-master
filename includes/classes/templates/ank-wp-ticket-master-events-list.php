<?php
/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/28/2018
 * Time: 2:38 AM
 */
get_header();?>

    <div id="main-content" class="main-content">


        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">
<?php
    //ank_wp_ticket_start_template_wrapper();

    //TODO main content function to be added here
            ank_wp_display_ticket_search_event();


        /*print_r($data->_embedded->events[0]->name);*/
        //create associated array of the hyperlink
?>
            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main-content -->
<?php
    //get_sidebar();
    get_footer();
?>