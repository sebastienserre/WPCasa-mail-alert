=== WPCasa Mail Alert ===
Plugin Name: WPCasa Mail Alert
Plugin URI: https://www.thivinfo.com/downloads/wpcasa-mail-alert-pro/
Contributors: sebastien@thivinfo.com
Donate link: http://paypal.me/sebastienserre
Tags: wpcasa, update, post, mail, subscription, notifier, status, alert, translation ready, e-mail
Requires at least: 4.6
Requires PHP: 5.6
Tested up to: 4.9
Stable tag: 2.0.4
License: GPL V2 or later

== Description ==
WPCasa Mail Alert will display a widget to add a form on your Website using WPCasa Plugin from WPSIght. Once registred, you'll receive a mail when the RealEstate Agency will add or update a property corresponding to your search!

<h3>Features</h3>

<ul>
    <li>Allow Search city</li>
    <li>Allow search by minimum Price</li>
    <li>Allow search by maximum Price</li>
</ul>

<h3><a href='https://www.thivinfo.com/en/downloads/wpcasa-mail-alert-pro/ref/4/' title='Link to WPCasa Mail Alert Pro' >WPCasa Mail Alert PRO</a></h3>

<ul>
	<li>Allow search by all of standards features form WPCasa</li>
	<li>Allow export of subscribers list in CSV format</li>
	<li>Add a Shortcode to display the form where ever you want</li>
</ul>


== Installation ==
* 1- unzip
* 2- upload to wp-content/plugin
* 3- Go to your dashboard to activate it
* 4- have fun!

== Frequently Asked Questions ==
= How to have a subscription field in my website? =
* Please use the Widget called "Mail Alert" in a sidebar.

= Is my subscriber can easily unsubscribe to the mail? =
* Yes! They have to click on the link present on all mail received or make a link somewhere to the unsubscribe page created at the plugin activation.


== Upgrade Notice ==

use automatic upgrade

== Screenshots ==
1. Widget
2. Subscriber List
3. General options
4. E-mail Options

== Changelog ==
* 2.0.4 -- 18 june 2018
    Add a filter to feat old WPCasa Framework CPT.
    Add in your (child) themes functions.php:
    function wpcasama_change_cpt($cpt){
    	return 'property';
    }
    add_filter('wpcasama/cpt', 'wpcasama_change_cpt');

* 2.0.3 14 june 2018 --
    bugfix in min/max price criterias

* 2.0.2 3rd june 2018 --
    Add an example of text with data collected by WPCasa Mail Alert to be Compliant with RGPD

* 2.0.1 1st May 2018 --
    remove PHP Notices

* 2.0.0 April 2018 -- Great Improvement
    Alerts are now Custom Post Type.
    Criterias are Custom Metabox.
    Add a Front subscribers account (by shortcode)
    Subscribers are now WP User (need allow user registration).

* 1.2.6 Add style to subscribers list
* 1.2.5 Fix a function name to avoid conflicts
* 1.2.4 Prepare way for Pro version features
* 1.2.3 Add Filter Hooks
* 1.2.2 Improve translations - Remove unused old files
* 1.2.0 Settings are merged in WPCasa Setting page for better User Experience
* 1.1.5 use currency unit from WPCasa Settings
* 1.1.3 add light CSS to Widget
* 1.1.1 add style and correct some small bug
* 1.1.0 adaptation for premium plugin
* 1.0.0 Initial version
