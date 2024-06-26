<?php
// public/form-handler.php

function agregar_mensaje_pie_de_pagina() {
    $instructions = "Escribe un ensayo super corto, de menos de 40 palabras, sobre la importancia de la inteligencia artificial en la educación.";
    $rubric = "Criterio 1: Comprensión del tema (20 puntos), Criterio 2: Argumentación (20 puntos), Criterio 3: Originalidad (10 puntos)";
    $student_work = "La Inteligencia Artificial en la educación ofrece un potencial significativo. Puede abordar desafíos, innovar prácticas de enseñanza y acelerar el progreso. Sin embargo, debemos asegurar que su aplicación sea inclusiva y equitativa, priorizando el bienestar humano.";
    
    $respuesta = obtener_respuesta_chatgpt($instructions, $rubric, $student_work);
    echo '<p style="text-align: center;">' . esc_html($respuesta) . '</p>';
}

?>
