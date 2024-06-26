<?php
// public/form-handler.php

function ajax_obtener_respuesta_chatgpt() {
    if (isset($_POST['instructions']) && isset($_POST['rubric']) && isset($_POST['student_work'])) {
        $instructions = sanitize_text_field($_POST['instructions']);
        $rubric = sanitize_text_field($_POST['rubric']);
        $student_work = sanitize_text_field($_POST['student_work']);
        $respuesta = obtener_respuesta_chatgpt($instructions, $rubric, $student_work);
        echo esc_html($respuesta);
    }
    wp_die();
}

?>
