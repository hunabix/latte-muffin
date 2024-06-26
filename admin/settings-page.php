<?php
// admin/settings-page.php

function muffin_menu() {
    add_menu_page(
        'MUFFIN Settings',           // Título de la página
        'MUFFIN',                    // Título del menú
        'manage_options',            // Capacidad requerida para ver este menú
        'muffin-settings',           // Slug del menú
        'muffin_settings_page',      // Función que muestra el contenido de la página
        'dashicons-admin-generic',   // Icono del menú
        6                            
    );
}

function muffin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Configuración de MUFFIN</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('muffin-settings-group');
            do_settings_sections('muffin-settings-group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Clave API de OpenAI</th>
                    <td><input type="text" name="muffin_api_key" value="<?php echo esc_attr(get_option('muffin_api_key')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Página de Evaluación</th>
                    <td>
                        <select name="muffin_evaluation_page">
                            <?php
                            $pages = get_pages();
                            foreach ($pages as $page) {
                                $selected = (get_option('muffin_evaluation_page') == $page->ID) ? 'selected' : '';
                                echo "<option value='{$page->ID}' $selected>{$page->post_title}</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
?>