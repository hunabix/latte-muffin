<?php
/**
 * Plugin Name: MUFFIN
 * Plugin URI:  https://alejandro.sobrevilla.mx/latte/
 * Description: Módulo Uniforme con doble Feedback usando Inteligencia Neuronal
 * Version:     1.0
 * Author:      Alejandro Sobrevilla
 * Author URI:  https://alejandro.sobrevilla.mx/
 * License:     GPL2
 */

// Evitar el acceso directo al archivo
if ( !defined('ABSPATH') ) {
    die('No direct access allowed');
}

require __DIR__ . '/vendor/autoload.php';  // Cargar autoload de Composer

use GuzzleHttp\Client;

// Función para obtener una respuesta de la API de ChatGPT
function obtener_respuesta_chatgpt($mensaje) {
    $api_key = get_option('muffin_api_key');  // Obtener la clave API de las opciones de WordPress
    $client = new Client();
    
    try {
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $mensaje]
                ],
                'max_tokens' => 50,
            ],
        ]);

        $body = $response->getBody();
        $result = json_decode($body, true);
        return $result['choices'][0]['message']['content'];

    } catch (RequestException $e) {
        error_log($e->getMessage()); // Registrar el error
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            if ($response->getStatusCode() == 429) {
                return 'Has excedido tu cuota de la API. Por favor, revisa tu plan y detalles de facturación en OpenAI.';
            } else {
                return 'Error en la solicitud a la API: ' . $response->getReasonPhrase();
            }
        } else {
            return 'Error al conectar con la API de OpenAI.';
        }
    }
}

// Función para añadir el mensaje al pie de página
function agregar_mensaje_pie_de_pagina() {
    $mensaje = "Di algo interesante sobre la inteligencia artificial con menos de 30 palabras.";
    $respuesta = obtener_respuesta_chatgpt($mensaje);
    echo '<p style="text-align: center;">' . esc_html($respuesta) . '</p>';
}

// Hook para añadir la función al pie de página
add_action('wp_footer', 'agregar_mensaje_pie_de_pagina');

// Añadir el menú al panel de administración
function muffin_menu() {
    add_menu_page(
        'MUFFIN Settings', 
        'MUFFIN', 
        'manage_options', 
        'muffin-settings', 
        'muffin_settings_page',
        'dashicons-admin-generic',
        6
    );
}
add_action('admin_menu', 'muffin_menu');

// Página de configuración del plugin
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
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Registrar la configuración
function muffin_register_settings() {
    register_setting('muffin-settings-group', 'muffin_api_key');
}
add_action('admin_init', 'muffin_register_settings');
