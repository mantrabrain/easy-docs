<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 *
 * @class     Easy_Docs_post_Type
 * @category  Class
 * @author    Mantrabrain
 * @package   Documentation/PostType
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Easy_Docs_Post_Type Class.
 */
class Easy_Docs_Post_Type {

	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_filter( 'rest_api_allowed_post_types', array( __CLASS__, 'rest_api_allowed_post_types' ) );
	}

	/**
	 * Register core taxonomies.
	 */
	public static function register_taxonomies() {

		if ( ! is_blog_installed() ) {
			return;
		}

		do_action( 'easy_docs_before_register_taxonomy' );

		register_taxonomy(
			'docs_category',
			apply_filters( 'docs_category', array( EASY_DOCS_POST_TYPE ) ),
			array(
				'hierarchical' => true,
				'label'        => __( 'Categories', 'easy-docs' ),
				'labels'       => array(
					'name'              => __( 'Docs categories', 'easy-docs' ),
					'singular_name'     => __( 'Category', 'easy-docs' ),
					'menu_name'         => _x( 'Categories', 'Admin menu name', 'easy-docs' ),
					'search_items'      => __( 'Search categories', 'easy-docs' ),
					'all_items'         => __( 'All categories', 'easy-docs' ),
					'parent_item'       => __( 'Parent category', 'easy-docs' ),
					'parent_item_colon' => __( 'Parent category:', 'easy-docs' ),
					'edit_item'         => __( 'Edit category', 'easy-docs' ),
					'update_item'       => __( 'Update category', 'easy-docs' ),
					'add_new_item'      => __( 'Add new category', 'easy-docs' ),
					'new_item_name'     => __( 'New category name', 'easy-docs' ),
					'not_found'         => __( 'No categories found', 'easy-docs' ),
				),
				'show_ui'      => true,
				'query_var'    => true,
				'show_in_rest' => true,
				'rewrite'      => array(
					'slug'         => 'docs-category',
					'with_front'   => false,
					'hierarchical' => true,
				),
			)
		);

		register_taxonomy(
			'docs_tag',
			apply_filters( 'easy_taxonomy_objects_docs_tag', array( EASY_DOCS_POST_TYPE ) ),
			apply_filters(
				'easy_taxonomy_args_docs_tag',
				array(
					'hierarchical' => false,
					'label'        => __( 'Docs tags', 'easy-docs' ),
					'labels'       => array(
						'name'                       => __( 'Docs tags', 'easy-docs' ),
						'singular_name'              => __( 'Tag', 'easy-docs' ),
						'menu_name'                  => _x( 'Tags', 'Admin menu name', 'easy-docs' ),
						'search_items'               => __( 'Search tags', 'easy-docs' ),
						'all_items'                  => __( 'All tags', 'easy-docs' ),
						'edit_item'                  => __( 'Edit tag', 'easy-docs' ),
						'update_item'                => __( 'Update tag', 'easy-docs' ),
						'add_new_item'               => __( 'Add new tag', 'easy-docs' ),
						'new_item_name'              => __( 'New tag name', 'easy-docs' ),
						'popular_items'              => __( 'Popular tags', 'easy-docs' ),
						'separate_items_with_commas' => __( 'Separate tags with commas', 'easy-docs' ),
						'add_or_remove_items'        => __( 'Add or remove tags', 'easy-docs' ),
						'choose_from_most_used'      => __( 'Choose from the most used tags', 'easy-docs' ),
						'not_found'                  => __( 'No tags found', 'easy-docs' ),
					),
					'show_ui'      => true,
					'query_var'    => true,
					'show_in_rest' => true,
					'rewrite'      => array(
						'slug'       => 'docs-tag',
						'with_front' => false,
					),
				)
			)
		);

		do_action( 'easy_docs_after_register_taxonomy' );
	}

	/**
	 * Register core post types.
	 */
	public static function register_post_types() {
		if ( ! is_blog_installed() || post_type_exists( 'easy_docs' ) ) {
			return;
		}

		do_action( 'easy_docs_register_post_type' );

		$supports = array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'author',
			'revisions',
			'custom-fields',
		);

		$has_comments = get_option( 'easy_search_has_comments' );
		$has_comments = ! $has_comments ? false : $has_comments;

		if ( ! $has_comments ) {
			$supports[] = 'comments';
		}

		register_post_type(
			EASY_DOCS_POST_TYPE,
			apply_filters(
				'easy_register_post_type_docs',
				array(
					'labels'              => array(
						'name'                  => __( 'Docs', 'easy-docs' ),
						'singular_name'         => __( 'Doc', 'easy-docs' ),
						'menu_name'             => _x( 'Docs', 'Admin menu name', 'easy-docs' ),
						'add_new'               => __( 'Add Doc', 'easy-docs' ),
						'add_new_item'          => __( 'Add New Doc', 'easy-docs' ),
						'edit'                  => __( 'Edit', 'easy-docs' ),
						'edit_item'             => __( 'Edit Doc', 'easy-docs' ),
						'new_item'              => __( 'New Doc', 'easy-docs' ),
						'view'                  => __( 'View Doc', 'easy-docs' ),
						'view_item'             => __( 'View Doc', 'easy-docs' ),
						'search_items'          => __( 'Search Docs', 'easy-docs' ),
						'not_found'             => __( 'No Docs found', 'easy-docs' ),
						'not_found_in_trash'    => __( 'No Docs found in trash', 'easy-docs' ),
						'parent'                => __( 'Parent Doc', 'easy-docs' ),
						'featured_image'        => __( 'Docs image', 'easy-docs' ),
						'set_featured_image'    => __( 'Set Docs image', 'easy-docs' ),
						'remove_featured_image' => __( 'Remove Docs image', 'easy-docs' ),
						'use_featured_image'    => __( 'Use as Docs image', 'easy-docs' ),
						'items_list'            => __( 'Docs list', 'easy-docs' ),
					),
					'description'         => __( 'This is where you can add new docs to your site.', 'easy-docs' ),
					'public'              => true,
					'show_ui'             => true,
					'map_meta_cap'        => true,
					'publicly_queryable'  => true,
					'exclude_from_search' => false,
					'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
					'query_var'           => true,
					'supports'            => $supports,
					'has_archive'         => true,
					'show_in_nav_menus'   => true,
					'show_in_rest'        => true,
				)
			)
		);

		do_action( 'easy_docs_after_register_post_type' );
	}

	/**
	 * Added post type to allowed for rest api
	 *
	 * @param  array $post_types Get the docs post types.
	 * @return array
	 */
	public static function rest_api_allowed_post_types( $post_types ) {
		$post_types[] = 'docs';

		return $post_types;
	}
}

Easy_Docs_post_Type::init();


