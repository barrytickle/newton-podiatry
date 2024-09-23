<?php
/*
Plugin Name: Form Submissions Plugin
Description: A plugin to capture and store form submissions in JSON, view individual submissions, and send them via email.
Version: 1.0
Author: Your Name
*/

// Register activation hook to create the database table with JSON field
register_activation_hook(__FILE__, 'fsp_create_table');
function fsp_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'form_submissions';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        submission_data longtext NOT NULL,
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Add the menu page
add_action('admin_menu', 'fsp_create_menu');
function fsp_create_menu() {
    add_menu_page(
        'Form Submissions',     // Page title
        'Form Submissions',     // Menu title
        'manage_options',       // Capability
        'form-submissions',     // Menu slug
        'fsp_render_admin_page',// Function to render the page
        'dashicons-feedback',   // Icon
        6                       // Position
    );
}

// Render admin page with view button
function fsp_render_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'form_submissions';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    echo '<div class="wrap"><h2>Form Submissions</h2>';
    echo '<table class="widefat"><thead><tr><th>ID</th><th>Submission Data</th><th>Submitted At</th><th>Actions</th></tr></thead><tbody>';

    foreach ($results as $row) {
        echo "<tr><td>{$row->id}</td><td>" . wp_trim_words(json_encode(json_decode($row->submission_data), true), 10, '...') . "</td><td>{$row->submitted_at}</td><td><a href='?page=form-submissions-view&id={$row->id}' class='button'>View</a></td></tr>";
    }

    echo '</tbody></table></div>';
}

// Add a view page for individual submissions
add_action('admin_menu', 'fsp_add_view_page');
function fsp_add_view_page() {
    add_submenu_page(
        null, // Hide the submenu from the main menu
        'View Submission', 
        'View Submission', 
        'manage_options', 
        'form-submissions-view', 
        'fsp_render_view_page'
    );
}

// Render the view page for submission
function fsp_render_view_page() {
    global $wpdb;
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $table_name = $wpdb->prefix . 'form_submissions';
    $result = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $id");

    if (!$result) {
        echo "<div class='wrap'><h2>No submission found</h2></div>";
        return;
    }

    $submission_data = json_decode($result->submission_data, true);

    echo '<div class="wrap"><h2>View Submission</h2>';
    echo '<table class="widefat"><thead><tr><th>Field Name</th><th>Value</th></tr></thead><tbody>';

    foreach ($submission_data as $field_name => $value) {
        echo "<tr><td>{$field_name}</td><td>{$value}</td></tr>";
    }

    echo '</tbody></table></div>';
}

// Add a tab for settings
add_action('admin_menu', 'fsp_add_settings_tab');
function fsp_add_settings_tab() {
    add_submenu_page(
        'form-submissions',         // Parent slug
        'Settings',                 // Page title
        'Settings',                 // Menu title
        'manage_options',           // Capability
        'form-submissions-settings',// Menu slug
        'fsp_render_settings_page'  // Function to render the page
    );
}

// Render settings page
function fsp_render_settings_page() {
    ?>
    <div class="wrap">
        <h2>Form Submissions Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('fsp_settings_group'); ?>
            <?php do_settings_sections('fsp_settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'fsp_register_settings');
function fsp_register_settings() {
    register_setting('fsp_settings_group', 'fsp_email_address');
    register_setting('fsp_settings_group', 'fsp_thank_you_page');

    add_settings_section(
        'fsp_settings_section',
        'Email & Redirect Settings',
        null,
        'fsp_settings'
    );

    add_settings_field(
        'fsp_email_address',
        'Email Address',
        'fsp_email_address_callback',
        'fsp_settings',
        'fsp_settings_section'
    );

    add_settings_field(
        'fsp_thank_you_page',
        'Thank You Page URL',
        'fsp_thank_you_page_callback',
        'fsp_settings',
        'fsp_settings_section'
    );
}

// Render email input field
function fsp_email_address_callback() {
    $email = get_option('fsp_email_address', '');
    echo "<input type='email' name='fsp_email_address' value='" . esc_attr($email) . "' />";
}

// Render thank-you page input field
function fsp_thank_you_page_callback() {
    $thank_you_page = get_option('fsp_thank_you_page', '');
    echo "<input type='url' name='fsp_thank_you_page' value='" . esc_attr($thank_you_page) . "' />";
}

// Handle form submissions and store them as JSON
add_action('wp_ajax_fsp_submit_form', 'fsp_handle_form_submission');
add_action('wp_ajax_nopriv_fsp_submit_form', 'fsp_handle_form_submission');

function fsp_handle_form_submission() {
    global $wpdb;

    $submission_data = json_encode($_POST); // Capture all form fields and values as JSON

    $table_name = $wpdb->prefix . 'form_submissions';
    $wpdb->insert($table_name, [
        'submission_data' => $submission_data
    ]);

    // Send email if email address is set
    $admin_email = get_option('fsp_email_address');
    if ($admin_email) {
        $subject = 'New Form Submission';
        $body = "New submission received:\n\n" . print_r($_POST, true);
        wp_mail($admin_email, $subject, $body);
    }

    // Redirect to thank you page if set
    $thank_you_page = get_option('fsp_thank_you_page');
    if ($thank_you_page) {
        wp_send_json_success(['redirect' => $thank_you_page]);
    } else {
        wp_send_json_success('Form submitted successfully!');
    }
}

// Front-end form submission handler
add_action('wp_enqueue_scripts', 'fsp_enqueue_scripts');
function fsp_enqueue_scripts() {
    ?>
    <script type="text/javascript">
    jQuery('#fsp-form').on('submit', function(e) {
        e.preventDefault();
        var data = jQuery(this).serialize();
        jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data + '&action=fsp_submit_form', function(response) {
            if (response.data.redirect) {
                window.location.href = response.data.redirect;
            } else {
                alert(response.data);
            }
        });
    });
    </script>
    <?php
}
