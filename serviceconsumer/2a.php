<?php
// Funcion para obtener los datos desde las APIs
function obtenerDatosDesdeApi($url) {
    $response = file_get_contents($url);
    if ($response === FALSE) {
        die('Error al obtener los datos de la API.');
    }
    return json_decode($response, true);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// URLs de las dos APIs
$url1 = 'https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/puntos-de-recarga-del-vehiculo-electrico/records?limit=100&offset=0';
$url2 = 'https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/puntos-de-recarga-del-vehiculo-electrico/records?limit=100&offset=100';

// para obtener los datos de ambas APIs
$data1 = obtenerDatosDesdeApi($url1);
$data2 = obtenerDatosDesdeApi($url2);




// Unir ambos conjuntos de datos
$puntosRecarga = array_merge($data1['results'], $data2['results']);

// Obtener la provincia desde la solicitud GET
$provincia = isset($_GET['provincia']) ? $_GET['provincia'] : '';

// Filtrar los puntos de recarga por provincia
$puntosFiltrados = array_filter($puntosRecarga, function($punto) use ($provincia) {
    if (empty($provincia)) return true; // Si no hay provincia, no filtramos nada
    $direccion = strtolower($punto['direccion']);
    $provincia = strtolower($provincia);
    
    // Buscar la provincia en la direccion
    if (preg_match("/\((.*?)\)/", $direccion, $matches)) {
        $provinciaEnDireccion = strtolower($matches[1]);
        return stripos($provinciaEnDireccion, $provincia) !== false;
    }
    return false;
});

// Mostrar los resultados en una tabla HTML
if (empty($puntosFiltrados)) {
    echo "<p>No se encontraron resultados para \"" . htmlspecialchars($provincia) . "\".</p>";
} else {
    echo "<table class='result-table'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Operador</th>
                    <th>Tipos de Cargador</th>
                    <th>Coordenadas</th>
                </tr>
            </thead>
            <tbody>";

    foreach ($puntosFiltrados as $punto) {
        echo "<tr>
                <td>" . htmlspecialchars($punto['nombre']) . "</td>
                <td>" . htmlspecialchars($punto['direccion']) . "</td>
                <td>" . htmlspecialchars($punto['operador']) . "</td>
                <td>" . implode(", ", $punto['tipo']) . "</td>
                <td>(Lat: " . htmlspecialchars($punto['dd']['lat']) . ", Lon: " . htmlspecialchars($punto['dd']['lon']) . ")</td>
              </tr>";
    }

    echo "</tbody></table>";
}
?>
