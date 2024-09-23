<?php

function find_page_by_keyword($keyword) {
    // Query all pages
    $args = array(
        'post_type' => 'page',
        'posts_per_page' => -1, // Get all pages
    );
    
    $pages = get_posts($args);
    foreach ($pages as $page) {
        $page_url = get_permalink($page->ID);
        // Check if the keyword exists in the page URL
        if (strpos($page_url, $keyword) !== false) {
            return $page_url;
        }
    }
    return null; // Return null if no match is found
}