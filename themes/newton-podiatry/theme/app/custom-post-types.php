<?php
// Register Custom Post Type for "Services"
function custom_post_type_services() {

    $labels = array(
        'name'                  => _x( 'Services', 'Post Type General Name', 'textdomain' ),
        'singular_name'         => _x( 'Service', 'Post Type Singular Name', 'textdomain' ),
        'menu_name'             => __( 'Services', 'textdomain' ),
        'name_admin_bar'        => __( 'Service', 'textdomain' ),
        'archives'              => __( 'Service Archives', 'textdomain' ),
        'attributes'            => __( 'Service Attributes', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Service:', 'textdomain' ),
        'all_items'             => __( 'All Services', 'textdomain' ),
        'add_new_item'          => __( 'Add New Service', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'new_item'              => __( 'New Service', 'textdomain' ),
        'edit_item'             => __( 'Edit Service', 'textdomain' ),
        'update_item'           => __( 'Update Service', 'textdomain' ),
        'view_item'             => __( 'View Service', 'textdomain' ),
        'view_items'            => __( 'View Services', 'textdomain' ),
        'search_items'          => __( 'Search Services', 'textdomain' ),
        'not_found'             => __( 'Not found', 'textdomain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'textdomain' ),
        'featured_image'        => __( 'Featured Image', 'textdomain' ),
        'set_featured_image'    => __( 'Set featured image', 'textdomain' ),
        'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
        'use_featured_image'    => __( 'Use as featured image', 'textdomain' ),
        'insert_into_item'      => __( 'Insert into service', 'textdomain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this service', 'textdomain' ),
        'items_list'            => __( 'Services list', 'textdomain' ),
        'items_list_navigation' => __( 'Services list navigation', 'textdomain' ),
        'filter_items_list'     => __( 'Filter services list', 'textdomain' ),
    );
    $args = array(
        'label'                 => __( 'Service', 'textdomain' ),
        'description'           => __( 'A custom post type for services', 'textdomain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Enables Gutenberg
    );
    register_post_type( 'services', $args );

}
add_action( 'init', 'custom_post_type_services', 0 );
