<?php
use Timber\Timber;
use Twig\TwigFunction; // Use the correct namespace for TwigFunction


/*
|--------------------------------------------------------------------------
| Helper functions
|--------------------------------------------------------------------------
*/

/**
 * Dump variable.
 */


add_filter('timber/twig', 'add_custom_twig_functions');

function add_custom_twig_functions($twig) {
    // Add dd() to Twig
    $twig->addFunction(new TwigFunction('dump', function($variable = null) {
        // Check if a variable was passed, if not use global Timber context
        if (is_null($variable)) {
            $variable = Timber::context();
        }
        dump($variable);
        die();
    }));

    return $twig;
}

add_filter('timber/twig', 'add_custom_functions_to_twig');

function add_custom_functions_to_twig($twig) {
    // Add custom excerpt function
    $twig->addFunction(new \Twig\TwigFunction('get_custom_excerpt', function($post, $word_limit = 15) {
        $excerpt = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
        $excerpt = wp_strip_all_tags($excerpt); // Remove HTML tags
        $words = explode(' ', $excerpt);
        
        if (count($words) > $word_limit) {
            $words = array_slice($words, 0, $word_limit);
            return implode(' ', $words) . '...';
        }

        return implode(' ', $words);
    }));

    return $twig;
}

?>
