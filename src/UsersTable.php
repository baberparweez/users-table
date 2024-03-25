<?php

declare(strict_types=1);

namespace BaberParweez\UsersTable;

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
    add_action('init', [$this, 'registerRewriteRule']);
    add_action('init', [$this, 'loadTextDomain']);
    add_filter('query_vars', [$this, 'addQueryVar']);
    add_action('template_redirect', [$this, 'handleTemplateRedirect']);
    add_action('wp_enqueue_scripts', [$this, 'enqueueScriptsAndStyles']);
    add_action('wp_ajax_fetch_user_details', [$this, 'handleAjaxFetchUserDetails']);
    add_action('wp_ajax_nopriv_fetch_user_details', [$this, 'handleAjaxFetchUserDetails']);

    register_activation_hook(__FILE__, [$this, 'flushRewriteRulesOnActivation']);
    register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
  }

  /**
   * Load text domain
   */
  public function loadTextDomain()
  {
    load_plugin_textdomain('users-table', false, basename(dirname(__FILE__)) . '/languages');
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
   * Handles the template redirect for the custom endpoint
   */
  public function handleTemplateRedirect(): void
  {
    if ($this->isOurEndpoint()) {
      $users = $this->fetchUsersFromApi();
      $this->displayUsersTable($users);
      die();
    }
  }

  /**
   * Check if custom endpoint
   */
  private function isOurEndpoint(): bool
  {
    return intval(get_query_var('users_table', 0)) === 1;
  }

  /**
   * Enqueue scripts and styles
   */
  public function enqueueScriptsAndStyles(): void
  {
    if ($this->isOurEndpoint()) {
      wp_enqueue_style('users-table-style', USERS_TABLE_PLUGIN_URL . 'dist/style.css', array(), filemtime(USERS_TABLE_PLUGIN_DIR . 'dist/style.css'), 'all');
      wp_enqueue_script('users-table-script', USERS_TABLE_PLUGIN_URL . 'dist/bundle.js', array('jquery'), filemtime(USERS_TABLE_PLUGIN_DIR . 'dist/bundle.js'), true);

      wp_localize_script('users-table-script', 'myUsersTable', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('fetch_user_details')
      ));
    }
  }

  /**
   * Handle the AJAX request to fetch user details
   */
  public function handleAjaxFetchUserDetails(): void
  {
    check_ajax_referer('fetch_user_details', 'nonce');

    if (isset($_GET['user_id'])) {
      $user_id = intval($_GET['user_id']);
      $users = $this->fetchUsersFromApi();

      $user_details = array_filter($users, function ($user) use ($user_id) {
        return $user['id'] === $user_id;
      });

      if (!empty($user_details)) {
        wp_send_json_success(reset($user_details));
      } else {
        wp_send_json_error('User not found');
      }
    } else {
      wp_send_json_error('Invalid request');
    }
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
   * Specifically for testing hook initialisation
   */
  public function testInit()
  {
    $this->initHooks(); // Directly call the method that adds hooks
  }

  /**
   * Fetches users from the API
   *
   * @return array
   */
  public function fetchUsersFromApi(): array
  {

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
   * Displays the users table
   *
   * @param array $users
   */
  private function displayUsersTable(array $users): void
  {
    // Render the header block template
    if (has_blocks('header')) {
      $header_template = get_block_template('header', 'header');
      $header_content = $header_template->render();
      echo $header_content;
    } else {
      echo get_header();
    }
    echo '<table class="users__table">';
    echo '<thead><tr><th>' . esc_html__('ID', 'users-table') . '</th><th>' . esc_html__('Name', 'users-table') . '</th><th>' . esc_html__('Username', 'users-table') . '</th></tr></thead>';
    echo '<tbody>';
    foreach ($users as $user) {
      echo sprintf(
        '<tr><td><a href="#" class="users__table--user" data-user-id="%1$s">%2$s</a></td><td><a href="#" class="users__table--user" data-user-id="%1$s">%3$s</a></td><td><a href="#" class="users__table--user" data-user-id="%1$s">%4$s</a></td></tr>',
        esc_attr($user['id']),
        esc_html($user['id']),
        esc_html($user['name']),
        esc_html($user['username'])
      );
    }
    echo '</tbody></table>';
    echo '<div id="user-details" aria-live="polite" class="users__table--details"></div>'; // Container for displaying the fetched user details

    if (has_blocks('footer')) {
      $footer_template = get_block_template('footer', 'footer');
      $footer_content = $footer_template->render();
      echo $footer_content;
    } else {
      echo get_footer();
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
  } // End instance
}
