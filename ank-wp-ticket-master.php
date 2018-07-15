<?php

/*
  Plugin Name: Integrate Ticket Master
  Plugin URI:
  Description: A plugin to integrate ticket master with wordpress
  Author: Ankur Khurana
  Author URI:http://mywp.ooo
  COntributors:
  Version: 1.0.0
  @copyright   Copyright (c) 2018, Ankur Khurana
  @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

final class AK_TicketMaster_Wordpress { //final to avoid extension/inheritance of class

    public $ank_wp_ticket_version = '1.0.0';
    public $ak_ticket_api_key;
    protected static $_instance = null;


    public static $ank_wp_ticket_api_url = '';
    public static $ank_wp_ticket_api_key = '';
    public static $ank_wp_ticket_event_count = '';
    public static $ank_wp_ticket_country_code= '';
    public static $ank_wp_ticket_classificationName= '';
    public static $ank_wp_ticket_dmaid= '';

    /**
     * Initiates the plugin
     *
     * @access  public
     * @since 	1.0
     *
     *
     *
     * @return  instance object
     */

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }//Singleton pattern

    /**
     * __construct
     * Constuctor function
     *
     * @access  public
     * @since 	1.0
     *
     *
     * @return  void
     */

    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Defines Constants and enable logging based on debug flag from settings page of plugin
     *
     * @access  private
     * @since 	1.0
     *
     * @uses 	get_option(), add_filter()
     *
     * @return  void
     */
    private function define_constants() {
        if (!defined('AK_TICKETMASTER_BASE_URL')) {
            define('AK_TICKETMASTER_BASE_URL', plugin_dir_url(__FILE__));
        }
        if (!defined('AK_TICKETMASTER_BASE_DIR')) {
            define('AK_TICKETMASTER_BASE_DIR', dirname(__FILE__));
        }
        if (!defined('AK_TICKETMASTER_VERSION')) {
            define('AK_TICKETMASTER_VERSION', $this->ank_wp_ticket_version);
        }
        if (!defined('AK_TICKETMASTER_VERSION_KEY')){
            define('AK_TICKETMASTER_VERSION_KEY', 'ank_wp_ticket_version');
        }
        if (!defined('AK_TICKETMASTER_MINIMUM_WP_VERSION')){
            define( 'AK_TICKETMASTER_MINIMUM_WP_VERSION', '4.10' );
        }
        if (get_option('ank_wp_ticket_debug_mode')){
            add_filter('wp_logging_post_type_args',ank_wp_ticket_logging_args); //ank_wp_ticket_logging_args is defined in ank-wp-ticket-master-function.php
        }
    }

    /**
     * Includes required core files used in admin and on the frontend.
     * Instantiates logging class
     *
     * @access  private
     * @since 	1.0
     *
     * @uses 	is_admin()
     *
     * @return  void
     */
    private function includes() {
        include_once AK_TICKETMASTER_BASE_DIR . '/includes/classes/common/ank-wp-ticket-master-logging.php';
        include_once AK_TICKETMASTER_BASE_DIR . '/includes/classes/common/ank-wp-ticket-master-function.php';
        if (is_admin()) { // if user is admin
            include_once AK_TICKETMASTER_BASE_DIR . '/includes/classes/admin/settings/ank-wp-ticket-master-settings-page.php';
            $ank_wp_ticket_master_settings_page = new ank_wp_ticket_master_settings_page();
        } else { //request coming from frontend
            include_once AK_TICKETMASTER_BASE_DIR . '/includes/classes/frontend/ank-wp-ticket-master-frontend.php';
        }
    }

    /**
     * Hook into actions and filters
     *
     * @access  private
     * @since 	1.0
     *
     * @uses 	register_activation_hook(), register_uninstall_hook(), add_action()
     *
     * @return  void
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, 'ank_wp_ticket_activate');
        register_uninstall_hook(__FILE__, 'ank_wp_ticket_uninstall');

        //Activates pruning of old log files. The default time period is to prune logs that are over 2 weeks old
        function activate_pruning( $should_we_prune ){
            return true;
        } // rapid_activate_pruning
        add_filter( 'wp_logging_should_we_prune', 'activate_pruning', 10 );

        //Schedules a hook which will be executed by the WordPress actions core
        $scheduled = wp_next_scheduled( 'wp_logging_prune_routine' );
        if ( $scheduled == false ){
            wp_schedule_event( time(), 'hourly', 'wp_logging_prune_routine' );
        }

        //include template loader functions. This is used for frontend UI
        add_action('after_setup_theme', array($this, 'include_template_functions'), 11);
        add_action('init', array($this, 'init'), 0);
    }

    /**
     * Initiates the statics variables with values entered in setting page of plugin
     *
     * @access  public
     * @since 	1.0
     *
     * @uses 	get_option()
     *
     * @return  void
     */
    public function init() {
        AK_TicketMaster_Wordpress::$ank_wp_ticket_api_key = get_option('ank_wp_ticket_api_key');
        //AK_TicketMaster_Wordpress::$ank_wp_ticket_event_count = get_option('ank_wp_ticket_count_event_page');
        AK_TicketMaster_Wordpress::$ank_wp_ticket_api_url = get_option('ank_wp_ticket_api_url');
        //AK_TicketMaster_Wordpress::$ank_wp_ticket_country_code = get_option('ank_wp_ticket_country');
    }

    /**
     * Loads file containing common template functions
     *
     * @access  public
     * @since 	1.0
     *
     * @uses 	is_admin()
     *
     * @return  void
     */
    public function include_template_functions(){
        if ( ! is_admin() ){ // load for frontend only
            include_once( AK_TICKETMASTER_BASE_DIR . '/includes/classes/frontend/ank-wp-ticket-master-template-function.php' );
        }
    }
}

function ank_wp_ticker_master_main() {
    return AK_TicketMaster_Wordpress::instance();
}

//main call starts from here
ank_wp_ticker_master_main();