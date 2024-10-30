<?php

/**
 * @package Send secrets by Kaboom

 */
/*
Plugin Name: Kaboom Send Secrets
Description: This plugin makes it possible to send secrets to your clients. You use the shortcode [stand_alone_send_secret], there will appear an input field to send the information to your client.
Version: 1.0.4
Author: Kaboom
Author URI: https://app.kaboom.website
License: GPLv2 or later
Text Domain: Send secrets by Kaboom
*/

/*
Send secrets by Kaboom is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or
any later version.

Send secrets by Kaboom is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Send secrets by Kaboom.
*/

defined( 'ABSPATH' ) or die( 'This is a very secure plugin!' );

class SendSecretsByKaboom {

    function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_to_menu' ) );
        add_shortcode( 'stand_alone_send_secret', array( $this, 'stand_alone_send_secret' ) );
        register_activation_hook(__FILE__, array( $this, 'install' ));        
    }

    function install(){
        update_option('send_secrets_only_admin', 0);
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = 'stand_alone_send_secret';

        $sql = "CREATE TABLE IF NOT EXISTS `$wpdb->prefix$table_name` (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          secret text NOT NULL,
          url varchar(255) DEFAULT '' NOT NULL,
          created_at DATE NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
        dbDelta($sql);        
    }

    function add_to_menu() {
      global $admin_page_hooks;
      if ( empty ( $GLOBALS['admin_page_hooks']['kaboom'] ) ){
          add_menu_page(
              'Kaboom', 
              'Kaboom', 
              'manage_options', 
              'kaboom', 
              array( $this,  'kaboom_main' ), 
              plugins_url('/images/blue-dot.svg', __FILE__)
          );
      }
      add_submenu_page(
          'kaboom', 
          'Send Secrets', 
          'Send Secrets', 
          'manage_options', 
          'Send Secrets', 
          array( $this,  'settings' )
      );
      add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'kaboom_send_secrets_link' );

      function kaboom_send_secrets_link( $links ) {
         $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=Send+Secrets') ) .'">Settings</a>';
         return $links;
      }      
    }
    
    function stand_alone_send_secret(){
      ob_start();
      include(dirname(__FILE__) . "/view/stand_alone_form.php");  
      return ob_get_clean();
    }

    function settings(){
      wp_register_style('kaboom-styling', plugins_url('/view/style.css', __FILE__));      
      wp_enqueue_style('kaboom-styling');

      include(dirname(__FILE__) . "/view/settings.php");
    }

    function kaboom_main()
    {
        wp_register_style('kaboom-styling', plugins_url('/view/style.css', __FILE__));
        wp_enqueue_style('kaboom-styling');
        
        include(dirname(__FILE__) . "/view/kaboom.php");
    }
}

new SendSecretsByKaboom()
?>
