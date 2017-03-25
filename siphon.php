<?php
/**
 * Plugin Name: Siphon
 * Plugin URI: https://siphoncloud.com
 * Description: Allow users to easily setup Siphon traffic filter on their WordPress site without having to mess with code or templates
 * Version: 1.7.0
 * Author: Siphon
 */

if ( ! defined( 'ABSPATH' ) ) exit;


if(!class_exists('siphon')){
    class siphon{
        /**
         * siphon constructor.
         */
        public function __construct(){
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
        }

        /**
         * Activate the plugin
         */
        public static function activate(){
            // Do nothing special
        } // end public static function activate

        /**
         * Deactivate the plugin
         */
        public static function deactivate(){
            // Do nothing special
        } // end public static function deactivate

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init(){
            // Do settings method
            $this->init_settings();
        } // END public static function activate

        /**
         * Initialize some custom settings
         */
        public function init_settings(){
            // register the settings for plugin
            register_setting('siphon-group', 'setting_a');
            register_setting('siphon-group', 'setting_b');
        } // END public function init_custom_settings()

        /**
         * add a menu
         */
        public function add_menu(){
            add_menu_page('Siphon Settings', 'Siphon', 'manage_options', 'siphon', array(&$this, 'plugin_settings_page'), plugins_url()."/siphon/templates/img/small_logo.png");

        } // END public function add_menu()

        /**
         * Menu Callback
         */
        public function plugin_settings_page(){
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }
            else{
                // Render the settings template
                include(dirname(__FILE__)."/templates/settings.php");
            }
        } // END public function plugin_settings_page()

        public function runFilter(){
            $upload_dir = wp_upload_dir();
            if(is_file($upload_dir['basedir']."/siphon/siphonfilterloaded.php")){
                if(!defined( 'DOING_CRON' )){
                    require_once($upload_dir['basedir']."/siphon/siphonfilterloaded.php");
                }
            }
        } // END public function runFilter()

        public function doubleVerify(){
           return "<script async id=\"augur.js\" src=\"//cdn.siphon-api.com/static/dualVerify.js\" data-warpspeed=\".".$_SESSION['siphonvalues']['warp']."\" data-schema=\"v4\" data-sendto=\"POST::https://siphon-api.com/dualVerify.php\" data-id=\"".$this->buildIdString()."\" data-silent=\"1\" data-dwn=\"1\"></script>";

        } // END public function runFilter()

        public function buildIdString(){
            return $_SESSION['siphonvalues']['id'].":".$_SESSION['siphonvalues']['token'].":".$_SESSION['siphonvalues']['uid'].":".$_COOKIE['fhid'];
        } // END public function buildIdString()

    }// end class
}//end if class exists
if(class_exists('siphon')){
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('siphon', 'activate'));
    register_deactivation_hook(__FILE__, array('siphon', 'deactivate'));

    // create plugin object
    if ( defined('DOING_CRON')){
        // Do nothing
    }
    else{
        $siphon = new siphon();
        if(isset($siphon)){
            // Add the settings link to the plugins page
            function plugin_settings_link($links){
                $settings_link = '<a href="options-general.php?page=siphon">Settings</a>';
                array_unshift($links, $settings_link);

                return $links;
            }

            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", 'plugin_settings_link');

            /**
             * Runs the traffic filter file
             */
            function runSiphon(){
                global $siphon;
                $siphon->runFilter();
            }
            add_action('plugins_loaded', 'runSiphon');
        }
        if(!isset($_COOKIE['doubleverify']) && isset($_COOKIE['fhid']) && isset($_SESSION['siphonvalues'])){
            function doubleVerifySiphon(){
                global $siphon;
                $siphon->doubleVerify();
            }
            add_action('wp_head', 'doubleVerifySiphon');
        }
    }
}

