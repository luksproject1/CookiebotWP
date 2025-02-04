<?php
namespace cybot\cookiebot\lib\api;

class Account_API {
    const API_BASE = 'https://api.ea.dev.usercentrics.cloud/v1';

    public static function register_ajax_handlers() {
        add_action('wp_ajax_cookiebot_create_account', array(__CLASS__, 'handle_create_account'));
    }

    public static function handle_create_account() {
        check_ajax_referer('cookiebot_create_account', 'nonce');

        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        $domain = esc_url_raw($_POST['domain']);

        // Step 1: Register account
        $register_response = wp_remote_post(self::API_BASE . '/auth/register', array(
            'headers' => array(
                'accept' => 'application/json',
                'X-EA-Mock' => 'true',
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode(array(
                'email' => $email,
                'password' => $password,
                'domain' => $domain
            ))
        ));

        if (is_wp_error($register_response)) {
            wp_send_json_error(array('message' => $register_response->get_error_message()));
        }

        $register_body = json_decode(wp_remote_retrieve_body($register_response), true);
        
        // If registration successful, create configuration
        if (wp_remote_retrieve_response_code($register_response) === 200) {
            $auth_token = $register_body['token']; // Assuming token is in response
            
            $config_response = wp_remote_post(self::API_BASE . '/configurations', array(
                'headers' => array(
                    'accept' => 'application/json',
                    'X-EA-Mock' => 'true',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $auth_token
                ),
                'body' => json_encode(array(
                    'domain' => $domain
                ))
            ));
            
            if (!is_wp_error($config_response)) {
                $config_body = json_decode(wp_remote_retrieve_body($config_response), true);
                wp_send_json_success(array(
                    'register' => $register_body,
                    'config' => $config_body
                ));
            }
        }

        // If we get here, something went wrong
        wp_send_json_error($register_body);
    }
} 