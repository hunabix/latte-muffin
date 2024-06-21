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

// Función para añadir el mensaje al pie de página
function agregar_mensaje_pie_de_pagina() {
    echo '<p style="text-align: center;">Este esta es la base de mi plugin MUFFIN en WordPress</p>';
}

// Hook para añadir la función al pie de página
add_action('wp_footer', 'agregar_mensaje_pie_de_pagina');
