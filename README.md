# Users Table WordPress Plugin

## Purpose

The "Users Table" plugin is designed to enhance WordPress websites by fetching user data from an external API (https://jsonplaceholder.typicode.com/users) and displaying this information in a dynamic HTML table within the WordPress frontend. This plugin is ideal for administrators looking to showcase user data in an interactive, easily accessible format. It leverages WordPress's Transients API for efficient data caching and employs AJAX for seamless user detail retrieval, ensuring a smooth, engaging user experience without requiring page reloads.

## Dependencies

-   PHP 7.4 or higher
-   WordPress 5.7 or higher
-   Composer for PHP package management
-   PHPUnit for running PHP tests
-   Node.js and NPM for JavaScript dependencies and running the compiler

## Required Packages and Libraries

-   `wp_remote_get` for fetching data from the external API
-   WordPress Transients API for caching responses
-   jQuery (comes with WordPress) for AJAX calls in the frontend

## Installation

### Setting Up the Environment

1. **Install Composer**: Ensure you have Composer installed on your system. If not, follow the [official Composer installation guide](https://getcomposer.org/download/).

2. **Install PHPUnit**: After installing Composer, run `composer global require phpunit/phpunit` to install PHPUnit globally on your system.

3. **Install Node.js and NPM**: Download and install Node.js from the [official site](https://nodejs.org/). NPM comes bundled with Node.js.

### Plugin Installation

1. **Download the Plugin**: Clone this repository into your WordPress plugins directory:

    ```bash
    cd wp-content/plugins
    git clone https://github.com/baberparweez/users-table
    ```

2. **Install PHP Dependencies**: Navigate to the plugin directory and run Composer to install PHP dependencies:

    ```bash
    cd users-table
    composer install
    ```

3. **Install JavaScript Dependencies**: Install the required Node.js packages:

    ```bash
    npm install
    ```

4. **Activate the Plugin**: Log into your WordPress admin dashboard, go to Plugins, and activate "Users Table."

## Running the Compiler

To compile JavaScript and SCSS files (assuming you have a build script defined in your `package.json`):

```bash
npm run build
```

This command should compile your SCSS and JavaScript files, typically outputting to a `dist` directory within your plugin folder.

## Running PHP Tests

To run the PHPUnit tests, navigate to your plugin directory and execute:

```bash
./vendor/bin/phpunit
```

Make sure you have a `phpunit.xml` configuration file in your plugin directory that defines the test suites.

## Contributions

Contributions are welcome! Please feel free to submit pull requests or open issues to discuss proposed changes or enhancements.
