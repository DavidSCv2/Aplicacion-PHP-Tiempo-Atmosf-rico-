<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Previsión por Horas</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Enlace a la hoja de estilos CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Librería Chart.js para gráficos -->

    <style>
        /* Estilos para el botón de inicio */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php
    session_start(); // Iniciar sesión para acceder a los datos guardados
    
    // Verificar si existen los datos de latitud y longitud en la sesión
    if (!isset($_SESSION['lat']) || !isset($_SESSION['lon'])) {
        echo "<p class='error'>No se ha seleccionado ninguna ciudad. Por favor, vuelve al inicio y selecciona una ciudad.</p>";
        echo "<a href='index.php' class='btn'>Volver al Inicio</a>";
        exit;
    }
    
    // Obtener la información de la ciudad desde la sesión
    $lat = $_SESSION['lat'];
    $lon = $_SESSION['lon'];
    $cityName = isset($_SESSION['city_name']) ? $_SESSION['city_name'] : "tu ubicación";
    ?>

    <h1>Previsión por Horas para <?php echo $cityName; ?></h1>

    <!-- Contenedor del gráfico -->
    <div class="forecast-container">
        <h2>Temperaturas y Lluvia por Horas</h2>
        <canvas id="hourlyChart"></canvas> <!-- Lienzo donde se dibujará el gráfico -->
    </div>

    <!-- Botón para volver a la página principal -->
    <a href="index.php" class="btn">Inicio</a>

    <?php
    // Clave API para OpenWeatherMap
    $apiKey = '544a4030985e7b50b46df6db73bb2bba';

    // Construcción de la URL para obtener la previsión meteorológica por horas
    $hourlyUrl = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=es";

    // Obtener los datos desde la API
    $hourlyResponse = @file_get_contents($hourlyUrl);

    // Verificar si la solicitud fue exitosa
    if ($hourlyResponse === FALSE) {
        echo "<p class='error'>Error al obtener la previsión por horas.</p>";
        exit;
    }

    // Decodificar la respuesta JSON
    $hourlyData = json_decode($hourlyResponse, true);

    // Inicializar arrays para almacenar los datos del gráfico
    $labels = []; // Etiquetas del eje X (horas)
    $temperatures = []; // Datos de temperatura
    $rainfall = []; // Datos de lluvia

    // Recorrer los datos horarios y extraer la información necesaria
    foreach ($hourlyData['list'] as $hour) {
        $labels[] = date('H:i', $hour['dt']); // Convertir el timestamp en formato HH:MM
        $temperatures[] = $hour['main']['temp']; // Guardar la temperatura en °C
        $rainfall[] = isset($hour['rain']['3h']) ? $hour['rain']['3h'] : 0; // Cantidad de lluvia en mm (0 si no hay datos)
    }
    ?>

    <script>
        // Convertir los datos de PHP a formato JavaScript
        const labels = <?php echo json_encode($labels); ?>;
        const temperatures = <?php echo json_encode($temperatures); ?>;
        const rainfall = <?php echo json_encode($rainfall); ?>;

        // Configuración del gráfico usando Chart.js
        const ctx = document.getElementById('hourlyChart').getContext('2d');
        const hourlyChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico principal
            data: {
                labels: labels, // Etiquetas del eje X (horas)
                datasets: [
                    {
                        label: 'Temperatura (°C)',
                        type: 'line', // La temperatura se muestra como una línea
                        data: temperatures,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color del área bajo la línea
                        borderColor: 'rgba(54, 162, 235, 1)', // Color de la línea
                        borderWidth: 2,
                        fill: true,
                        yAxisID: 'y', // Asociado al eje Y de temperatura
                    },
                    {
                        label: 'Lluvia (mm)',
                        type: 'bar', // La lluvia se muestra como un gráfico de barras
                        data: rainfall,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)', // Color de las barras
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        yAxisID: 'y1', // Asociado al segundo eje Y de lluvia
                    }
                ]
            },
            options: {
                responsive: true, // El gráfico se adapta al tamaño de la pantalla
                plugins: {
                    title: {
                        display: true,
                        text: 'Temperaturas y Lluvia por Horas' // Título del gráfico
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Hora' // Etiqueta del eje X
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Temperatura (°C)' // Etiqueta del primer eje Y
                        },
                        position: 'left', // Se sitúa a la izquierda
                    },
                    y1: {
                        title: {
                            display: true,
                            text: 'Lluvia (mm)' // Etiqueta del segundo eje Y
                        },
                        position: 'right', // Se sitúa a la derecha
                        grid: {
                            drawOnChartArea: false // Evita que las líneas del eje Y1 interfieran con Y
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
