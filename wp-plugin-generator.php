<?php

// Main generator logic
require_once 'src/Generator.php';

// Function to prompt the user for input with validation
function promptInput($message, $defaultValue = '') {
    do {
        $input = readline($message . ($defaultValue ? " [$defaultValue]: " : ': '));
        $input = trim($input);
        if ($input === '') {
            return $defaultValue;
        }
    } while ($input === '');

    return $input;
}

// Get plugin details from user input
echo PHP_EOL;
echo PHP_EOL;
echo '------------------------------------------------' . PHP_EOL;
$pluginName = promptInput("Enter plugin name", 'WP Plugin Generator');
$authorName = promptInput("Enter author name", '');
$authorURI = promptInput("Enter author URL", '');
$version = promptInput("Enter version", '1.0.0');
$description = promptInput("Enter description", 'Generated plugin description.');
$textDomain = promptInput("Enter text domain", 'wp-plugin-generator');
$pluginURI = promptInput("Enter plugin URL", '');
$license = promptInput("Enter license", 'GPL');
$pluginNamespace = promptInput("Enter namespace", 'WPPluginGenerator');
echo '------------------------------------------------';
echo PHP_EOL;
echo PHP_EOL;

// Generate the main plugin class
EA\WPPluginGenerator\Generator::generatePluginFiles($pluginName, $authorName, $version, $description, $textDomain, $pluginURI, $authorURI, $license, $pluginNamespace);

echo "Plugin files generated successfully!\n";

// Run composer dump-autoload
echo "Running composer dump-autoload --optimize...\n";
exec('composer dump-autoload --optimize');

echo "Autoload configuration updated successfully!\n";
