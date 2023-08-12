<?php
require 'path/to/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;


$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://example.com/path/to/details.json',
    __FILE__,
    'unique-plugin-or-theme-slug'
);

/*
Plugin Name: Currency Price Checker
Description: Checks currency price using an external API and creates a post if the price changes.
Version: 1.1
*/
 



 


function currency_price_checker_create_table(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'currency_price_checker_log';

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        log_id INT AUTO_INCREMENT PRIMARY KEY,
        currency_code VARCHAR(10) NOT NULL,
        price FLOAT NOT NULL,
        checked_at DATETIME NOT NULL,
        status VARCHAR(20) NOT NULL,
        created_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}
register_activation_hook( __FILE__, 'currency_price_checker_create_table' );

add_action('save_post', 'check_currency_price_on_save');
function check_currency_price_on_save($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (get_post_type($post_id) !== 'post') {
        return;
    }

    $api_url = 'https://api.wallex.ir/v1/currencies/stats';
    $response = wp_remote_get($api_url);

    if (is_array($response) && !is_wp_error($response)) {
        $data = json_decode($response['body'], true);

        if ($data && isset($data['price']) && is_numeric($data['price'])) {
            $current_price = floatval($data['price']);

            $last_price = get_option('last_currency_price');
            if (!$last_price || $current_price !== $last_price) {
                // Create a new post with the updated price
                $post_title = 'Currency Price: ' . $current_price;
                $post_content = 'The current price of the currency is: ' . $current_price;

                // Create the post
                $post_data = array(
                    'post_title'   => $post_title,
                    'post_content' => $post_content,
                    'post_status'  => 'publish',
                    'post_type'    => 'post',
                );

                $post_id = wp_insert_post($post_data);
                update_option('last_currency_price', $current_price);

               
            }
        }
    }
}
 
 
add_action('admin_menu', 'currency_price_checker_add_settings_page');

function currency_price_checker_add_settings_page() {
    add_menu_page(
        'Currency Price Checker Settings',
        'Currency Price Checker',
        'manage_options',
        'currency_price_checker_settings',
        'currency_price_checker_settings_page'
    );
}

function currency_price_checker_settings_page() {
    // Handle form submission and save settings
    if (isset($_POST['currency_price_checker_submit'])) {
        update_option('currency_price_checker_bitcoin', isset($_POST['bitcoin']));
        update_option('currency_price_checker_ethereum', isset($_POST['ethereum']));
        update_option('currency_price_checker_bitcoincash', isset($_POST['bitcoincash']));
        update_option('currency_price_checker_litecoin', isset($_POST['litecoin']));
        echo '<div class="notice notice-success"><p>Settings updated successfully.</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Currency Price Checker Settings</h1>
        <form method="post">
            <label for="bitcoin">
                <input type="checkbox" id="bitcoin" name="bitcoin" <?php checked(get_option('currency_price_checker_bitcoin'), 1); ?>>
                Bitcoin
            </label><br>
            <label for="ethereum">
                <input type="checkbox" id="ethereum" name="ethereum" <?php checked(get_option('currency_price_checker_ethereum'), 1); ?>>
                Ethereum
            </label><br>
            <label for="bitcoincash">
                <input type="checkbox" id="bitcoincash" name="bitcoincash" <?php checked(get_option('currency_price_checker_bitcoincash'), 1); ?>>
                Bitcoin Cash
            </label><br>
            <label for="litecoin">
                <input type="checkbox" id="litecoin" name="litecoin" <?php checked(get_option('currency_price_checker_litecoin'), 1); ?>>
                LiteCoin
            </label><br>
            <input type="submit" name="currency_price_checker_submit" class="button button-primary" value="Save Settings">
        </form>
    </div>
    <?php
}

add_shortcode('currency_prices', 'currency_prices_shortcode');

function currency_prices_shortcode() {
    ob_start();
    ?>
    <div class="currency-prices">
        <?php
        if (get_option('currency_price_checker_bitcoin')) {
            echo '<h2>Bitcoin Price</h2>';
            echo get_currency_price('Bitcoin');
        }
        if (get_option('currency_price_checker_ethereum')) {
            echo '<h2>Ethereum Price</h2>';
            echo get_currency_price('Ethereum');
        }
        if (get_option('currency_price_checker_bitcoincash')) {
            echo '<h2>Bitcoin Cash Price</h2>';
            echo get_currency_price('Bitcoin Cash');
        }
        if (get_option('currency_price_checker_litecoin')) {
            echo '<h2>LiteCoin Price</h2>';
            echo get_currency_price('LiteCoin');
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}

function get_currency_price($currency_name) {
    $api_url = 'https://api.wallex.ir/v1/currencies/stats';
    $response = wp_remote_get($api_url);

    if (is_array($response) && !is_wp_error($response)) {
        $data = json_decode($response['body'], true);

        if ($data && isset($data['result']) && is_array($data['result'])) {
            foreach ($data['result'] as $currency) {
                if ($currency['name_en'] === $currency_name && isset($currency['price'])) {
                    $current_price = floatval($currency['price']);

                    // Log the price in the database
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'currency_price_checker_log';

                    $log_data = array(
                        'currency_code' => $currency_name,
                        'price' => $current_price,
                        'checked_at' => current_time('Y-m-d H:i:s'),
                        'status' => 'Viewed',
                    );

                    $wpdb->insert($table_name, $log_data);

                    return '<p>The current price of ' . $currency_name . ' is: ' . $current_price . '</p>';
                }
            }
        }
    }

    return '<p>Unable to retrieve the price for ' . $currency_name . ' at the moment.</p>';
}

 