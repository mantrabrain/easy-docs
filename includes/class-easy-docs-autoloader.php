<?php
/**
 * Responsible for setting up constants, classes and includes.
 *
 * @author Mantrabrain
 * @package Documentation/Loader
 */

defined( 'ABSPATH' ) || die('Exit');

if ( ! class_exists( 'Easy_Docs_Autoloader' ) ) {

	/**
	 * Responsible for setting up constants, classes and includes.
	 *
	 * @since 1.0.0
	 */
	final class Easy_Docs_Autoloader {

		/**
		 * The unique instance of the plugin.
		 *
		 * @var Instance variable
		 */
		private static $instance;

		/**
		 * Gets an instance of our plugin.
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {

			$this->define_constants();
			$this->load_files();
			$this->init_hooks();
			add_action( 'init', array( $this, 'easy_docs_callback' ) );

			do_action( 'easy_docs_loaded' );
		}
		/**
		 * Callback function for overide templates.
		 *
		 * @category InitCallBack
		 */
		function easy_docs_callback() {

			$is_single_template_on = get_option( 'easy_docs_override_single_template' );
			$is_cat_template_on    = get_option( 'easy_docs_override_category_template' );

			if ( '1' == $is_single_template_on || false === $is_single_template_on ) {
				add_filter( 'single_template', array( $this, 'get_easy_docs_single_template' ), 99 );
				add_filter( 'body_class', array( $this, 'easy_docs_body_single_class' ) );
			}

			if ( '1' == $is_cat_template_on || false === $is_cat_template_on ) {
				add_filter( 'template_include', array( $this, 'category_template' ), 99 );
				add_filter( 'template_include', array( $this, 'tag_template' ), 99 );
				add_filter( 'body_class', array( $this, 'easy_docs_body_tax_class' ) );
				add_filter( 'body_class', array( $this, 'easy_docs_body_sidebar_class' ) );
			}

		}

		/**
		 * Initialization hooks
		 *
		 * @category Hooks
		 */
		function init_hooks() {
			register_activation_hook( EASY_DOCS_BASE_FILE, array( $this, 'activation' ) );
			add_action( 'admin_menu', array( $this, 'register_options_menu' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_scripts' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 10 );
			// Use this filter to overwrite archive page for easy docs post type.
			add_filter( 'archive_template', array( $this, 'get_easy_docs_archive_template' ) );
			// Call register settings function.
			add_action( 'admin_init', array( $this, 'register_easy_docs_plugin_settings' ) );
		}

		/**
		 * Taxonomy Callback Function.
		 *
		 * @param array $template Overide taxonomy template.
		 */
		function category_template( $template ) {
			if ( is_tax( 'docs_category' ) ) {
				return EASY_DOCS_DIR_NAME . '/templates/taxonomy-category.php';
			}
			return $template;
		}

		/**
		 * Taxonomy Callback Function.
		 *
		 * @param array $template Overide taxonomy template.
		 */
		function tag_template( $template ) {
			if ( is_tax( 'docs_tag' ) ) {
				return EASY_DOCS_DIR_NAME . '/templates/taxonomy-tag.php';
			}
			return $template;
		}

		/**
		 * Plugin activation hook.
		 *
		 * @author Mantrabrain
		 */
		function activation() {
			// Register post types.
			Easy_Docs_post_Type::register_post_types();
			Easy_Docs_post_Type::register_taxonomies();
			flush_rewrite_rules();

		}

		/**
		 * Add Class to body hooks
		 *
		 * @param array $classes It will add class to the body doc post.
		 * @category Hooks
		 * @return $classed
		 */
		function easy_docs_body_single_class( $classes ) {

			if ( is_post_type_archive( 'docs' ) || is_singular( 'docs' ) && is_array( $classes ) ) {
					$cls = array_merge( $classes, array( 'docs-single-templates-enabled' ) );
				return $cls;
			}
			return $classes;
		}

		/**
		 * Processes this test, when one of its tokens is encountered.
		 *
		 * @param Class-easy-docs-loader $classes load.
		 * @return $classes
		 */
		function easy_docs_body_tax_class( $classes ) {
			if ( is_post_type_archive( 'docs' ) || is_tax( 'docs_category' ) || is_tax( 'docs_tag' ) && is_array( $classes ) ) {
				// Add clss to body.
				$cls = array_merge( $classes, array( 'docs-tax-templates-enabled' ) );
				return $cls;
			}
			return $classes;
		}

		/**
		 * Processes this test, when one of its tokens is encountered.
		 *
		 * @param Class-easy-docs-loader $classes load.
		 * @return $classes
		 */
		function easy_docs_body_sidebar_class( $classes ) {
			if ( is_post_type_archive( 'docs' ) || is_tax( 'docs_category' ) || is_tax( 'docs_tag' ) || is_singular( 'docs' ) && is_array( $classes ) ) {

				if ( is_active_sidebar( 'docs-sidebar-1' ) ) {
					// Add clss to body.
					$cls = array_merge( $classes, array( 'docs-sidebar-active' ) );
					return $cls;
				}
			}
			return $classes;
		}

		/**
		 * Register setting option variables.
		 */
		function register_easy_docs_plugin_settings() {
			// Register our settings.
			register_setting( 'easy-docs-settings-group', 'easy_ls_enabled' );
			register_setting( 'easy-docs-settings-group', 'easy_search_post_types' );
			register_setting( 'easy-docs-settings-group', 'easy_search_has_comments' );
			register_setting( 'easy-docs-settings-group', 'easy_docs_override_single_template' );
			register_setting( 'easy-docs-settings-group', 'easy_docs_override_category_template' );
			register_setting( 'easy-docs-settings-group', 'easy_doc_title' );
		}

		/**
		 * Regsiter option menu
		 *
		 * @category Filter
		 */
		function register_options_menu() {
			add_submenu_page(
				'edit.php?post_type=docs',
				__( 'Settings', 'easy-docs' ),
				__( 'Settings', 'easy-docs' ),
				'manage_options',
				'easy_docs_settings',
				array( $this, 'render_options_page' )
			);
		}

		/**
		 * Includes options page
		 */
		function render_options_page() {
			require_once EASY_DOCS_DIR_NAME . '/templates/options-page.php';
		}

		/**
		 * Get Archive Template for the docs base directory.
		 *
		 * @param int $archive_template Overirde archive templates.
		 * @author Mantrabrain
		 */
		function get_easy_docs_archive_template( $archive_template ) {

			if ( is_post_type_archive( EASY_DOCS_POST_TYPE ) ) {
				$archive_template = EASY_DOCS_DIR_NAME . '/templates/archive-template.php';
			}
			return $archive_template;
		}

		/**
		 * Get Single Page Template for docs base directory.
		 *
		 * @param int $single_template Overirde single templates.
		 * @author Mantrabrain
		 */
		function get_easy_docs_single_template( $single_template ) {

			if ( is_singular( 'docs' ) ) {
				$single_template = EASY_DOCS_DIR_NAME . '/templates/single-template.php';
			}
			return $single_template;
		}

		/**
		 * Renders an admin notice.
		 *
		 * @since 1.0.0
		 * @param string $message Error message.
		 * @param string $type Check type of user.
		 * @return void
		 */
		private function render_admin_notice( $message, $type = 'update' ) {

			if ( ! is_admin() ) {
				return;
			} elseif ( ! is_user_logged_in() ) {
				return;
			} elseif ( ! current_user_can( 'update_core' ) ) {
				return;
			}

			echo '<div class="' . $type . '">';
			echo '<p>' . $message . '</p>';
			echo '</div>';
		}

		/**
		 * Define constants.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		private function define_constants() {


		}

		/**
		 * Loads classes and includes.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		static private function load_files() {

			require_once EASY_DOCS_DIR_NAME . '/includes/class-easy-docs-post-type.php';
			require_once EASY_DOCS_DIR_NAME . '/templates/shortcode.php';
			require_once EASY_DOCS_DIR_NAME . '/includes/class-easy-docs-widget.php';
			require_once EASY_DOCS_DIR_NAME . '/includes/class-easy-docs-category-widget.php';
		}

		/**
		 * Enqueue frontend scripts
		 *
		 * @since 1.0.0
		 */
		function enqueue_front_scripts() {
			wp_enqueue_style( 'easy-frontend-style', EASY_DOCS_BASE_URL . '/assets/css/easy-docs.css' );

			$is_live_search = get_option( 'easy_ls_enabled' );

			if ( '1' == $is_live_search || false === $is_live_search ) {

				wp_enqueue_script( 'easy-live-search', EASY_DOCS_BASE_URL . '/assets/js/jquery.livesearch.js', array( 'jquery' ), EASY_DOCS_VERSION, true );
				wp_enqueue_script( 'easy-docs-script', EASY_DOCS_BASE_URL . '/assets/js/easy-docs.js', array( 'easy-live-search' ), EASY_DOCS_VERSION, true );

				wp_localize_script(
					'easy-docs-script',
					'easy_docs_ajax_url',
					array(
						'url' => admin_url( 'admin-ajax.php' ),
					)
				);
			}
		}

		/**
		 * Enqueue admin scripts.
		 *
		 * @since 1.0.0
		 */
		function enqueue_admin_scripts() {
			wp_enqueue_style( 'easy-options-style', EASY_DOCS_BASE_URL . '/assets/css/easy-docs-admin.css' );
		}
	}

	$easy_doc_loader = Easy_Docs_Autoloader::get_instance();
}

