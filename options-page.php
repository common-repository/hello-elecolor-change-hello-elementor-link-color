<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Create the options page
function hello_elecolor_options_page() {
    // Dashicons icon code
    $icon_code = 'dashicons-marker';

    add_menu_page(
        'Hello EleColor Options',
        'EleColor Options',
        'manage_options',
        'hello-elecolor-options',
        'hello_elecolor_options_page_content',
        $icon_code // Specify the Dashicons icon code here
    );
}
add_action('admin_menu', 'hello_elecolor_options_page');

// Define the options page content
function hello_elecolor_options_page_content() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if the form is submitted with nonce verification
    if (isset($_POST['custom_link_color']) && wp_verify_nonce($_POST['elecolor_nonce'], 'elecolor_nonce_action')) {
        update_option('hello_elecolor_custom_link_color', sanitize_hex_color($_POST['custom_link_color']));
        echo '<div class="updated"><p>Link color updated.</p></div>';
    }

    // Check if the "Reset to Default" button is clicked
    if (isset($_POST['reset_to_default']) && wp_verify_nonce($_POST['reset_default_nonce'], 'reset_default_action')) {
        update_option('hello_elecolor_custom_link_color', '#0073e6'); // Replace with your default color
        echo '<div class="updated"><p>Link color reset to default.</p></div>';
    }

    // Get the current link color setting
    $link_color = get_option('hello_elecolor_custom_link_color', '#0073e6');

    ?>
    <style>
        /* Custom CSS for adjusting the icon size */
        .dashicons-admin-links:before {
            content: '\f159'; /* Replace with the desired Dashicons icon code */
            font-size: 20px; /* Set the font size as needed */
            line-height: 1; /* Adjust line-height if needed */
        }

        /* Two-column layout */
        .wrap {
            display: flex;
        }

        .wrap .column {
            flex: 1;
            padding: 20px;
        }

        /* Card Styles */
        .intro-card {
            background-color: #f7f7f7;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 20px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        table td {
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>

    <div class="wrap">
        <div class="column" style="width: 80%;">
            <h2>Hello EleColor Options</h2>
            <p>You can enter a valid CSS color code (e.g., #0073e6) in the field below and click "Save Link Color" to apply the color to all links on your website:</p>
            <form method="post" action="">
                <?php wp_nonce_field('elecolor_nonce_action', 'elecolor_nonce'); ?>
                <table>
                    <tr>
                        <td><label for="custom_link_color">Link Color:</label></td>
                        <td>
                            <input type="text" id="custom_link_color" name="custom_link_color" value="<?php echo esc_attr($link_color); ?>" class="regular-text">
                            <p class="description">Enter a valid CSS color code (e.g., #0073e6) for the link color.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save Link Color'); ?>
            </form>

            <form method="post" action="">
                <?php wp_nonce_field('reset_default_action', 'reset_default_nonce'); ?>
                <?php submit_button('Reset to Default', 'secondary', 'reset_to_default'); ?>
            </form>
        </div>
        <div class="column" style="width: 20%;">
            <div class="intro-card">
                <h3>About Plugin</h3>
                <p>Welcome to the Hello EleColor plugin options page. This plugin allows you to customize the link color on your website easily.</p>
            </div>
            <div class="intro-card">
                <h3>About Lemerit</h3>
                <p>Lemerit Ltd. is the developer behind the Hello EleColor plugin. We specialize in creating WordPress solutions to enhance your website's functionality.</p>
                <p>Contact us for support or further inquiries:</p>
                <a href="https://www.lemerit.com/contact" class="button-primary">Contact Us</a>
                <a href="https://www.lemerit.com/support" class="button-secondary">Support</a>
            </div>
        </div>
    </div>
    <?php

    // Enqueue custom CSS for the front end
    add_action('wp_head', 'hello_elecolor_enqueue_styles');
}

// Enqueue custom CSS for the front end
function hello_elecolor_enqueue_styles() {
    $link_color = get_option('hello_elecolor_custom_link_color', '#0073e6');
    echo '<style type="text/css">a { color: ' . esc_attr($link_color) . '; }</style>';
}
add_action('wp_head', 'hello_elecolor_enqueue_styles');
?>
