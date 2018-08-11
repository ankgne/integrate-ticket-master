=== Integrate Ticket Master ===
Contributors: ankgne
Donate link:
Tags: ticketmaster, ticketmaster events, ticketmaster shortcode, ticket master, ticketmaster API
Requires at least: 4.9.7
Tested up to: 4.9.7
Stable tag: 2.0.1
Requires PHP: 5.5.12
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin will enable a simple shortcode that you can use for embedding ticket master events in any WordPress post or page. The shortcode uses the ticket master <a href="https://developer.ticketmaster.com/products-and-docs/apis/discovery-api/v2/">Discovery API</a> to search for events, their timings, venue and ticket price details.

== Description ==

This plugin will enable a simple shortcode that you can use for embedding ticket master events in any WordPress post or page. The shortcode uses the ticket master <a href="https://developer.ticketmaster.com/products-and-docs/apis/discovery-api/v2/">Discovery API</a> to search for events, their timings, venue and ticket price details.

Event are displayed with the [ank_wp_ticket_get_event_shortcode] shortcode:


<pre><code>[ank_wp_get_events genre="music" event_count="12" dmaid="324" title="Events in LA"]
</code></pre>

Ticketmaster requires an API key to access discovery API to fetch event details. You can <a href="https://developer-acct.ticketmaster.com/user/register">register for an API here</a>.


The "Debug Mode" on "Ticket Master Settings" page is used for enabling logging of plugin. By enabling logging, a custom type named "Logs" is created in admin section where logs created can be viewed

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Obtain an API key <a href="https://developer-acct.ticketmaster.com/user/register">here</a>
1. Use "Ticket Master Settings" on admin screen to configure the plugin
1. Enter your ticketmaster consumer key details in "Consumer Key" field of plugin's setting page
1. Enter "https://app.ticketmaster.com/" as API URL in "Api URL" field of plugin's setting page


== Frequently Asked Questions ==

= What is dmaid ? =

DMA stands for Designated Market Areas. Ticket master identifies areas using dmaids. 
Designated Market Area (DMA) can be used to segment and target events to a specific audience. Each DMA groups several zipcodes into a specific market segmentation based on population demographics.

You can checkout the complete list of DMAids supported by ticketmaster <a href="https://developer.ticketmaster.com/products-and-docs/apis/discovery-api/v2/#supported-dma">here </a>


= What are the various values of dmaids? =


You can checkout the complete list of DMAids supported by ticketmaster <a href="https://developer.ticketmaster.com/products-and-docs/apis/discovery-api/v2/#supported-dma">here </a>

= What are the supported country codes? =
You can checkout the complete list of supported country codes <a href="https://developer.ticketmaster.com/products-and-docs/apis/discovery-api/v2/#supported-country-codes">here </a>



You can checkout the complete list of DMAids supported by ticketmaster <a href="https://developer.ticketmaster.com/products-and-docs/apis/discovery-api/v2/#supported-dma">here </a>

== Screenshots ==
 
1. Settings Page
2. Shortcode
3. Searched Events

== Changelog ==

= 1.0.0 =
First release!

= 1.0.1 =
Fixed the pagination bug when count of total pages is 0.

= 2.0.0 =
1) Updated the shortcode to have title for searched results
2) Added dropdown in setting page to get the results in sorted order (this required change in API url , setting pages)
3) UI enhancements:
	1) Added the logic on UI to filter the results by Segment/Genre of searched events
	2) Show page number details below the title of searched results
	3) Added animations to show event location and time on image hover
	4) Added event details on click of event image	
	5) Updated the date format of event on UI
4) Updated the logic of pagination to highlight active page

== Upgrade Notice ==
= 2.0.0 =
Enhanced UI options and option to filter and sort the results
