<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable;

final class UsersTable
{
    /**
     * Holds the singleton instance of this class
     * @var UsersTable|null
     */
    private static ?UsersTable $instance = null;

    /**
     * Private constructor to prevent direct creation
     */
    private function __construct()
    {
        $this->initHooks();
    }

    /**
     * Initialize WordPress hooks
     */
    private function initHooks(): void
    {
        // add_action('init', [$this, 'clearApiCache']);
        add_action('init', [$this, 'registerRewriteRule']);
        add_filter('query_vars', [$this, 'addQueryVar']);
        add_action('template_redirect', [$this, 'handleTemplateRedirect']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScriptsAndStyles']);

        register_activation_hook(__FILE__, [$this, 'flushRewriteRulesOnActivation']);
        register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
    }

    /**
     * Registers a rewrite rule that maps custom endpoint to a query variable
     */
    public function registerRewriteRule(): void
    {
        add_rewrite_rule('^users-table/?$', 'index.php?users_table=1', 'top');
    }

    /**
     * Adds custom query var
     *
     * @param array $vars
     * @return array
     */
    public function addQueryVar(array $vars): array
    {
        $vars[] = 'users_table';
        return $vars;
    }

    /**
     * Flushes rewrite rules on plugin activation
     */
    public function flushRewriteRulesOnActivation(): void
    {
        $this->registerRewriteRule();
        flush_rewrite_rules();
    }

    /**
     * Handles the template redirect for the custom endpoint
     */
    public function handleTemplateRedirect(): void
    {
        $isOurEndpoint = intval(get_query_var('users_table', 0));
        if ($isOurEndpoint) {
            $users = $this->fetchUsersFromApi();
            $this->displayUsersTable($users);
            exit;
        }
    }

    /**
     * Fetches users from the API
     *
     * @return array
     */
    public function fetchUsersFromApi(): array {

        $transient_key = 'users_table_api_data';
        $cached_data = get_transient($transient_key);
        
        if ($cached_data !== false) {
            return $cached_data;
        }
        
        $response = wp_remote_get('https://jsonplaceholder.typicode.com/users');
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            return []; // Make sure to return an empty array on error
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if (!is_array($data) || is_null($data)) {
            return []; // Ensure we return an empty array if $data is not valid
        }
    
        set_transient($transient_key, $data, HOUR_IN_SECONDS);
        
        return $data;
    }     
    
    /**
     * Clears cached API data.
     */
    public function clearApiCache(): void
    {
        delete_transient('users_table_api_data');
    }

    /**
     * Displays the users table
     *
     * @param array $users
     */
    private function displayUsersTable(array $users): void
    {
        echo '<link rel="stylesheet" href="' . esc_url(USERS_TABLE_PLUGIN_URL . 'dist/style.css') . '" type="text/css" media="all" />';
        echo '<script src="' . esc_url(USERS_TABLE_PLUGIN_URL . 'dist/bundle.js') . '"></script>';

        echo '<table class="users__table">';
        echo '<thead><tr><th>ID</th><th>Name</th><th>Username</th></tr></thead>';
        echo '<tbody>';
        foreach ($users as $user) {
            echo sprintf(
                '<tr><td><a href="#" class="users__table--user" data-user-id="%s">%s</a></td><td><a href="#" class="users__table--user" data-user-id="%s">%s</a></td><td><a href="#" class="users__table--user" data-user-id="%s">%s</a></td></tr>',
                esc_attr($user['id']), esc_html($user['id']),
                esc_attr($user['id']), esc_html($user['name']),
                esc_attr($user['id']), esc_html($user['username'])
            );
        }
        echo '</tbody></table>';
        echo '<div id="user-details" class="users__table--details"></div>'; // Container for displaying the fetched user details
    }

    /**
     * Enqueues scripts and styles
     */
    public function enqueueScriptsAndStyles(): void
    {
        $isOurEndpoint = intval(get_query_var('users_table', 0));
        if ($isOurEndpoint) {
            $version = time(); // Use filemtime() in production for cache busting based on file modification time.
            wp_enqueue_style('users-table-style', USERS_TABLE_PLUGIN_URL . 'dist/style.css');
            wp_enqueue_script('users-table-script', USERS_TABLE_PLUGIN_URL . 'dist/bundle.js', array(), null, true);
        }
    }

    /**
     * Returns the singleton instance of this class
     *
     * @return UsersTable
     */
    public static function instance(): UsersTable
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
