<?php

/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/28/2018
 * Time: 2:24 AM
 */


/**
 * Function to locate template files
 * @todo    This function is not being used in 1.0 but will be used in future release
 * @since 	1.0
 *
 * @uses   trailingslashit()
 *
 * @return  json response of events
 */
function ank_wp_ticket_locate_template($template) {
    $check_dirs = array(
        trailingslashit(get_stylesheet_directory()) . '/templates',
        trailingslashit(get_template_directory()) . '/templates',
        trailingslashit(get_stylesheet_directory()),
        trailingslashit(get_template_directory()),
        trailingslashit(AK_TICKETMASTER_BASE_DIR) . '/includes/classes/templates/'
    );

    foreach ($check_dirs as $dir) {
        if (file_exists(trailingslashit($dir) . $template)) {
            return trailingslashit($dir) . $template;
        }
    }
}

/**
 * Retrieves events from discover API
 *
 * @since 	1.0
 *
 *
 * @return  json response of events
 */
if (!function_exists('ank_wp_get_ticket_search_event')){

    function ank_wp_get_ticket_search_event(){
        $ank_wp_ticket_api_call=new ank_wp_ticket_api_call('search_events');
        $json_response=$ank_wp_ticket_api_call->ank_wp_get_search_event_result();
        return $json_response;
    }

}

/**
 * This function gets the response from discovery API and takes care of rendering searched events on a page/post
 *
 * @since 	1.0
 *
 * @uses 		esc_url()
 * @uses 		esc_attr()
 * @uses 		esc_html()
 *
 *
 * @return  void
 */
if (!function_exists('ank_wp_display_ticket_search_event')) {
    function ank_wp_display_ticket_search_event(){

        //API call
        $ank_wp_json_response = ank_wp_get_ticket_search_event();


        if (null === $ank_wp_json_response) {//error message
            ?>
            <div class="alert alert-danger" role="alert">
                <strong>Oh snap!</strong> There was a problem communicating with the Discovery API...
            </div>
            <?php
        } else {//successful response
            ?>
            <div class="row">
                <?php
                foreach ($ank_wp_json_response->_embedded->events as $events) {
                    ?>
                    <div class="col-lg-4 col-sm-6 portfolio-item">
                        <div class="card h-100">
                            <a href="<?php echo esc_url($events->url) ?>"><img class="card-img-top"
                                                                      src="<?php echo esc_url($events->images[0]->url) ?>"
                                                                      alt="<?php echo esc_attr($events->name) ?>"></a>

                            <div class="card-body">
                                <h4 class="card-title">
                                    <a href="<?php echo esc_url($events->url) ?>"><?php echo esc_html($events->name) ?></a>
                                </h4>

                                <p class="card-text"><span
                                        class="fa fa-ticket-alt"></span><?php echo("Price Range " . esc_html($events->priceRanges[0]->min) . "-" . esc_html($events->priceRanges[0]->max) . " " . esc_html($events->priceRanges[0]->currency)) ?>
                                </p>

                                <p class="card-text"><span
                                        class="fa fa-map-marker "> </span><?php echo(" " . esc_html($events->_embedded->venues[0]->name) . ",");
                                    echo(esc_html($events->_embedded->venues[0]->city->name) . ",");
                                    echo(esc_html($events->_embedded->venues[0]->state->stateCode)); ?></p>

                                <p class="card-text"><span
                                        class="fa fa-clock"></span><?php echo esc_html((date(" D m/d @ h:ia", strtotime($events->dates->start->localDate . " " . $events->dates->start->localTime)))) ?>
                                </p>
                            </div>

                            <?php
                            /*print_r($events->name);
                            print_r($events->id);*/
                            //print_r($events->images[0]->url);
                            /*foreach($events->images as $images){
                                print_r($images->url);
                            }*/
                            ?>
                        </div>
                    </div>
                    <?php
                } ?>
            </div>
            <?php
            ank_wp_display_ticket_search_event_pagination($ank_wp_json_response);
        }
    }
}


/**
 * This function handles the logic of pagination for searched events
 *
 * @since 	1.0
 *
 * @uses 		get_permalink()
 * @uses 		add_query_arg()
 * @uses 		esc_url()
 *
 * @param   json valid response from discovery API
 *
 * @return  void
 */
if (!function_exists('ank_wp_display_ticket_search_event_pagination')) {
    function ank_wp_display_ticket_search_event_pagination($ank_wp_json_response){
        $redirect_args=array ("ank-wp-event-details"=>true); //used in template loader to load template

        $chunks = array_chunk(preg_split('/(=|&)/', $ank_wp_json_response->_links->next->href), 2);
        $next_url = array_combine(array_column($chunks, 0), array_column($chunks, 1));

        $page_size=$ank_wp_json_response->page->size;
        $total_pages=$ank_wp_json_response->page->totalPages;

        $next_page_number=($next_url['page']);
        $current_page_number=$next_page_number-1;

        $remaining_page=$total_pages-($current_page_number+3);

        $redirect_url= get_permalink();
        $ank_wp_redirect_url = add_query_arg( $redirect_args, $redirect_url );

        ?>
        <!-- Pagination -->
        <ul class="pagination justify-content-center">
            <?php if ($current_page_number != 0){?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($current_page_number-1) );?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
            <?php } ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($current_page_number) );?>"><?php echo $current_page_number + 1 ?></a> <!--current page-->
            </li>
            <li class="page-item">
                <a class="page-link" href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($current_page_number + 1 ) );?>"><?php echo $current_page_number + 2 ?></a> <!--next page-->
            </li>
            <li class="page-item">
                <a class="page-link" href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($current_page_number + 2) );?>"><?php echo $current_page_number + 3 ?></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($current_page_number + 3) );?>"><?php echo $current_page_number + 4 ?></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($current_page_number + 4) );?>"><?php echo $current_page_number + 5 ?></a>
            </li>
            <?php if ($remaining_page != 0){//do not show if there are no remaining pages?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($current_page_number + 5) );?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
<?php
    }
}?>