<?php
// admin/settings.php
function muffin_register_settings() {
    register_setting('muffin-settings-group', 'muffin_api_key', 'sanitize_and_encrypt_api_key');
    register_setting('muffin-settings-group', 'muffin_evaluation_page');
}

function sanitize_and_encrypt_api_key($api_key) {
    // Sanitiza y encripta el API key
    $api_key = sanitize_text_field($api_key);
    return base64_encode($api_key);
}
?>