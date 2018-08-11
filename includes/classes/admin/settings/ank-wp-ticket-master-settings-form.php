<?php

/**
 * Created by PhpStorm.
 * User: Ankur Khurana
 * Date: 6/24/2018
 * Time: 11:15 AM
 */
class ank_wp_ticket_settings_form
{

    /**
     * ank_wp_ticket_render_options_page
     * For rendering the output of setting page as a form
     *
     * @access  public
     * @since 	1.0
     *
     * @uses 	settings_fields()
     * @uses 	do_settings_sections()
     * @uses 	submit_button()
     *
     * @return  void
     */
    public function ank_wp_ticket_render_options_page() {
        ?>
        <div class="wrap">
        <h1><?php _e('Ticket Master Settings', 'ank_wp_ticket'); ?></h1>
        <?php if (isset($_GET['settings-updated'])) { ?>
            <div id="message" class="updated ank-wp-ticket-setting-message">
                <p><strong><?php _e('Ticket Master Settings saved.', 'ank_wp_ticket') ?></strong></p>
            </div>
        <?php } ?>
            <form method="post" action="options.php">


                <?php
                //add_settings_section callback is displayed here. For every new section we need to call settings_fields
                settings_fields('ank_wp_ticket_settings_section');

                // all the add_settings_field callbacks is displayed here
                do_settings_sections("ticket-master-settings");//url name of the setting page

                    // Add the submit button to serialize the options
                submit_button();
                ?>


            </form>
        </div>
        <?php
    }

    /**
     * ank_wp_ticket_settings_display_options
     * For adding and registering fields of setting page
     *
     * @access  public
     * @since 	1.0
     *
     * @uses 	add_settings_section()
     * @uses 	add_settings_field()
     * @uses 	register_setting()
     *
     * @return  void
     */
    public function ank_wp_ticket_settings_display_options()
        {
            //section name, display name, callback to print description of section, page to which section is attached.
            add_settings_section("ank_wp_ticket_settings_section", "Ticket Master  Options",array($this, 'ank_wp_ticket_display_header_options_content'), "ticket-master-settings");

            //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
            //last field section is optional.
            add_settings_field("ank_wp_ticket_debug_mode", "Debug Mode (Create 'Logs' in admin )", array($this, 'ank_wp_ticket_display_debug_mode'), "ticket-master-settings", "ank_wp_ticket_settings_section");
            add_settings_field("ank_wp_ticket_api_key", "Consumer Key", array($this, 'ank_wp_ticket_display_api_key'), "ticket-master-settings", "ank_wp_ticket_settings_section");
            //add_settings_field("ank_wp_ticket_count_event_page", "Number of events per page", array($this, 'ank_wp_ticket_display_number_event_page'), "ticket-master-settings", "ank_wp_ticket_settings_section");
            add_settings_field("ank_wp_ticket_api_url", "Api URL", array($this, 'ank_wp_ticket_display_api_url'), "ticket-master-settings", "ank_wp_ticket_settings_section");
            //add_settings_field("ank_wp_ticket_country", "Enter your country code", array($this, 'ank_wp_ticket_display_country'), "ticket-master-settings", "ank_wp_ticket_settings_section");
            add_settings_field("ank_wp_ticket_event_sort_by", "Sort event search result by", array($this, 'ank_wp_ticket_event_sort_by'), "ticket-master-settings", "ank_wp_ticket_settings_section");

            //section name, form element name, callback for sanitization
            register_setting("ank_wp_ticket_settings_section", "ank_wp_ticket_debug_mode");
            register_setting("ank_wp_ticket_settings_section", "ank_wp_ticket_api_key");
            //register_setting("ank_wp_ticket_settings_section", "ank_wp_ticket_count_event_page");
            register_setting("ank_wp_ticket_settings_section", "ank_wp_ticket_api_url");
            //register_setting("ank_wp_ticket_settings_section", "ank_wp_ticket_country");
            register_setting("ank_wp_ticket_settings_section", "ank_wp_ticket_event_sort_by");
        }

    public function ank_wp_ticket_display_header_options_content(){
        echo "";
    }

    public function ank_wp_ticket_display_debug_mode()
    {
        //id and name of form element should be same as the setting name.
        ?>
        <input type="checkbox" name="ank_wp_ticket_debug_mode"  value="1" <?php checked(1,get_option('ank_wp_ticket_debug_mode'),true); ?> />
        <?php
    }

    public function ank_wp_ticket_display_api_key()
    {
        //id and name of form element should be same as the setting name.
        ?>
        <input type="text" name="ank_wp_ticket_api_key" id="ank_wp_ticket_api_key" value="<?php echo get_option('ank_wp_ticket_api_key'); ?>" />
        <?php
    }

    public function ank_wp_ticket_display_number_event_page()
    {
        //id and name of form element should be same as the setting name.
        ?>
        <input type="text" name="ank_wp_ticket_count_event_page" id="ank_wp_ticket_count_event_page" value="<?php echo get_option('ank_wp_ticket_count_event_page'); ?>" />
        <?php
    }

    public function ank_wp_ticket_display_api_url()
    {
        //id and name of form element should be same as the setting name.
        ?>
        <input type="text" name="ank_wp_ticket_api_url" id="ank_wp_ticket_api_url" value="<?php echo get_option('ank_wp_ticket_api_url'); ?>" />
        <?php
    }

    public function ank_wp_ticket_display_country()
    {
        //id and name of form element should be same as the setting name.
        ?>
        <input type="text" name="ank_wp_ticket_country" id="ank_wp_ticket_country" value="<?php echo get_option('ank_wp_ticket_country'); ?>" />
        <?php
    }

    public function ank_wp_ticket_event_sort_by()
    {
        $options=get_option('ank_wp_ticket_event_sort_by');
        $items = array("Event Name Ascending"=>"name,asc", "Event Name Descending" => "name,desc", "Event Date Ascending" => 'date,asc',
            "Event Date Descending" => 'date,desc', "Relevancy Ascending" => 'relevance,asc',"Relevancy Descending" => 'relevance,desc',
            "Event name and date Ascending" => 'name,date,asc',"Event name and date Descending" => 'name,date,desc',);
        //id and name of form element should be same as the setting name.

        echo "<select name='ank_wp_ticket_event_sort_by' id='ank_wp_ticket_event_sort_by'>";
        foreach($items as $name => $value) {
            $selected = ($options==$value) ? 'selected="selected"' : '';
            echo "<option value='$value' $selected>$name</option>";
        }
    }

}