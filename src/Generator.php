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
        // Read template file
        $template = file_get_contents(__DIR__ . '/../templates/PluginFiles.php.template');

        // Replace placeholders with provided values
        $template = str_replace(
            ['{plugin_name}', '{plugin_uri}', '{description}', '{plugin_version}', '{author_name}', '{author_uri}', '{plugin_license}', '{text_domain}'],
            [$pluginName, $pluginURI, $description, $version, $authorName, $authorURI, $license, $textDomain],
            $template
        );

        self::$_namespace = $pluginNamespace !== '' ? $pluginNamespace : self::toPascalCase( $pluginName );
        
        // Generate main plugin filename
        $main_plugin_file = self::toKebabCase( $pluginName );
        file_put_contents(__DIR__ . '/../' . $main_plugin_file . '.php', $template);

        // Composer.json
        self::generateComposerJSON($pluginName);

        // Main class file
        self::generateMainPluginClass($pluginName, $version, $textDomain);
    }

    private static function generateComposerJSON(){

        // Check if autoload config already exists in composer.json
        $composerJsonPath = __DIR__ . '/composer.json';
        $composerJson = json_decode(file_get_contents($composerJsonPath), true);

        if ( isset( $composerJson['autoload']['psr-4'] ) ) {
            $existingNamespace = array_pop($composerJson['autoload']['psr-4']);
            if( $existingNamespace ) {
                self::$_namespace = $existingNamespace;
            }

            if ( isset( $composerJson['autoload']['psr-4'][ self::$_namespace ] ) ) {
                self::$_srcPath = $composerJson['autoload']['psr-4'][ self::$_namespace ];
            }
            
            // $autoloadConfig = array_keys($composerJson['autoload']['psr-4']);

        } else {

            // Autoload config
            $autoloadConfig = [
                'psr-4' => [
                    self::$_namespace . '\\' => self::$_srcPath
                ]
            ];
            
            // Merge autoload configuration into existing composer.json
            $composerJson['autoload'] = $autoloadConfig;

            file_put_contents($composerJsonPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            echo "Autoload configuration updated successfully!\n";
        }
    }

    private static function generateMainPluginClass($pluginName, $version, $textDomain){

        // Read template file
        $template = file_get_contents(__DIR__ . '/../templates/MainPluginClass.php.template');

        $pluginTextDomain = $textDomain !== '' ? $textDomain : self::toKebabCase( $pluginName );
        $main_plugin_file = self::toKebabCase( $pluginName );
        $constPrefix = self::toUpperSnakeCase( $pluginName );

        $template = str_replace(
            ['{plugin_name}', '{plugin_version}', '{plugin_namespace}', '{const_prefix}', '{text_domain}', '{plugin_filename}'], 
            [$pluginName, $version, self::$_namespace, $constPrefix, $pluginTextDomain, $main_plugin_file], 
            $template 
        );

        file_put_contents(__DIR__ . '/../' . self::$_srcPath . '/Plugin.php', $template);
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