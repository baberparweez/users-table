<?php

/**
 * Plugin Name: Users Table
 * Description: A WordPress plugin to display users in an HTML table fetched from an external API.
 * Version: 1.0
 * Author: Baber Parweez
 */

declare(strict_types=1);

namespace Inpsyde\UsersTable;

// Ensure that the autoloader is present and readable.
if (!class_exists(UsersTable::class) && is_readable(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Initialize the plugin.
class_exists(UsersTable::class) && UsersTable::instance();
