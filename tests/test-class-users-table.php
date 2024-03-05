<?php

use Inpsyde\UsersTable\UsersTable;
use WP_Mock\Tools\TestCase;

class UsersTableTest extends TestCase {

    public function setUp(): void {
        WP_Mock::setUp();
    }

    public function tearDown(): void {
        WP_Mock::tearDown();
    }

    public function test_instance_returns_singleton() {
        $firstInstance = UsersTable::instance();
        $secondInstance = UsersTable::instance();

        $this->assertSame($firstInstance, $secondInstance);
    }

    public function test_hooks_are_initialized() {
        WP_Mock::expectActionAdded('init', [UsersTable::instance(), 'registerRewriteRule']);
        WP_Mock::expectFilterAdded('query_vars', [UsersTable::instance(), 'addQueryVar']);
        // Add expectations for other hooks
    
        // Trigger the hooks initialization
        UsersTable::instance();
        
        // Verify all hooks were correctly added
        $this->assertHooksAdded();
    }
    

    public function test_register_rewrite_rule() {
        WP_Mock::expectActionAdded('init', [UsersTable::instance(), 'registerRewriteRule']);
        WP_Mock::userFunction('add_rewrite_rule', [
            'times' => 1,
            'args' => ['^users-table/?$', 'index.php?users_table=1', 'top'],
        ]);
    
        UsersTable::instance()->registerRewriteRule();
    }
    
    public function test_add_query_var() {
        $vars = ['existing_var'];
        $expected = array_merge($vars, ['users_table']);
    
        $result = UsersTable::instance()->addQueryVar($vars);
    
        $this->assertEquals($expected, $result);
    }

    public function test_handle_template_redirect() {
        WP_Mock::userFunction('get_query_var', [
            'args' => ['users_table', 0],
            'return' => 1,
        ]);
    
        WP_Mock::userFunction('wp_remote_get', [
            'return' => ['body' => json_encode([['id' => 1, 'name' => 'Test User']])],
        ]);
    
        // Expect the `displayUsersTable` method to be called. This may require using partial mocks or testing the output buffering.
        
        // Since `handleTemplateRedirect` exits, you'll need to find a way around this for testing. Mocking or output buffering could be approaches.
    
        // UsersTable::instance()->handleTemplateRedirect();
        // This test might need a more sophisticated setup to mock the exit and output.
    }

    public function test_display_users_table_outputs_correct_html() {
        $users = [
            ['id' => 1, 'name' => 'Test User', 'username' => 'testuser'],
            // Add more users as needed
        ];
    
        ob_start();
        $method = new ReflectionMethod(UsersTable::class, 'displayUsersTable');
        $method->setAccessible(true);
        $method->invoke(UsersTable::instance(), $users);
        $output = ob_get_clean();
    
        $this->assertStringContainsString('<table class="users__table">', $output);
        // Additional assertions to verify the structure and content of the output
    }

    public function test_fetch_users_from_api_uses_cache() {
        $method = new ReflectionMethod(UsersTable::class, 'fetchUsersFromApi');
        $method->setAccessible(true);
    
        WP_Mock::userFunction('get_transient', [
            'args' => ['users_table_api_data'],
            'return' => [['id' => 1, 'name' => 'Cached User']],
        ]);
    
        $usersTableInstance = UsersTable::instance();
        $users = $method->invoke($usersTableInstance); // Call the now accessible method
    
        $this->assertCount(1, $users);
        $this->assertEquals('Cached User', $users[0]['name']);
    }
    
}
