<?php

// Ensure Timber is included
if ( ! class_exists( 'Timber' ) ) {
    add_action( 'admin_notices', function() {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">plugins page</a></p></div>';
    } );
    return;
}

$args = array(
    'post_type'      => 'services',  // Your custom post type
    'posts_per_page' => -1,          // Get all posts
);

$services_query = Timber::get_posts($args);

// Convert the Timber\PostQuery to an array
$services_array = $services_query->to_array();

// Chunk the array of posts into groups of 4
$context['services'] = array_chunk($services_array, 4);
