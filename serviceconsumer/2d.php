<?php
$base_url = "https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/tasa-mortalidad-covid-por-zonas-basicas-de-salud/records";
$limit = 100;  // Limitar a 100 registros por solicitud
$offset = 0;   // Empezar desde el principio
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasa Mortalidad COVID por Zonas Básicas de Salud</title>
    <style>
       
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fc;
    margin: 0;
    padding: 0;
    color: #333;
}

h1 {
    text-align: center;
    margin-top: 20px;
    color: #FF6FB1;
    font-size: 2.5em;
    word-wrap: break-word;
}


#main-container {
    width: 90%;
    margin: 20px auto;
    max-width: 1200px;
    word-wrap: break-word;
}


#provinceForm {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    flex-wrap: wrap; 
}

#provinceForm label {
    font-size: 1.2em;
    margin-right: 10px;
    align-self: center;
}

#provinceForm select {
    padding: 10px;
    font-size: 1em;
    border-radius: 5px;
    border: 1px solid #ddd;
    min-width: 200px; 
    box-sizing: border-box;
}

#provinceForm button {
    background-color: #FF6FB1;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    margin-left: 10px;
    min-width: 100px; 
}

#provinceForm button:hover {
    background-color: #FF3B85;
}


#results {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
    overflow: hidden;
}

.result-card {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
    word-wrap: break-word;
    max-height: 400px; 
    overflow: auto; 
}

.result-card:hover {
    transform: translateY(-10px);
}

.result-card h3 {
    color: #FF6FB1;
    font-size: 1.5em;
    word-wrap: break-word;
}

.result-card p {
    font-size: 1em;
    color: #555;
    word-wrap: break-word;
}

.result-card strong {
    color: #333;
}

/* Botón cargar más */
#loadMore {
    text-align: center;
    margin-top: 30px;
}

#loadMoreButton {
    background-color: #FF6FB1;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1.1em;
    min-width: 150px; 
}

#loadMoreButton:hover {
    background-color: #FF3B85;
}


#loading {
    text-align: center;
    margin-top: 20px;
    display: none;
}

#loading img {
    width: 50px;
}

    </style>
</head>
<body>

    <div id="main-container">
        <h1>Tasa Mortalidad COVID por Zonas Básicas de Salud</h1>

        <!-- Formulario de seleccion de provincia -->
        <form id="provinceForm">
            <label for="province">Selecciona una provincia:</label>
            <select id="province" name="province">
                <option value="Ávila">Ávila</option>
                <option value="Burgos">Burgos</option>
                <option value="León">León</option>
                <option value="Palencia">Palencia</option>
                <option value="Salamanca">Salamanca</option>
                <option value="Segovia">Segovia</option>
                <option value="Soria">Soria</option>
                <option value="Valladolid">Valladolid</option>
                <option value="Zamora">Zamora</option>
            </select>
            <button type="submit">Buscar</button>
        </form>

        <div id="loading">
            <img src="https://www.w3schools.com/images/loader.gif" alt="Cargando..."> Cargando datos...
        </div>

        <div id="results"></div>

        <!-- Boton para cargar mas resultados -->
        <div id="loadMore" style="display: none;">
            <button id="loadMoreButton">Cargar más</button>
        </div>
    </div>

    <script>
        let currentOffset = 0;
        let totalResults = [];
        let provinceSelected = '';

        // Funcion para obtener datos desde la API
        async function fetchData(offset) {
            const url = `https://analisis.datosabiertos.jcyl.es/api/explore/v2.1/catalog/datasets/tasa-mortalidad-covid-por-zonas-basicas-de-salud/records?limit=100&offset=${offset}`;
            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data && data.results) {
                    return data.results;
                } else {
                    console.error("No se obtuvieron registros.");
                    return [];
                }
            } catch (error) {
                console.error("Error al obtener los datos:", error);
                return [];
            }
        }

        // Funcion para mostrar los resultados en bloques organizados
        function displayResults(data) {
            let resultsHtml = "";
            if (data.length > 0) {
                data.forEach(record => {
                    resultsHtml += `
                        <div class="result-card">
                            <h3>${record.centro}</h3>
                            <p><strong>Fecha:</strong> ${record.fecha}</p>
                            <p><strong>Municipio:</strong> ${record.municipio}</p>
                            <p><strong>Fallecidos:</strong> ${record.fallecidos}</p>
                            <p><strong>Tasa x 100:</strong> ${record.tasax100}</p>
                            <p><strong>Provincia:</strong> ${record.provincia}</p>
                        </div>
                    `;
                });
            } else {
                resultsHtml = "<p>No se encontraron datos.</p>";
            }
            document.getElementById("results").innerHTML = resultsHtml;
            document.getElementById("loading").style.display = "none"; // Ocultar el cargando
            document.getElementById("loadMore").style.display = "block"; 
        }

        // Funcion para filtrar por provincia
        async function filterByProvince(province) {
            provinceSelected = province;
            currentOffset = 0; // Reiniciar offset al filtrar por nueva provincia
            totalResults = [];

            document.getElementById("loading").style.display = "block"; // Mostrar cargando
            const data = await fetchData(currentOffset);
            const filteredData = data.filter(record => record.provincia.toLowerCase() === province.toLowerCase());

            totalResults = filteredData;
            displayResults(filteredData);
        }

        // Funcion para cargar mas resultados
        async function loadMoreResults() {
            currentOffset += 100; // Aumentar offset
            const data = await fetchData(currentOffset);
            const filteredData = data.filter(record => record.provincia.toLowerCase() === provinceSelected.toLowerCase());

            totalResults = [...totalResults, ...filteredData]; // Añadir mas registros
            displayResults(totalResults);
        }

        // Evento para manejar el formulario de seleccion de provincia
        document.getElementById("provinceForm").addEventListener("submit", function(event) {
            event.preventDefault();
            const province = document.getElementById("province").value;
            filterByProvince(province).catch(error => {
                document.getElementById("loading").innerHTML = "Error al cargar los datos.";
                console.error("Error al obtener los datos:", error);
            });
        });

        // Evento para cargar mas resultados
        document.getElementById("loadMoreButton").addEventListener("click", function() {
            loadMoreResults().catch(error => {
                document.getElementById("loading").innerHTML = "Error al cargar más datos.";
                console.error("Error al cargar más datos:", error);
            });
        });
    </script>

</body>
</html>
