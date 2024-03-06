# Users Table WordPress Plugin

## Purpose

The "Users Table" WordPress plugin dynamically displays a table of users fetched from an external API (https://jsonplaceholder.typicode.com/users) on the WordPress frontend. This table showcases user details such as ID, name, and username. Users can click on these details to view more information about each user without reloading the page, thanks to AJAX calls.

## Features

-   **Custom Endpoint**: The plugin introduces a custom endpoint (/users-table/) to WordPress, displaying the users' table.
-   **AJAX-Enabled Detail View**: Clicking on a user detail fetches and displays additional information asynchronously.
-   **Caching**: Implements caching for API responses using WordPress Transients API, enhancing performance and reducing API call frequency.
-   **Extensible and Customisable**: Offers hooks for further customization and extension by developers.

## Technical Requirements

-   **PHP 8.0 or higher**: Utilizes modern PHP features for improved performance and security.
-   **WordPress 5.7 or higher**: Ensures compatibility with recent WordPress features and improvements.
-   **Composer**: For managing PHP dependencies.
-   **PHPUnit**: Version 8.5.37 for unit tests ensuring code quality and reliability.
-   **WP Mock**: For mocking WordPress functions in unit tests (Thanks to the 10up team!)
-   **Node.js (v19 or higher)**: Required for compiling JavaScript assets.
-   **NPM**: For managing Node.js packages

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

## What could have been done better

-   I opted to not use any JS library like React or Vue. This was to intially save time and for a task this size, including additional libraries or frameworks would be an overkill. For a plugin that would grow over time, I would opt to use latest techniques and adopt React with Vite for this particular plugin.

-   I included my assets for SCSS and JS within an index.js file. To improve, I would have split this further and included all SCSS in one parent file—with the same being done for all JS. In this plugin, I kept it simple as it worked and I applied minimal styles and JS.

-   I would have created more accurate unit tests that I understand better but I used WP Mock for the very first time so it was challenging and intriguing.

## Third-Party Code

-   https://github.com/n3r4zzurr0/svg-spinners

## Contributions

Contributions are welcome! Please feel free to submit pull requests or open issues to discuss proposed changes or enhancements.

## License

This project is open-sourced under the MIT License
