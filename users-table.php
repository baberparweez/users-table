<?php

declare(strict_types=1);

/**
 * Plugin Name: Users Table
 * Description: A WordPress plugin to display users in an HTML table fetched from an external API.
 * Version: 1.0
 * Author: Baber Parweez
 */

namespace BaberParweez\UsersTable;

if (!class_exists(UsersTable::class) && is_readable(__DIR__ . '/vendor/autoload.php')) {
  /** @noinspection PhpIncludeInspection */
  define('USERS_TABLE_PLUGIN_DIR', plugin_dir_path(__FILE__));
  define('USERS_TABLE_PLUGIN_URL', plugin_dir_url(__FILE__));
  require_once __DIR__ . '/vendor/autoload.php';
}

class_exists(UsersTable::class) && UsersTable::instance();
