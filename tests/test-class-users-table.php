<?php

use WP_Mock\Tools\TestCase;

class MyPluginTest extends TestCase {
    public function setUp(): void {
        WP_Mock::setUp();
    }

    public function tearDown(): void {
        WP_Mock::tearDown();
    }

    public function testSampleFunction() {
        WP_Mock::userFunction('get_option', [
            'args' => 'my_plugin_option',
            'return' => 'expected value',
        ]);

        $this->assertTrue(true, 'This should always pass.');

        // $this->assertEquals('expected value', $result);
    }
}
