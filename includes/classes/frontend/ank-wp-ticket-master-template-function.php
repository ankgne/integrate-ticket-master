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
    <section id="references">
      <div class="container">
        <div class="col-sm-12">
            <div class=" text-center">
                <?php
                $title=ank_wp_ticket_get_event_title();
                if(!empty($title)){
                    echo "<h2 data-animate='fadeInUp' class='title'>$title</h2>";
                }?>
                <p data-animate="fadeInUp" id="resultstats" class="animated fadeInUp">Page <?php echo ($ank_wp_json_response -> page ->number +1) . " of about " . $ank_wp_json_response -> page ->totalElements . " results"  ?></p>
            </div>


            <ul id="filter" data-animate="fadeInUp">
                <li class="active"><a href="#" data-filter="all">All</a></li>
                 <?php
                 $ank_wp_ticket_classification_type="segment";
                 if (!empty(AK_TicketMaster_Wordpress::$ank_wp_ticket_classificationName)){ // if genre is set in shortcall code
                     $ank_wp_ticket_classification_type="genre";
                 }
                 $ank_wp_ticket_get_genres=ank_wp_ticket_get_unique_genre_segment($ank_wp_json_response,$ank_wp_ticket_classification_type);
                foreach ($ank_wp_ticket_get_genres as $genre) {
                    ?>
                    <li><a href="#" data-filter="<?php echo esc_html($genre) ?>"><?php echo esc_html($genre) ?></a></li>
                    <?php
                }
                ?>
            </ul>



            <div id="detail">
                <div class="row">
                    <div class="col-lg-10 mx-auto"><span class="close">×</span>
                        <div id="detail-slider" class="owl-carousel owl-theme"></div>
                        <div class="text-center">
                            <h1 id="detail-title" class="title"></h1>
                        </div>
                        <div id="detail-content"></div>
                    </div>
                </div>
            </div>


            <div id="references-masonry" data-animate="fadeInUp">
                <div class="row">
                <?php
                foreach ($ank_wp_json_response->_embedded->events as $events) {
                    $ank_wp_data_category=ank_wp_ticket_get_genre_segment_name($events,$ank_wp_ticket_classification_type);
                    ?>
                    <div data-category="<?php echo esc_html($ank_wp_data_category) ?>" class="reference-item col-lg-3 col-md-6 portfolio-item">
                        <div class="reference card h-100">
                            <a href="#"><img src="<?php echo esc_url(ank_wp_ticket_get_event_image($events,"640","16_9")) ?>" alt="<?php echo esc_attr($events->name) ?>" class="img-fluid">
                                <div class="overlay">
                                    <div class="inner">
                                        <?php echo ank_wp_ticket_get_event_address($events); ?>
                                        <?php echo ank_wp_ticket_get_event_datetime($events); ?>
                                    </div>
                                </div>
                            </a>

                            <h1 class="h6 reference-title"><?php echo esc_html($events->name);?></h1>
                            <div data-images="<?php echo esc_url(empty($events->seatmap->staticUrl)?(ank_wp_ticket_get_event_image($events,"640","16_9")):($events->seatmap->staticUrl))?>" class="sr-only reference-description">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6"><?php echo ank_wp_ticket_get_event_price($events); ?></div>

                                    <div class="col-lg-4 col-sm-6"><?php echo ank_wp_ticket_get_event_address($events); ?></div>

                                    <div class="col-lg-4 col-sm-6"><?php echo ank_wp_ticket_get_event_datetime($events); ?></div>
                                </div>

                                <p>
                                    <?php echo esc_html($events->pleaseNote) ?>
                                </p>
                                <p>
                                    <?php echo esc_html($events->accessibility->info) ?>
                                </p>
                                <p>
                                    <?php echo esc_html($events->ticketLimit->info) ?>
                                </p>
                                <p class="buttons text-center">
                                    <a href="<?php echo esc_url($events->url) ?>" class="btn btn-outline-primary"><i class="fa fa-ticket-alt"></i> Book Tickets</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php
                } ?>
                </div>
            </div>   <!--references-masonry ends here-->
        </div >   <!--col-sm-12 ends here-->
      </div>  <!--Containers end here-->
    </section> <!--Reference section end here-->
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
        $pagination_count=4;

        $total_pages=$ank_wp_json_response->page->totalPages;

        $current_page_number=$ank_wp_json_response->page->number;

        $redirect_url= get_permalink();
        $ank_wp_redirect_url = add_query_arg( $redirect_args, $redirect_url );

        //Took pagination calculation logic from here https://stackoverflow.com/questions/11272108/logic-behind-pagination-like-google
        $startPage = $current_page_number - $pagination_count;
        $endPage = $current_page_number + $pagination_count;

        if ($startPage <= 0) {
            $endPage -= ($startPage - 1);
            $startPage = 1;
        }

        if ($endPage > $total_pages)
            $endPage = $total_pages;

        ?>
        <!-- Pagination -->
        <ul class="pagination justify-content-center" id="pagination">
            <?php if ($startPage > 1) { ?>
                <li class="page-item">
                    <a class="page-link"
                       href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($startPage - 1)); ?>"
                       aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <?php
            }
            for($i=$startPage; $i<=$endPage; $i++)  {
                if ($current_page_number + 1 == $i){
                    $class="active";
                }else{
                    $class="";
                }
                    ?>
                    <li class="page-item <?php echo $class ?>">
                        <a class="page-link" href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($i-1) );?>"><?php echo $i ?></a> <!--current page-->
                    </li>
                    <?php
                }
            ?>

            <?php if ($endPage < $total_pages){//do not show if there are no remaining pages?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo esc_url($ank_wp_redirect_url . "&api_page=" . ($current_page_number+1) );?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            <?php } ?>
        </ul>
<?php
    }
}?>