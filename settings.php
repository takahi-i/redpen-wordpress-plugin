<?php
add_action('admin_init', 'redpen_register_settings');

function redpen_register_settings() {
    add_settings_section('redpen', 'RedPen Server', 'redpen_settings_section', 'writing');
    add_settings_field('redpen_base_url', 'URL', 'redpen_setting_field', 'writing', 'redpen');
    register_setting('writing', 'redpen_base_url', 'redpen_sanitize_url');
}

function redpen_settings_section() {
    echo '<p>Change the URL below if you want to use your own instance of RedPen Server.</p>';
}

function redpen_setting_field() {
    echo '<input type="text" class="regular-text" name="redpen_base_url" value="' . esc_attr(get_option('redpen_base_url')). '">';
}

function redpen_sanitize_url($url) {
    if (!preg_match('/^https?:/', $url))
        $url = 'http://' . $url;
    if (!preg_match('/\/$/', $url))
        $url .= '/';
    return $url;
}
?>