<?php

use Inpsyde\UsersTable\UsersTable;
use WP_Mock\Tools\TestCase;

class UsersTableTest extends TestCase {

    public function setUp(): void {
        parent::setUp();
        WP_Mock::setUp();

        if (!defined('HOUR_IN_SECONDS')) {
            define('HOUR_IN_SECONDS', 3600);
        }
    
        // Mock core WordPress functions
        WP_Mock::userFunction('register_activation_hook', [
            'times' => '0+', // Expected to be called one or more times across tests
        ]);
        WP_Mock::userFunction('register_deactivation_hook', [
            'times' => '0+', // Expected to be called one or more times across tests
        ]);
        WP_Mock::userFunction('add_action', [
            'times' => '0+', // Expected to be called one or more times across tests
        ]);
        WP_Mock::userFunction('add_filter', [
            'times' => '0+', // Expected to be called one or more times across tests
        ]);

        // Mock wp_remote_retrieve_response_code to return a specific HTTP status code
        WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'return' => 200, // You can adjust the return value based on your test scenario
        ]);

        // Mocking `is_wp_error` to return false by default
        WP_Mock::userFunction('is_wp_error', [
            'return' => false,
        ]);
    }

    public function tearDown(): void {
        WP_Mock::tearDown();
        parent::tearDown();
    }

    public function test_instance_returns_singleton() {
        $instance1 = UsersTable::instance();
        $instance2 = UsersTable::instance();
    
        $this->assertSame($instance1, $instance2, 'UsersTable::instance should return the same instance.');
    }    

    public function test_hooks_are_initialized() {
        WP_Mock::expectActionAdded('init', [UsersTable::class, 'registerRewriteRule'], 10, 1);
        WP_Mock::expectFilterAdded('query_vars', [UsersTable::class, 'addQueryVar']);
        WP_Mock::expectActionAdded('template_redirect', [UsersTable::class, 'handleTemplateRedirect']);
        WP_Mock::expectActionAdded('wp_enqueue_scripts', [UsersTable::class, 'enqueueScriptsAndStyles']);

        // Mocking functions called during plugin initialization
        WP_Mock::userFunction('add_rewrite_rule', [
            'times' => 1,
            'args' => ['^users-table/?$', 'index.php?users_table=1', 'top'],
        ]);

        WP_Mock::userFunction('flush_rewrite_rules', [
            'times' => 1 // Expect it to be called during activation
        ]);

        // Activation and deactivation hooks
        WP_Mock::onFilter('register_activation_hook')->with(__FILE__, [UsersTable::instance(), 'flushRewriteRulesOnActivation']);
        WP_Mock::onFilter('register_deactivation_hook')->with(__FILE__, 'flush_rewrite_rules');

        // Instantiate the class to trigger hook registration
        UsersTable::instance();

        $this->assertConditionsMet();
    }

    public function test_register_rewrite_rule() {
        WP_Mock::userFunction('add_rewrite_rule', [
            'times' => 1,
            'args' => ['^users-table/?$', 'index.php?users_table=1', 'top'],
        ]);

        UsersTable::instance()->registerRewriteRule();

        $this->assertConditionsMet();
    }

    public function test_fetch_users_from_api() {
        WP_Mock::userFunction('set_transient', [
            'times' => '0+', // '0+' allows for one or more calls across tests
        ]);   
        
        $testData = [['id' => 1, 'name' => 'Test User']];

        WP_Mock::userFunction('get_transient', [
            'args' => ['users_table_api_data'],
            'return' => false, // Simulate cache miss
        ]);

        WP_Mock::userFunction('wp_remote_get', [
            'return' => ['body' => json_encode($testData)],
        ]);

        WP_Mock::userFunction('wp_remote_retrieve_body', [
            'return' => json_encode($testData),
        ]);

        $result = UsersTable::instance()->fetchUsersFromApi();

        $this->assertIsArray($result);
        $this->assertEquals($testData, $result);

        // Test with cache hit
        WP_Mock::userFunction('get_transient', [
            'args' => ['users_table_api_data'],
            'return' => $testData, // Return cached data
        ]); 

        $cachedResult = UsersTable::instance()->fetchUsersFromApi();
        
        $this->assertIsArray($cachedResult);
        $this->assertEquals($testData, $cachedResult);           
    }

    public function test_fetch_users_from_api_with_wp_error() {
        WP_Mock::userFunction('wp_remote_get', [
            'return' => new WP_Error('http_request_failed', 'A valid URL was not provided.')
        ]);
    
        // Mock is_wp_error to recognize the WP_Error object
        WP_Mock::userFunction('is_wp_error', [
            'return' => true,
        ]);
    
        // Ensure wp_remote_retrieve_body returns a JSON string even in error scenarios for the purpose of this test
        WP_Mock::userFunction('wp_remote_retrieve_body', [
            'return' => json_encode([]), // Return an empty array as JSON, or a more realistic error response if applicable
        ]);
    
        $result = UsersTable::instance()->fetchUsersFromApi();
    
        // The method should still return an array, avoiding the TypeError
        $this->assertIsArray($result, 'Expected fetchUsersFromApi to return an array even on WP_Error.');
        $this->assertEmpty($result, 'Expected fetchUsersFromApi to return an empty array on WP_Error.');
    }    
}

if (!class_exists('WP_Error')) {
    class WP_Error {
        public $errors = [];
        public function __construct($code = '', $message = '', $data = '') {
            $this->errors[$code][] = ['message' => $message, 'data' => $data];
        }
        // Add methods used by your plugin, if any.
    }
}