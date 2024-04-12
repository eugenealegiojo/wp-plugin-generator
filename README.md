# wp-plugin-generator
A composer package that can generate basic WordPress plugin with PSR-4 autoloading.

## Usage

- Navigate to your project folder. For example: **wp-content/plugins/my-new-plugin**
- Add or generate a **composer.json** file.
- Run: **composer require eugenealegiojo/wp-plugin-generator**
- Add the following code to your **composer.json** file:
  ```
  "scripts": {
      "post-install-cmd": "php vendor/eugenealegiojo/wp-plugin-generator/wp-plugin-generator.php"
  },
  ```

- Run: **composer install**
- Follow the prompts in the terminal to add your plugin info, then activate your plugin in the WordPress admin.
