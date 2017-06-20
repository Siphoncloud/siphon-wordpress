=== Plugin Name ===
Contributors: siphon
Link: http://siphon.com/
Tags: comments, spam, bots, traffic filter, spam protection, bot protection, campaigns, marketing, click fraud
Requires at least: 3.0.1
Tested up to: 4.7.1
Stable tag: 4.7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows a user to easily install their Siphon traffic filter.


== Description ==

This plugin allows a user to easily install their Siphon traffic filter.

Using this plugin allows WordPress administrators to install their Siphon traffic filter easily without having to change
their theme files.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/siphon` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Siphon menu to access the settings page
4. Once on the settings page ensure that you see a confirmation box that your server supports Siphon.
5. Upload your Siphon filter .php file that you received from your Siphon dashboard.


== Frequently Asked Questions ==

= Is an active subscription with Siphon required to install this plugin? =

No. However if you wish to actually filter any traffic you must have an active filter in your Siphon dashboard.

= Where can I get my filter file for uploading? =

Navigate to your Siphon Traffic Filter dashboard and find the filter that you want to use, click the download button.

== Changelog ==

= 1.7.0 =
* Updated this readme file to be more accurate
* Added check for ZipArchive class on upload screen

= 1.6.7 =
* Changed hook point to work better with newer themes

= 1.6.6 =
* Bug fix with previous update

= 1.6.5 =
* Bug fix

= 1.6.4 =
* Bug fix for Cron loop back
* 4.7.1 Tested

= 1.6.3 =
* Bug fix for auto updating

= 1.6 =
* GitHub release
* README.md moved

= 1.5 =
* Everyone was uploading their filter file as a zip, so now that won't break the site

= 1.4 =
* Adds earlier php support
* Stops execution on wp-cron

= 1.3 =
* Made check to ensure users are not uploading zip files

= 1.2 =
* Change action hook to fire earlier in site load

= 1.1 =
* Added better cookie support
* Added maneuver support
* Fixed maintenance page bug

= 1.0 =
* Release