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
if ( ! function_exists('d') ) {
    
    function d() {
        call_user_func_array( 'dump' , func_get_args() );
    }

}

/**
 * Dump variables and die.
 */
if ( ! function_exists('dd') ) {

    function dd() {
        call_user_func_array( 'dump' , func_get_args() );
        die();
    }

}

// add_filter('timber/twig', 'add_custom_twig_functions');

// function add_custom_twig_functions($twig) {
//     // Add dd() to Twig
//     $twig->addFunction(new TwigFunction('dump', function($variable = null) {
//         // Check if a variable was passed, if not use global Timber context
//         if (is_null($variable)) {
//             $variable = Timber::context();
//         }
//         dump($variable);
//         die();
//     }));

//     return $twig;
// }

?>
