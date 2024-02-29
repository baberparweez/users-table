<?php
/**
 * Plugin Name: Users Table
 * Description: A WordPress plugin to display users in an HTML table fetched from an external API.
 * Version: 1.0
 * Author: Baber Parweez
 */

// Check if accessed directly
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Required files
 */
require_once __DIR__ . '/vendor/autoload.php';


/**
 * Registers a rewrite rule that maps custom endpoint to a query variable
 */
add_action('init', function() {
    add_rewrite_rule('^users-table/?$', 'index.php?users_table=1', 'top');
});

add_filter('query_vars', function($vars) {
    $vars[] = 'users_table';
    return $vars;
});


/**
 * Flush the rewrite rules upon plugin activation to ensure our new rule is recognized
 */
register_activation_hook(__FILE__, function() {
    add_rewrite_rule('^users-table/?$', 'index.php?users_table=1', 'top');
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});


/**
 * Hook into template_redirect to load our custom template when the endpoint is accessed.
 */
add_action('template_redirect', function() {
    $is_our_endpoint = intval(get_query_var('users_table', 0));
    if ($is_our_endpoint) {
        $users = fetch_users_from_api();
        display_users_table($users);
        exit; // Prevent the default WordPress behavior
    }
});


/**
 * Encapsulate our logic to fetch users in a function
 */
function fetch_users_from_api() {
    $response = wp_remote_get('https://jsonplaceholder.typicode.com/users');
    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data ?: [];
}


/**
 * Display the data
 */
function display_users_table($users) {
    echo '<link rel="stylesheet" href="' . esc_url(plugin_dir_url(__FILE__) . 'dist/style.css') . '" type="text/css" media="all" />';
    echo '<script src="' . esc_url(plugin_dir_url(__FILE__) . 'dist/bundle.js') . '"></script>';

    echo '<table>';
    echo '<thead><tr><th>ID</th><th>Name</th><th>Username</th></tr></thead>';
    echo '<tbody>';
    foreach ($users as $user) {
        echo sprintf(
            '<tr><td><a href="#" class="user-detail" data-user-id="%s">%s</a></td><td><a href="#" class="user-detail" data-user-id="%s">%s</a></td><td><a href="#" class="user-detail" data-user-id="%s">%s</a></td></tr>',
            esc_attr($user['id']), esc_html($user['id']),
            esc_attr($user['id']), esc_html($user['name']),
            esc_attr($user['id']), esc_html($user['username'])
        );
    }
    echo '</tbody></table>';
    echo '<div id="user-details"></div>'; // Container for displaying the fetched user details
}
