<?php

declare(strict_types=1);

use Inpsyde\UsersTable\UsersTable;
use WP_Mock\Tools\TestCase;

class UsersTableTest extends TestCase
{

    // Prepares the testing environment before each test method runs.
    // This is where we mock WordPress functions and set up the environment.
    public function setUp(): void
    {
        parent::setUp();
        WP_Mock::setUp();

        // Defines HOUR_IN_SECONDS if not already defined, for use in transients.
        if (!defined('HOUR_IN_SECONDS')) {
            define('HOUR_IN_SECONDS', 3600);
        }
    
        // Mock WordPress lifecycle hooks and functions to isolate our testing.
        WP_Mock::userFunction('register_activation_hook', [
            'times' => '0+'
        ]);

        WP_Mock::userFunction('register_deactivation_hook', [
            'times' => '0+'
        ]);

        WP_Mock::userFunction('add_action', [
            'times' => '0+'
        ]);

        WP_Mock::userFunction('add_filter', [
            'times' => '0+'
        ]);

        // Mocks for ensuring HTTP response handling behaves as expected.
        WP_Mock::userFunction('wp_remote_retrieve_response_code', [
            'return' => 200
        ]);

        WP_Mock::userFunction('is_wp_error', [
            'return' => false
        ]);

        // Simulate the transient retrieval behavior, defaulting to no cached data.
        WP_Mock::userFunction('get_transient', [
            'args' => ['users_table_api_data'], 
            'return' => false
        ]);
    }

    // Resets the WP Mock environment after each test method.
    public function tearDown(): void
    {
        WP_Mock::tearDown();
        parent::tearDown();
    }

    // Tests that the UsersTable class follows the singleton pattern,
    // ensuring only one instance of the class can exist.
    public function test_instance_returns_singleton()
    {
        $instance1 = UsersTable::instance();
        $instance2 = UsersTable::instance();
    
        $this->assertSame($instance1, $instance2, 'UsersTable::instance should return the same instance.');
    }    

    // Verifies that all necessary WordPress hooks are properly initialized by the plugin,
    // ensuring correct plugin behavior and integration with WordPress.
    public function testInitHooks()
    {
        $instance = UsersTable::instance();
        $instance->testInit(); // Directly initialize hooks for testing
    
        // Now assert that hooks were added
        $this->assertHooksAdded();
    }
    
    // Tests that the custom rewrite rule is correctly registered by the plugin,
    // which is crucial for the plugin's custom endpoint functionality.
    public function test_register_rewrite_rule()
    {
        WP_Mock::userFunction('add_rewrite_rule', [
            'times' => 1, 
            'args' => ['^users-table/?$', 'index.php?users_table=1', 'top']
        ]);

        UsersTable::instance()->registerRewriteRule(); // Invoke the method to test.

        $this->assertConditionsMet(); // Assert that the rewrite rule was added as expected.
    }

    // Tests the functionality for fetching users from the API, covering both scenarios:
    // no data in cache (cache miss) and data present in cache (cache hit).
    public function test_fetch_users_from_api()
    {
        // Allows the set_transient function to be called any number of times.
        WP_Mock::userFunction('set_transient', [
            'times' => '0+'
        ]);   
        
        // The expected data to be returned by the API call.
        $testData = [
            ['id' => 1, 'name' => 'Test User']
        ];

        // Mocks to simulate fetching data from the API and handling transient data.
        WP_Mock::userFunction('get_transient', [
            'args' => ['users_table_api_data'], 
            'return' => false]
        );

        WP_Mock::userFunction('wp_remote_get', [
            'return' => ['body' => json_encode($testData)]
        ]);

        WP_Mock::userFunction('wp_remote_retrieve_body', [
            'return' => json_encode($testData)
        ]);

        $result = UsersTable::instance()->fetchUsersFromApi(); // Test the fetch functionality.

        $this->assertIsArray($result); // Ensure the result is an array.
        $this->assertEquals($testData, $result); // Ensure the result matches expected data.

        // Simulating a cache hit scenario.
        WP_Mock::userFunction('get_transient', [
            'args' => ['users_table_api_data'], 
            'return' => $testData
        ]); 

        $cachedResult = UsersTable::instance()->fetchUsersFromApi(); // Fetching data again.
        
        $this->assertIsArray($cachedResult); // Ensure cached result is still an array.
        $this->assertEquals($testData, $cachedResult); // Ensure cached data matches expected.           
    }

    // Tests the plugin's behavior when a WP_Error occurs during the API request,
    // ensuring the plugin gracefully handles API errors.
    public function test_fetch_users_from_api_with_wp_error()
    {
        // Simulates a WP_Error being returned by the wp_remote_get call.
        WP_Mock::userFunction('get_transient', [
            'args' => ['users_table_api_data'],
            'return' => false,
        ]);
        WP_Mock::userFunction('wp_remote_get', [
            'return' => new WP_Error('http_request_failed', 'A valid URL was not provided.'),
        ]);
    
        // Ensures is_wp_error function recognizes the simulated WP_Error object.
        WP_Mock::userFunction('is_wp_error', [
            'return' => true,
        ]);

        // Ensure wp_remote_retrieve_body returns a JSON string even in error scenarios for the purpose of this test
        WP_Mock::userFunction('wp_remote_retrieve_body', [
            'return' => json_encode([]), // Return an empty array as JSON, or a more realistic error response if applicable
        ]);
    
        // Test method execution, even in error scenarios.
        $result = UsersTable::instance()->fetchUsersFromApi();
    
        $this->assertIsArray($result); // Ensures method returns an array on WP_Error.
        $this->assertEmpty($result); // Ensures returned array is empty on WP_Error.
    }    
}

if (!class_exists('WP_Error'))
{
    // Defines a mock WP_Error class if it doesn't already exist, for testing purposes.
    class WP_Error
    {
        public $errors = [];
        public function __construct($code = '', $message = '', $data = '') {
            $this->errors[$code][] = ['message' => $message, 'data' => $data];
        }
    }
}