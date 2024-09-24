<?php
use Timber\Timber;
use Twig\TwigFunction; // Use the correct namespace for TwigFunction
use Timber\Post;


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

// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

    global $wp_version;
    if ( $wp_version !== '4.7.1' ) {
       return $data;
    }
  
    $filetype = wp_check_filetype( $filename, $mimes );
  
    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
  
  }, 10, 4 );
  
  function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter( 'upload_mimes', 'cc_mime_types' );
  
  function fix_svg() {
    echo '<style type="text/css">
          .attachment-266x266, .thumbnail img {
               width: 100% !important;
               height: auto !important;
          }
          </style>';
  }
  add_action( 'admin_head', 'fix_svg' );

  function custom_dynamic_page_title() {
    // Get the current post object
    global $post;

    // Get the current URL path
    $url_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    // Split the URL path into parts
    $url_parts = explode('/', $url_path);

    // Company name (replace with your company name)
    $company_name = 'Newton Podiatry';

    $title = '';
    // $title = array_reverse(($url_parts))

    for($i = 0; $i < count(array_reverse($url_parts)); $i++) {
        $title = $title . ucwords(str_replace('-', ' ', array_reverse($url_parts)[$i]));
        if($i < count(array_reverse($url_parts)) - 1) {
            $title = $title . ' - ';
        }
    }

    if($title !== '') {
        $title = $title . ' - ';
    }
    
    $title = $title . $company_name;
 
    // Output the title
    return esc_html($title);
}

remove_action( 'wp_head', '_wp_render_title_tag', 1 );


function has_acf_fields($acf_fields) {
    if (!$acf_fields) {
        return false; // If there are no ACF fields, return false
    }

    if(strpos($acf_fields, 'acf/hero') !== false) {
        return true; // If the ACF fields contain 'field_', return true
    }
    

    return false;
};


function auto_enqueue_assets() {
    // Get the theme directory path
    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();
    
    // Folder paths
    $css_folder = $theme_dir . '/dist/assets/';
    $js_folder = $theme_dir . '/dist/assets/';

    // Enqueue all CSS files
    if (is_dir($css_folder)) {
        foreach (glob($css_folder . '*.css') as $file) {
            $file_name = basename($file);
            wp_enqueue_style('theme-style-' . $file_name, $theme_uri . '/assets/css/' . $file_name);
        }
    }

    // Enqueue all JS files
    if (is_dir($js_folder)) {
        foreach (glob($js_folder . '*.js') as $file) {
            $file_name = basename($file);
            wp_enqueue_script('theme-script-' . $file_name, $theme_uri . '/assets/js/' . $file_name, array(), false, true);
        }
    }
}
add_action('wp_enqueue_scripts', 'auto_enqueue_assets');

    


?>
