<?php
// public/form-page.php

function mostrar_formulario_evaluacion($content) {
    // Verifica si la página actual es la página de evaluación seleccionada en la configuración
    if (is_page(get_option('muffin_evaluation_page'))) {
        ob_start();
        ?>
        <div style="text-align: center; margin-top: 20px;">
            <h2>Formulario de Evaluación con IA</h2>
            <form id="evaluacion-form">
                <textarea id="instructions" placeholder="Instrucciones del trabajo" required></textarea><br>
                <textarea id="rubric" placeholder="Rúbrica" required></textarea><br>
                <textarea id="student_work" placeholder="Trabajo del alumno" required></textarea><br>
                <button type="submit">Enviar</button>
            </form>
            <div id="chatgpt-response" style="text-align: center; margin-top: 20px;"></div>
        </div>
        <script type="text/javascript">
            document.getElementById('evaluacion-form').addEventListener('submit', function(e) {
                e.preventDefault();
                var instructions = document.getElementById('instructions').value;
                var rubric = document.getElementById('rubric').value;
                var student_work = document.getElementById('student_work').value;
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('chatgpt-response').innerHTML = this.responseText;
                    }
                };
                xhttp.open("POST", "<?php echo admin_url('admin-ajax.php'); ?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("action=obtener_respuesta_chatgpt&instructions=" + encodeURIComponent(instructions) + "&rubric=" + encodeURIComponent(rubric) + "&student_work=" + encodeURIComponent(student_work));
            });
        </script>
        <?php
        $formulario = ob_get_clean();
        $content .= $formulario;
    }
    return $content;
}
add_filter('the_content', 'mostrar_formulario_evaluacion');
?>
