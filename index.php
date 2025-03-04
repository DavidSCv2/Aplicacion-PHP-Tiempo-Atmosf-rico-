<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Información del Tiempo</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Enlace a la hoja de estilos CSS -->
</head>
<body>
    <h1>Consulta del Tiempo</h1>

    <!-- Formulario para ingresar el nombre de la ciudad -->
    <form action="index.php" method="GET">
        <label for="city">Ciudad:</label>
        <input type="text" id="city" name="city" required>
        <button type="submit">Buscar</button>
    </form>

    <?php
    // Verifica si se ha enviado el nombre de la ciudad
    if (isset($_GET['city'])) {
        $apiKey = ''; // Clave API para acceder a OpenWeatherMap (reemplázala con la tuya)
        $city = urlencode($_GET['city']); // Codifica el nombre de la ciudad para la URL
        $geocodeUrl = "http://api.openweathermap.org/geo/1.0/direct?q={$city}&limit=1&appid={$apiKey}";

        // Obtener la latitud y longitud de la ciudad a partir del nombre ingresado
        $response = @file_get_contents($geocodeUrl);

        // Verificar si la solicitud a la API fue exitosa
        if ($response === FALSE) {
            echo "<p class='error'>Error al conectar con la API de geolocalización.</p>";
            exit;
        }

        $data = json_decode($response, true); // Decodificar la respuesta JSON

        // Si no se encontraron datos para la ciudad ingresada, mostrar un mensaje de error
        if (empty($data)) {
            echo "<p class='error'>Ciudad no encontrada.</p>";
            exit;
        }

        $lat = $data[0]['lat']; // Extraer la latitud
        $lon = $data[0]['lon']; // Extraer la longitud

        // Construcción de la URL para obtener el tiempo actual con la latitud y longitud obtenidas
        $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=es";
        $weatherResponse = @file_get_contents($weatherUrl);

        // Verificar si la solicitud para obtener el clima fue exitosa
        if ($weatherResponse === FALSE) {
            echo "<p class='error'>Error al obtener el tiempo actual.</p>";
            exit;
        }

        $weatherData = json_decode($weatherResponse, true); // Decodificar la respuesta JSON del clima

        // Mostrar la información meteorológica de la ciudad ingresada
        echo "<div class='weather-info'>";
        echo "<h2>Tiempo en {$data[0]['name']}</h2>";
        echo "<p>Temperatura: {$weatherData['main']['temp']}°C</p>";
        echo "<p>Condición: {$weatherData['weather'][0]['description']}</p>";
        echo "<p>Humedad: {$weatherData['main']['humidity']}%</p>";
        echo "<p>Viento: {$weatherData['wind']['speed']} m/s</p>";

        // Enlaces para ver más detalles sobre el clima por horas y semanalmente
        echo "<div class='nav-links'>";
        echo "<a href='hourly.php?lat={$lat}&lon={$lon}'>Previsión por Horas</a>";
        echo "<a href='weekly.php?lat={$lat}&lon={$lon}'>Previsión Semanal</a>";
        echo "</div>";
        echo "</div>";
    }
    ?>
</body>
</html>
