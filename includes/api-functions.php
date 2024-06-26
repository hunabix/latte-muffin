<?php
// includes/api-functions.php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

function obtener_respuesta_chatgpt($instructions, $rubric, $student_work) {
    $api_key = get_option('muffin_api_key');
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
                    ['role' => 'system', 'content' => 'Eres un asistente para evaluar trabajos académicos.'],
                    ['role' => 'user', 'content' => "Instrucciones: $instructions\nRúbrica: $rubric\nTrabajo del alumno: $student_work"]
                ],
                'max_tokens' => 500,
            ],
        ]);

        $body = $response->getBody();
        $result = json_decode($body, true);
        return $result['choices'][0]['message']['content'];

    } catch (RequestException $e) {
        error_log($e->getMessage());
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
?>
