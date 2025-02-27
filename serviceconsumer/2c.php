<?php
$base_url = "https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/registro-de-establecimientos-farmaceuticos-de-castilla-y-leon/records";
$limit = 100;
$total_datos = 1605;
$offsets = range(0, $total_datos - 1, $limit);

// Funcion para obtener y filtrar datos
function obtenerDatosFiltradosPorProvincia($urls, $provincia) {
    $datos_filtrados = [];
    
    foreach ($urls as $url) {
        $respuesta = file_get_contents($url);
        $json = json_decode($respuesta, true);

        if (isset($json['results'])) {
            foreach ($json['results'] as $record) {
                // Comprobamos si el campo 'provincia' esta y si coincide
                if (isset($record['provincia']) && trim(strtolower($record['provincia'])) === strtolower($provincia)) {
                    $datos_filtrados[] = $record;
                }
            }
        }
    }
    return $datos_filtrados;
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['provincia'])) {
    $provincia_filtro = $_POST['provincia'];

    // Generar URLs
    $urls = [];
    foreach ($offsets as $offset) {
        $urls[] = "$base_url?limit=$limit&offset=$offset";
    }

    // Obtener datos filtrados
    $datos_filtrados = obtenerDatosFiltradosPorProvincia($urls, $provincia_filtro);

    // Mostrar resultados
    if (empty($datos_filtrados)) {
        echo "<p>No se encontraron datos para la provincia seleccionada: $provincia_filtro</p>";
    } else {
        echo "<h2>Resultados para la provincia: $provincia_filtro</h2>";
        echo "<div class='results-container'>";
        foreach ($datos_filtrados as $record) {
            echo "<div class='result-card'>";
            echo "<h3>" . htmlspecialchars($record['nombre_comercial']) . "</h3>";
            echo "<p><strong>Dirección:</strong> " . htmlspecialchars($record['calle']) . ", " . htmlspecialchars($record['numero']) . ", " . htmlspecialchars($record['municipio']) . "</p>";
            echo "<p><strong>Provincia:</strong> " . htmlspecialchars($record['provincia']) . "</p>";
            echo "<p><strong>Código Postal:</strong> " . htmlspecialchars($record['codigo_postal']) . "</p>";
            echo "<p><strong>Teléfono:</strong> " . (empty($record['telefono']) ? "No disponible" : htmlspecialchars($record['telefono'])) . "</p>";
            echo "</div>";
        }
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar por Provincia</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9e2f2; 
            color: #333;
            margin: 0;
            padding: 0;
        }
        h1, h2 {
            text-align: center;
            color: #d84f7b;
        }
        .form-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .form-container label {
            font-size: 18px;
            margin-bottom: 10px;
            color: #d84f7b; 
        }
        .form-container input {
            padding: 10px;
            width: 100%;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #f1a7c9; 
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-container button:hover {
            background-color: #d84f7b; 
        }
        .results-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }
        .result-card {
            background-color: white;
            padding: 20px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: left;
        }
        .result-card h3 {
            margin: 0;
            color: #d84f7b; 
            font-size: 18px;
        }
        .result-card p {
            margin: 8px 0;
            font-size: 16px;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Buscar Establecimientos Farmacéuticos</h1>
    
    <div class="form-container">
        <form method="POST" action="">
            <label for="provincia">Introduce la provincia:</label>
            <input type="text" id="provincia" name="provincia" required>
            <button type="submit">Buscar</button>
        </form>
    </div>
</body>
</html>

