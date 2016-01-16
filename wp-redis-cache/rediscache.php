<?php
/*
Plugin Name: Wild Wing Cache Helper
Description: A helper plugin to clear cache objects from Wild Wing's Redis cluster.
Plugin URI: https://github.com/BenjaminAdams/wp-redis-cache
Version: 1.0
Author: Wild Wing Studios
Author URI: https://www.wildwingstudios.com

    Copyright 2013  Benjamin Adams  (email : ben@dudelol.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/


//Custom Theme Settings
add_action('admin_menu', 'add_redis_interface');
add_action('admin_bar_menu', 'wwRedisCache', 100);

function wwRedisCache($wp_admin_bar){
    $args = array(
        'id' => 'WPRedisClearCache',
        'title' => 'Clear WW Cache',
        'href' => 'http://#',
        'meta' => array(
            'class' => 'clear-ww-cache'
    ));
    $wp_admin_bar->add_node($args);

    clear_wp_redis_cache_javascript();
}


function add_redis_interface() {
    add_options_page('Wild Wing Redis Cache', 'Wild Wing Redis Cache', 'manage_options', 'functions', 'edit_redis_options');
}

function edit_redis_options() {
    ?>
    <div class='wrap'>
    <h2>Wild Wing Redis Cache</h2>
    <form method="post" action="options.php">
    <?php wp_nonce_field('update-options') ?>
	
	<p>This plugin does not work out of the box and requires additional steps.<br />
	<p>If you do not have Redis installed on your machine this will NOT work! </p>

    <p><strong>Seconds of Caching:</strong><br />
	How many seconds would you like to cache?  *Recommended 12 hours or 43200 seconds <br />
    <input type="text" name="wp-redis-cache-seconds" size="45" value="<?php echo get_option('wp-redis-cache-seconds'); ?>" /></p>
 
    <p><strong>Cache unlimited:</strong><br />
		If this options set the cache never expire. This option overiedes the setting "Seconds of Caching"<br />
    <input type="checkbox" name="wp-redis-cache-unlimited" size="45" value="true" <?php checked('true', get_option('wp-redis-cache-unlimited')); ?>/></p>
	  
    <p><input class="button button-secondary button-hero load-customize hide-if-no-customize" type="submit" name="Submit" value="Update Options" /></p>
	<p><input type="button" class="clear-ww-cache button button-primary button-hero load-customize hide-if-no-customize" name="WPRedisClearCache" value="Clear Cache"></p>
    <input class="button" type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="wp-redis-cache-seconds,wp-redis-cache-unlimited" />

    </form>
    </div>
    <?php
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_redis_cache() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/rediscache-activator.php';
    Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_redis_cache() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/rediscache-deactivator.php';
    Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_redis_cache' );
register_deactivation_hook( __FILE__, 'deactivate_redis_cache' );

include('cache.php');
