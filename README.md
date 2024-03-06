# Users Table WordPress Plugin

## Purpose

The "Users Table" plugin is designed to enhance WordPress websites by fetching user data from an external API (https://jsonplaceholder.typicode.com/users) and displaying this information in a dynamic HTML table within the WordPress frontend. It leverages WordPress's Transients API for efficient data caching and employs AJAX for seamless user detail retrieval, ensuring a smooth, engaging user experience without requiring page reloads.

## Dependencies

-   **PHP**: 7.4 or higher
-   **WordPress**: 5.7 or higher
-   **Composer**: For PHP package management
-   **PHPUnit**: Version 8.5.37 for running PHP tests
-   **WP Mock**: For mocking WordPress functions in unit tests (Thanks to the 10up team!)
-   **Node.js**: Version 19 or higher for JavaScript dependencies and running the compiler
-   **NPM**: For managing Node.js packages

## Required Packages and Libraries

-   `wp_remote_get` for fetching data from the external API
-   WordPress Transients API for caching responses
-   jQuery (comes with WordPress) for AJAX calls in the frontend

## Installation

### Setting Up the Environment

1. **Install Composer**: Make sure Composer is installed on your system. If not, follow the [official Composer installation guide](https://getcomposer.org/download/).

2. **Install PHPUnit and WP Mock**: After installing Composer, run the following commands to install PHPUnit and WP Mock globally on your system:

    ```bash
    composer global require phpunit/phpunit
    composer global require 10up/wp_mock:dev-master
    ```

3. **Install Node.js (v19 or higher)**: Download and install Node.js from the [official site](https://nodejs.org/). Ensure you're installing version 19 or above.

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
