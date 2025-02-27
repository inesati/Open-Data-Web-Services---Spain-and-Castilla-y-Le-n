<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convocatorias de Empleo Público</title>
    <link rel="stylesheet" href="2b.css">
</head>
<body>
    <div class="container">
        <h1>Convocatorias de Empleo Público</h1>
        <?php
        // URLs de las APIs
        $urls = [
            'https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/convocatorias-de-empleo-publico/records?limit=100&offset=0',
            'https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/convocatorias-de-empleo-publico/records?limit=100&offset=100',
            'https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/convocatorias-de-empleo-publico/records?limit=100&offset=200',
        ];

        // Verificar si se enviaron las fechas
        if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
            $fecha_inicio_busqueda = $_POST['fecha_inicio'];
            $fecha_fin_busqueda = $_POST['fecha_fin'];

            // Funcion para realizar una solicitud a la API
            function obtenerDatosAPI($url) {
                $opciones = [
                    "http" => [
                        "method" => "GET",
                        "header" => "Content-Type: application/json\r\n",
                    ]
                ];
                $contexto = stream_context_create($opciones);
                $respuesta = file_get_contents($url, false, $contexto);

                if ($respuesta === FALSE) {
                    return [];
                }

                $datos = json_decode($respuesta, true);
                return $datos['results'] ?? [];
            }

            // Funcion para filtrar las convocatorias por fecha
            function filtrarConvocatorias($convocatorias, $fecha_inicio, $fecha_fin) {
                $convocatorias_filtradas = [];
                foreach ($convocatorias as $convocatoria) {
                    $inicio = $convocatoria['fecha_de_inicio'] ?? '';
                    $fin = $convocatoria['fechafinalizacion'] ?? '';
                    if ($inicio >= $fecha_inicio && $fin <= $fecha_fin) {
                        $convocatorias_filtradas[] = $convocatoria;
                    }
                }
                return $convocatorias_filtradas;
            }

            // Obtener y combinar los datos de las APIs
            $convocatorias = [];
            foreach ($urls as $url) {
                $convocatorias = array_merge($convocatorias, obtenerDatosAPI($url));
            }

            // Filtrar las convocatorias
            $convocatorias_filtradas = filtrarConvocatorias($convocatorias, $fecha_inicio_busqueda, $fecha_fin_busqueda);

            // Mostrar los resultados
            if (count($convocatorias_filtradas) > 0) {
                echo '<div class="results">';
                foreach ($convocatorias_filtradas as $convocatoria) {
                    echo '<div class="result">';
                    echo "<h2>" . ($convocatoria['titulo'] ?? 'Sin título') . "</h2>";
                    echo "<p><strong>Fecha de inicio:</strong> " . ($convocatoria['fecha_de_inicio'] ?? 'No disponible') . "</p>";
                    echo "<p><strong>Fecha de finalización:</strong> " . ($convocatoria['fechafinalizacion'] ?? 'No disponible') . "</p>";
                    echo "<p><strong>Organismo gestor:</strong> " . ($convocatoria['organismo_gestor'] ?? 'No disponible') . "</p>";
                    echo "<p><strong>Número de plazas:</strong> " . ($convocatoria['numeroplazas'] ?? 'No disponible') . "</p>";
                    echo '</div>';
                    echo '<hr>';
                }
                echo '</div>';
            } else {
                echo '<p class="error">No se encontraron convocatorias en el rango de fechas especificado.</p>';
            }
        } else {
            // Mostrar formulario si no se han enviado las fechas
            echo '<form method="POST" action="" class="search-form">
                    <label for="fecha_inicio">Fecha de inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" required>
                    <label for="fecha_fin">Fecha de fin:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" required>
                    <button type="submit">Buscar</button>
                </form>';
        }
        ?>
    </div>
</body>
</html>
