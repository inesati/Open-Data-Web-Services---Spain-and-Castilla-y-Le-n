
<?php
// Comprobamos si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores seleccionados por el usuario
    $año = $_POST['año'];
    $province = strtolower(trim($_POST['provincia']));

    // URL de la API
    $api_url = "https://servicios.ine.es/wstempus/jsCache/es/DATOS_TABLA/3995?tip=AM";

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Inicializar cURL
    $ch = curl_init();

    // Configuracion de cURL
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar cURL
    $response = curl_exec($ch);

    // Verificar si hubo un error
    if(curl_errno($ch)) {
        echo 'Error al obtener los datos: ' . curl_error($ch);
    }

    // Cerrar cURL
    curl_close($ch);

    // Decodificar la respuesta JSON
    $data = json_decode($response, true);

    if ($data) {
        $resultados = []; // Inicializar el arreglo de resultados

        // Filtramos los datos
        foreach ($data as $dato) {
            $provincia = $dato['MetaData'][2]['Nombre']; //Nombre de la provincia
            $sector = $dato['MetaData'][4]['Nombre']; //Nombre del sector económico
            //Extraemos los datos de cada provincia (año, trimestre y valor)
            foreach ($dato['Data'] as $mini_dato) {
                $anyo = $mini_dato['Anyo'];
                $trimestre = $mini_dato['T3_Periodo'];
                $valor = $mini_dato['Valor'];

                // Filtrar por año y provincia 
                if (($año == null || $año == $anyo) && ($province == null || stripos($provincia, $province) !== false)) {
                    $resultados[] = [
                        'provincia' => $provincia,
                        'sector' => $sector,
                        'anyo' => $anyo,
                        'trimestre' => $trimestre,
                        'valor' => $valor
                    ];
                }
            }
        }

        // Mostrar los datos filtrados
        if (count($resultados) > 0) {
            echo "<h2>Datos Filtrados:</h2>";
            echo "<table><tr><th>Sector</th><th>Valor (%)</th><th>Año</th><th>Trimestre</th></tr>";
            foreach ($resultados as $dato) {
                echo "<tr><td>" . $dato['sector'] . "</td><td>" . $dato['valor'] . "%</td><td>" . $dato['anyo'] . "</td><td>" . $dato['trimestre'] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='error'>No hay datos disponibles para la selección de año y provincia.</p>";
        }
    } else {
        echo "<p class='error'>Hubo un error al procesar los datos.</p>";
    }
}
?>

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8e2f7; 
        color: #333;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 70%;
        margin: 50px auto;
        padding: 30px;
        background-color: #fff;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
    }
    h1 {
        font-size: 2.2em;
        color: #ff66b2; 
        margin-bottom: 30px;
        border-bottom: 3px solid #ff66b2;
        padding-bottom: 10px;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: center;
        margin-top: 20px;
    }
    label {
        font-weight: bold;
        font-size: 1.1em;
        color: #333;
    }
    select, input[type="text"], button {
        width: 250px;
        padding: 12px 20px;
        font-size: 1em;
        border: 2px solid #ffb3d9; 
        border-radius: 5px;
        box-sizing: border-box;
        background-color: #fff;
        color: #333;
        transition: all 0.3s ease-in-out;
    }
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #f9f9f9;
        background-image: url('https://www.gstatic.com/ui/v1/menu_arrow_down.png');
        background-position: right 10px center;
        background-repeat: no-repeat;
        padding-right: 40px;
    }
    select:focus, input[type="text"]:focus {
        border-color: #ff66b2;
        outline: none;
        box-shadow: 0 0 5px rgba(255, 102, 178, 0.4);
    }
    button {
        background-color: #ff66b2;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 1.1em;
        font-weight: bold;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #ff3399; 
    }
    table {
        width: 100%;
        margin-top: 30px;
        border-collapse: collapse;
    }
    th, td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #ff66b2;
        color: white;
        font-size: 1.1em;
    }
    td {
        font-size: 1.1em;
    }
    tr:nth-child(even) {
        background-color: #ffe6f2;
    }
    .error {
        color: red;
        font-weight: bold;
        font-size: 1.1em;
        margin-top: 20px;
    }
</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar Datos de Sectores</title>
</head>
<body>
    <div class="container">
        <h1>Filtrar Datos de Sectores</h1>

        <form action="1a.php" method="POST">
            <label for="año">Selecciona el Año:</label>
            <select name="año" id="año">
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
            </select>

            <label for="provincia">Selecciona la Ciudad o Provincia:</label>
            <input type="text" name="provincia" id="provincia" placeholder="Ejemplo: Albacete, Madrid..." required>

            <button type="submit">Filtrar</button>
        </form>
    </div>
</body>
</html>
