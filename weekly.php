<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Previsión Semanal</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Enlace a la hoja de estilos -->
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

    <h1>Previsión Semanal para <?php echo $cityName; ?></h1>

    <!-- Contenedor del gráfico -->
    <div class="forecast-container">
        <h2>Temperaturas y Lluvia Semanal</h2>
        <canvas id="weeklyChart"></canvas> <!-- Lienzo para el gráfico -->
        <div id="weatherIcons">
            <!-- Aquí se mostrarán los iconos del clima -->
        </div>
    </div>

    <!-- Botón para volver a la página principal -->
    <a href="index.php" class="btn">Inicio</a>

    <?php
    // Clave API para OpenWeatherMap
    $apiKey = '544a4030985e7b50b46df6db73bb2bba';

    // Construcción de la URL para obtener la previsión del clima
    $url = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=es";

    // Obtener la respuesta de la API
    $response = @file_get_contents($url);

    // Verificar si hubo un error en la solicitud
    if ($response === FALSE) {
        echo "<p class='error'>Error al obtener la previsión semanal.</p>";
        exit;
    }

    // Decodificar la respuesta JSON
    $data = json_decode($response, true);

    // Agrupar datos por día
    $dailyData = [];
    foreach ($data['list'] as $forecast) {
        $date = date('Y-m-d', $forecast['dt']); // Convertir timestamp en fecha

        // Si aún no hemos agregado este día, inicializarlo
        if (!isset($dailyData[$date])) {
            $dailyData[$date] = [
                'min_temp' => $forecast['main']['temp_min'], // Temperatura mínima
                'max_temp' => $forecast['main']['temp_max'], // Temperatura máxima
                'rain' => $forecast['rain']['3h'] ?? 0, // Precipitación en mm (0 si no hay datos)
                'icon' => $forecast['weather'][0]['icon'], // Icono del clima
                'date' => $date
            ];
        } else {
            // Actualizar la temperatura mínima si es más baja
            if ($forecast['main']['temp_min'] < $dailyData[$date]['min_temp']) {
                $dailyData[$date]['min_temp'] = $forecast['main']['temp_min'];
            }
            // Actualizar la temperatura máxima si es más alta
            if ($forecast['main']['temp_max'] > $dailyData[$date]['max_temp']) {
                $dailyData[$date]['max_temp'] = $forecast['main']['temp_max'];
            }
            // Sumar la precipitación si hay datos
            if (isset($forecast['rain']['3h'])) {
                $dailyData[$date]['rain'] += $forecast['rain']['3h'];
            }
        }
    }

    // Preparar datos para el gráfico
    $labels = []; // Etiquetas del eje X (días)
    $temperatures = []; // Datos de temperatura
    $rain = []; // Datos de precipitación
    $icons = []; // Iconos del clima

    foreach ($dailyData as $day) {
        $labels[] = date('D, M j', strtotime($day['date'])); // Formato: Mar, Sept 2
        $temperatures[] = [
            'min' => $day['min_temp'],
            'max' => $day['max_temp']
        ];
        $rain[] = $day['rain']; // Precipitación total del día en mm
        $icons[] = $day['icon']; // Icono del clima
    }
    ?>

    <script>
        // Convertir los datos de PHP a formato JavaScript
        const labels = <?php echo json_encode($labels); ?>; // Días de la semana
        const temperatures = <?php echo json_encode($temperatures); ?>; // Temperaturas mín y máx
        const rain = <?php echo json_encode($rain); ?>; // Precipitación total
        const icons = <?php echo json_encode($icons); ?>; // Iconos del clima

        // Configuración del gráfico con Chart.js
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        const weeklyChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico de barras
            data: {
                labels: labels, // Eje X: Días
                datasets: [
                    {
                        label: 'Temperatura Mínima (°C)',
                        data: temperatures.map(temp => temp.min), // Extraer temperaturas mínimas
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color azul claro
                        borderColor: 'rgba(54, 162, 235, 1)', // Borde azul oscuro
                        borderWidth: 1,
                    },
                    {
                        label: 'Temperatura Máxima (°C)',
                        data: temperatures.map(temp => temp.max), // Extraer temperaturas máximas
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Color rojo claro
                        borderColor: 'rgba(255, 99, 132, 1)', // Borde rojo oscuro
                        borderWidth: 1,
                    },
                    {
                        label: 'Lluvia (mm)',
                        data: rain, // Datos de precipitación
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color verde claro
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        type: 'line', // Mostrar como línea
                        yAxisID: 'rainAxis', // Eje Y secundario para la lluvia
                    }
                ]
            },
            options: {
                responsive: true, // Adaptar a diferentes pantallas
                plugins: {
                    title: {
                        display: true,
                        text: 'Temperaturas y Lluvia Semanal' // Título del gráfico
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Día' // Etiqueta del eje X
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Temperatura (°C)' // Etiqueta del eje Y principal
                        }
                    },
                    rainAxis: {
                        position: 'right', // Ubicar el eje Y de lluvia a la derecha
                        title: {
                            display: true,
                            text: 'Lluvia (mm)' // Etiqueta del eje de precipitación
                        },
                        grid: {
                            display: false, // Ocultar líneas de cuadrícula en este eje
                        }
                    }
                }
            }
        });

        // Mostrar iconos de clima
        const iconsContainer = document.getElementById('weatherIcons');
        labels.forEach((day, index) => {
            const iconDiv = document.createElement('div');
            iconDiv.className = 'weather-icon';
            
            const img = document.createElement('img');
            img.src = `http://openweathermap.org/img/wn/${icons[index]}@2x.png`;
            img.alt = 'Icono del clima';
            
            const daySpan = document.createElement('span');
            daySpan.textContent = day;
            
            iconDiv.appendChild(img);
            iconDiv.appendChild(daySpan);
            iconsContainer.appendChild(iconDiv);
        });
    </script>

    <style>
        /* Estilos para los iconos del clima */
        #weatherIcons {
            display: flex;
            justify-content: space-around; /* Espaciado uniforme entre iconos */
            margin-top: 20px;
        }
        .weather-icon {
            text-align: center; /* Centrar el icono */
        }
        .weather-icon img {
            width: 50px; /* Tamaño del icono */
            height: 50px;
        }
    </style>
</body>
</html>
