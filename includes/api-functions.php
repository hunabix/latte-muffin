<?php
// includes/api-functions.php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Función para obtener una respuesta de ChatGPT usando la API de OpenAI.
 *
 * @param string $instructions Instrucciones para el modelo.
 * @param string $rubric Rúbrica para evaluar.
 * @param string $student_work Trabajo del alumno.
 * @return string Respuesta de la API de OpenAI.
 */
function obtener_respuesta_chatgpt($instructions, $rubric, $student_work) {
    // Obtener el API key desencriptado
    $api_key = get_decrypted_api_key();
    
    // Verificar si el API key está configurado
    if (!$api_key) {
        return 'Clave API no configurada.';
    }

    // Crear un cliente GuzzleHttp para hacer la solicitud
    $client = new Client();

    try {
        // Hacer una solicitud POST a la API de OpenAI
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key, // Añadir la autorización con el API key desencriptado
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo', // Modelo a usar
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un asistente para evaluar trabajos académicos.'],
                    ['role' => 'user', 'content' => "Instrucciones: $instructions\nRúbrica: $rubric\nTrabajo del alumno: $student_work"]
                ],
                'max_tokens' => 500,
            ],
        ]);

        // Obtener el cuerpo de la respuesta
        $body = $response->getBody();
        // Decodificar el JSON de la respuesta
        $result = json_decode($body, true);
        // Devolver el contenido del mensaje de la API
        return $result['choices'][0]['message']['content'];

    } catch (RequestException $e) {
        // Registrar el error en el log
        error_log($e->getMessage());
        
        // Manejar errores específicos de la respuesta
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


/**
 * Función para obtener y desencriptar el API key de la base de datos.
 *
 * @return string API key desencriptado.
 */
function get_decrypted_api_key() {
    // Obtener la clave API encriptada almacenada en la base de datos.
    $encrypted_key = get_option('muffin_api_key');
    // Desencriptar la clave API usando base64_decode.
    return base64_decode($encrypted_key);
}


?>
