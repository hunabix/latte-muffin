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
use GuzzleHttp\Exception\RequestException;

// Función para obtener una respuesta de la API de ChatGPT
function obtener_respuesta_chatgpt($mensaje) {
    $api_key = get_option('muffin_api_key');  // Obtener la clave API de las opciones de WordPress
    if (!$api_key) {
        return 'Clave API no configurada.';
    }
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

// Función para añadir el botón y el contenedor en el pie de página
function agregar_mensaje_pie_de_pagina() {
    ?>
    <div id="chatgpt-response" style="text-align: center; margin-top: 20px;"></div>
    <div style="text-align: center; margin-top: 20px;">
        <button id="chatgpt-button">Obtener respuesta de ChatGPT</button>
    </div>
    <script type="text/javascript">
        document.getElementById('chatgpt-button').addEventListener('click', function() {
            var mensaje = "Di algo interesante sobre la inteligencia artificial.";
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('chatgpt-response').innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "<?php echo admin_url('admin-ajax.php'); ?>", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("action=obtener_respuesta_chatgpt&mensaje=" + encodeURIComponent(mensaje));
        });
    </script>
    <?php
}

add_action('wp_footer', 'agregar_mensaje_pie_de_pagina');

// Función para manejar la solicitud AJAX
function ajax_obtener_respuesta_chatgpt() {
    if (isset($_POST['mensaje'])) {
        $mensaje = sanitize_text_field($_POST['mensaje']);
        $respuesta = obtener_respuesta_chatgpt($mensaje);
        echo esc_html($respuesta);
    }
    wp_die();
}

add_action('wp_ajax_obtener_respuesta_chatgpt', 'ajax_obtener_respuesta_chatgpt');
add_action('wp_ajax_nopriv_obtener_respuesta_chatgpt', 'ajax_obtener_respuesta_chatgpt');

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
