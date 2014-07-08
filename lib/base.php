<?php

class BASE {
    
    //Constructor
    public function __construct() {
        
    }
    
    /*
     * 
     * Custom Post Type
     * 
     */
    public function create_post_type() {
        $labels = array(
            'name' => _x('FAQs', 'post type general name', LANG_DOMAIN),
            'singular_name' => _x('FAQ', 'post type singular name', LANG_DOMAIN),
            'add_new' => _x('Add New', 'Slide', LANG_DOMAIN),
            'add_new_item' => __('Add New FAQ', LANG_DOMAIN),
            'edit_item' => __('Edit FAQ', LANG_DOMAIN),
            'new_item' => __('New FAQ', LANG_DOMAIN),
            'all_items' => __('All FAQs', LANG_DOMAIN),
            'view_item' => __('View FAQs', LANG_DOMAIN),
            'search_items' => __('Search FAQs', LANG_DOMAIN),
            'not_found' => __('No FAQ found', LANG_DOMAIN),
            'not_found_in_trash' => __('No FAQ found in Trash', LANG_DOMAIN),
            'parent_item_colon' => '',
            'menu_name' => __('FAQs', LANG_DOMAIN)
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => _x('faqs', 'URL slug', LANG_DOMAIN)),
            'capability_type' => 'page',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'page-attributes')
        );
        register_post_type('faq', $args);
    }
    
    
    /*
     * 
     * Custom category taxonomy for FAQs
     * 
     */
    public function faq_category() {
	
        // create a new taxonomy
        $labels = array(
            'name' => _x( 'FAQ Categories', 'taxonomy general name', LANG_DOMAIN ),
            'singular_name' => _x( 'faq_categoriy', 'taxonomy singular name', LANG_DOMAIN ),
            'search_items' =>  __( 'Search Categories', LANG_DOMAIN ),
            'all_items' => __( 'All Categories', LANG_DOMAIN ),
            'parent_item' => __( 'Parent Category', LANG_DOMAIN ),
            'parent_item_colon' => __( 'Parent Category:', LANG_DOMAIN ),
            'edit_item' => __( 'Edit Category', LANG_DOMAIN ),
            'update_item' => __( 'Update Category', LANG_DOMAIN ),
            'add_new_item' => __( 'Add New Category', LANG_DOMAIN ),
            'new_item_name' => __( 'New Category Name', LANG_DOMAIN ),
        );
	register_taxonomy(
		'faq_categories',
		array('faq'),
		array(
                    'hierarchical' => true,
                    'labels' => $labels,
                    'show_ui' => true,
                    'query_var' => true,
                    'show_admin_column' => true,
                    'rewrite' => array( 'slug' => 'recordings' ),
                     )
       );
    }
    
    /*
     * 
     * Adding Styles and Scripts
     * 
     */
    public function user_faq_styles() {
        wp_register_script( 'accordion_js', PLUGIN_PATH_JS, array( 'jquery' ) );
        wp_enqueue_script( 'accordion_js' );
        
        wp_register_style( 'accordion_css', PLUGIN_PATH_CSS);
        wp_enqueue_style( 'accordion_css' );
    }
    
    /*
     * 
     * Adding column in taxonomy
     * 
     */
    public function posts_columns_id($columns) {
        return $columns + array ( 'tax_id' => 'ID' );    
    }

    public function posts_custom_id_columns($v, $name, $id) {
        return $id;
    }
    
}
