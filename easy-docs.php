<?php
/*
Plugin Name: Easy Docs
Description: Easily Create Documentation WordPress Website
Version: 1.0.0
Author: Mantrabrain
Author URI: https://mantrabrain.com/
License: MIT License
License URI: http://opensource.org/licenses/MIT
Text Domain: easy-docs
Domain Path: /languages
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

if (!class_exists('Easy_Docs')) :

    /**
     * Main Easy_Docs Class.
     *
     * @since 1.0.0
     */
    final class Easy_Docs
    {
        /** Singleton *************************************************************/

        /**
         * @var Easy_Docs The one true Easy_Docs
         * @since 1.0.0
         */
        private static $instance;


        /**
         * Main Easy_Docs Instance.
         *
         * Insures that only one instance of Easy_Docs exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 1.0.0
         * @static
         * @staticvar array $instance
         * @uses Easy_Docs::setup_constants() Setup the constants needed.
         * @uses Easy_Docs::includes() Include the required files.
         * @uses Easy_Docs::load_textdomain() load the language files.
         * @see  Easy_Docs()
         * @return object|Easy_Docs The one true Easy_Docs
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof Easy_Docs)) {
                self::$instance = new Easy_Docs;

                self::$instance->setup_constants();
                self::$instance->includes();
            }

            return self::$instance;
        }

        /**
         * Throw error on object clone.
         *
         * The whole idea of the singleton design pattern is that there is a single
         * object therefore, we don't want the object to be cloned.
         *
         * @since 1.0.0
         * @access protected
         * @return void
         */
        public function __clone()
        {
            // Cloning instances of the class is forbidden.
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'easy-docs'), '1.0.0');
        }

        /**
         * Disable unserializing of the class.
         *
         * @since 1.0.0
         * @access protected
         * @return void
         */
        public function __wakeup()
        {
            // Unserializing instances of the class is forbidden.
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'easy-docs'), '1.6');
        }

        /**
         * Setup plugin constants.
         *
         * @access private
         * @since 1.0.0
         * @return void
         */
        private function setup_constants()
        {

            $file = (dirname(__FILE__));

            $this->define('EASY_DOCS_BASE_FILE', (__FILE__));
            $this->define('EASY_DOCS_VERSION', '1.0.0');
            $this->define('EASY_DOCS_DIR_NAME', dirname(EASY_DOCS_BASE_FILE));
            $this->define('EASY_DOCS_BASE_DIR', plugin_dir_path(EASY_DOCS_BASE_FILE));
            $this->define('EASY_DOCS_BASE_URL', plugins_url('/', EASY_DOCS_BASE_FILE));
            $this->define('EASY_DOCS_POST_TYPE', 'docs');
        }


        public function define($key, $value)
        {
            if (!defined($key)) {
                define($key, $value);
            }
        }

        /**
         * Include required files.
         *
         * @access private
         * @since 1.0.0
         * @return void
         */
        private function includes()
        {

            require_once EASY_DOCS_DIR_NAME . '/includes/class-easy-docs-autoloader.php';

        }


    }

endif; // End if class_exists check.


/**
 * The main function for that returns Easy_Docs
 *
 * The main function responsible for returning the one true Easy_Docs
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 *
 * @since 1.0.0
 * @return object|Easy_Docs The one true Easy_Docs Instance.
 */
function MB_Easy_Docs()
{
    return Easy_Docs::instance();
}

// Get Easy_Docs Running.
MB_Easy_Docs();
