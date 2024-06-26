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

// Cargar archivos necesarios
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/admin/settings-page.php';
require __DIR__ . '/admin/settings.php';
require __DIR__ . '/includes/api-functions.php';
require __DIR__ . '/public/form-handler.php';
//require __DIR__ . '/public/form-page.php';
//require __DIR__ . '/includes/error-handling.php';

// Hooks
add_action('admin_menu', 'muffin_menu');
add_action('admin_init', 'muffin_register_settings');
add_action('wp_footer', 'agregar_mensaje_pie_de_pagina');
//add_action('wp_ajax_obtener_respuesta_chatgpt', 'ajax_obtener_respuesta_chatgpt');
//add_action('wp_ajax_nopriv_obtener_respuesta_chatgpt', 'ajax_obtener_respuesta_chatgpt');

?>