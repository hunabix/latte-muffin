<?php
// admin/settings.php
function muffin_register_settings() {
    register_setting('muffin-settings-group', 'muffin_api_key');
    register_setting('muffin-settings-group', 'muffin_evaluation_page');
}
?>