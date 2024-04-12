<?php
namespace EA\WPPluginGenerator;

class Generator {
    /**
     * Plugin namespace.
     * 
     * @var string
     */
    private static $_namespace = 'MyGeneratedPlugin';

    /**
     * The path where the main class file will be generated.
     *
     * @var string
     */
    private static $_srcPath = 'src/';
    
    public static function generatePluginFiles($pluginName, $authorName, $version, $description, $textDomain, $pluginURI = '', $authorURI = '', $license = 'GPL', $pluginNamespace = '') {
        
        // Setup namespace in composer.json
        self::generateComposerJSON($pluginName, $pluginNamespace);

        // Read template file
        $template = file_get_contents( dirname(__DIR__) . '/templates/MainPlugin.php.template');

        $sep = DIRECTORY_SEPARATOR;
        $rootFolderPath = realpath(__DIR__ . $sep . '..' . $sep . '..' . $sep . '..' . $sep .'..');
        $main_plugin_file = self::toKebabCase( basename($rootFolderPath) );
        $mainFilePath = $rootFolderPath . $sep . $main_plugin_file . '.php';

        // Replace placeholders with provided values
        $template = str_replace(
            ['{plugin_name}', '{plugin_uri}', '{description}', '{plugin_version}', '{author_name}', '{author_uri}', '{plugin_license}', '{text_domain}', '{plugin_namespace}'],
            [$pluginName, $pluginURI, $description, $version, $authorName, $authorURI, $license, $textDomain, self::$_namespace],
            $template
        );
        
        // Ensure the directory exists, create it if it doesn't
        if (!file_exists(dirname($mainFilePath)) && !mkdir(dirname($mainFilePath), 0777, true)) {
            die('Failed to create directory: ' . dirname($mainFilePath) . PHP_EOL);
        }
        
        // Generate main plugin filename
        file_put_contents( $mainFilePath, $template );

        // Main class file
        self::generateMainPluginClass($pluginName, $version, $textDomain);
    }

    private static function generateMainPluginClass($pluginName, $version, $textDomain){

        // Read template file
        $template = file_get_contents(dirname(__DIR__) . '/templates/MainPluginClass.php.template');

        $pluginTextDomain = $textDomain !== '' ? $textDomain : self::toKebabCase( $pluginName );
        $constPrefix = self::toUpperSnakeCase( $pluginName );

        $sep = DIRECTORY_SEPARATOR;
        $rootFolderPath = realpath(__DIR__ . $sep . '..' . $sep . '..' . $sep . '..' . $sep .'..');
        $main_plugin_file = self::toKebabCase( basename($rootFolderPath) );
        $mainClassFilePath = $rootFolderPath . $sep . self::$_srcPath . 'Plugin.php';

        $template = str_replace(
            ['{plugin_name}', '{plugin_version}', '{plugin_namespace}', '{const_prefix}', '{text_domain}', '{plugin_filename}'], 
            [$pluginName, $version, self::$_namespace, $constPrefix, $pluginTextDomain, $main_plugin_file], 
            $template 
        );

        // Ensure the directory exists, create it if it doesn't
        if (!file_exists(dirname($mainClassFilePath)) && !mkdir(dirname($mainClassFilePath), 0777, true)) {
            die('Failed to create directory: ' . dirname($mainClassFilePath) . PHP_EOL);
        }

        file_put_contents( $mainClassFilePath, $template );
    }

    private static function generateComposerJSON( $pluginName, $pluginNamespace ){

        // Initialize the namespace
        self::$_namespace = $pluginNamespace !== '' ? $pluginNamespace : self::toPascalCase( $pluginName );

        $sep = DIRECTORY_SEPARATOR;
        $rootFolderPath = realpath(__DIR__ . $sep . '..' . $sep . '..' . $sep . '..' . $sep .'..');
        $composerJsonPath = $rootFolderPath . $sep . 'composer.json';
        $composerJson = json_decode(file_get_contents($composerJsonPath), true);

        if ( isset( $composerJson['autoload']['psr-4'] ) ) {
            $namespace = array_keys($composerJson['autoload']['psr-4']);
            self::$_namespace = rtrim($namespace[0], "\\");
           
            if ( isset( $composerJson['autoload']['psr-4'][ self::$_namespace ] ) ) {
                self::$_srcPath = $composerJson['autoload']['psr-4'][ self::$_namespace ];
            }
            
            echo "Namespace \"{$pluginNamespace}\" has been ignored. Namespace already defined in composer.json." . PHP_EOL;

        } else {

            echo "Adding autload config in composer.json...\n";

            // Autoload config
            $autoloadConfig = [
                'psr-4' => [
                    rtrim(self::$_namespace, '\\') . '\\' => self::$_srcPath
                ]
            ];
            
            // Merge autoload configuration into existing composer.json
            $composerJson['autoload'] = $autoloadConfig;

            file_put_contents($composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            echo "Autoload configuration updated successfully!\n";
        }
    }

    // Function to transform the plugin name into kebab-case
    public static function toKebabCase($string) {
        return strtolower(preg_replace('/[^A-Za-z0-9\- ]/', '', str_replace(' ', '-', $string)));
    }

    // Function to transform the plugin name into PascalCase
    public static function toPascalCase($string) {
        return preg_replace_callback('/[^A-Za-z0-9\- ]/', function($matches) {
            return '';
        }, str_replace(' ', '', ucwords(str_replace('-', ' ', $string))));
    }

    // Function to transform the plugin name into UPPER_SNAKE_CASE
    public static function toUpperSnakeCase($string) {
        return strtoupper(preg_replace('/[^A-Za-z0-9\- ]/', '', str_replace(' ', '_', $string)));
    }
}