# Aplicación PHP Tiempo Atmosférico

## Índice

1. [Introducción](#introducción)  
2. [Requisitos Previos](#requisitos-previos)  
3. [Infraestructura y Direccionamiento IP](#infraestructura-y-direccionamiento-ip)
4. [Estructura](#estructura)  
5. [Configuración](#configuración)
    - [Configuración del index](#configuración-del-index)  
    - [Configuración del hourly](#configuración-del-hourly)  
    - [Configuración del Servidor NFS](#configuración-del-servidor-nfs)  
    - [Configuración de los Servidores Web](#configuración-de-los-servidores-web)
6. [Despliegue](#despliegue)  
7. [Conclusión](#conclusión)


## Introducción
Este proyecto es una aplicación web en **PHP y JavaScript** que permite consultar el clima de una ciudad específica mediante la API de **OpenWeatherMap**. Incluye información del tiempo actual, previsión por horas y previsión semanal con gráficos.

## Requisitos Previos
Para ejecutar esta aplicación, necesitarás:

- Servidor **Apache** con **PHP** (>=7.4 recomendado)
- Módulos habilitados de php
- Acceso a Internet para consumir la API de OpenWeatherMap
- Clave API de [OpenWeatherMap](https://openweathermap.org/api)
- Opcional, un dominio para mejor acceso a la aplicación para el cual necesitaremos una ip elástica

## Infraestructura y direccionamiento IP 
- Se utilizará una estructura de archivos en `/var/www/html/tiempo`
- Dirección local de acceso: `<IP-SERVIDOR>` o el nombre de nuestro dominio.
- Se requiere modificar la configuración de **Apache** para servir la carpeta correctamente.

## Estructura
├── /tiempo      

  ├── index.php              # Página principal encargada de permitir consultar a los usuarios  el clima de una ciudad específica.

  ├── hourly.php              # Muestra la previsión del clima en la ciudad especificada.

  ├── weekly.php              # Muestra la previsión meteorológica semanal para la ciudad especificada.

  ├── /css              

  ├── styles.css              # Define los estilos visuales del proyecto.

  ## Configuración

  ### Configuración del index
  El archivo `index.php` es la página principal de la aplicación y permite a los usuarios consultar el clima de cualquier ciudad utilizando la API de OpenWeatherMap. Al cargar la página, se muestra un formulario en el que el usuario puede ingresar el nombre de una ciudad y enviarlo mediante el botón "Buscar". Cuando se envía el formulario, el script obtiene las coordenadas (latitud y longitud) de la ciudad ingresada a través de una solicitud a la API de geolocalización.

Si la ciudad existe y se obtienen las coordenadas correctamente, se realiza una segunda solicitud a la API para obtener el clima actual. Con estos datos, la página muestra información como la temperatura, la condición meteorológica, la humedad y la velocidad del viento. Además, se generan enlaces dinámicos a las páginas de previsión por horas y semanal, enviando las coordenadas en la URL para que puedan ser utilizadas en esos archivos.

El documento también maneja errores en caso de que la API no responda o la ciudad ingresada no exista, mostrando mensajes adecuados al usuario. Finalmente, se pueden realizar mejoras como un mejor manejo de errores, la inclusión de íconos del clima para hacerlo más visual y la optimización de las peticiones a la API para mejorar el rendimiento de la aplicación.

  ![head](https://github.com/user-attachments/assets/085fb026-53e6-4a52-b93a-2a7cc09d266b) 
  
  - **Define el documento HTML** con la etiqueta `<!DOCTYPE html>`.
  - Especifica que el idioma es español con `lang="es"`.
  - Usa `meta charset="UTF-8"` para permitir caracteres especiales (acentos, ñ, etc.).
  - Establece el título del sitio como **"Información del Tiempo"**.
  - Enlaza un archivo CSS (`styles.css`) para aplicar estilos a la página.

  ![formulario](https://github.com/user-attachments/assets/f0ce1183-67b5-4847-85d5-f6a3cc6124e3)
  - **Crea un formulario** donde el usuario puede ingresar el nombre de una ciudad.
  - El formulario usa el método **GET** y envía los datos a `weather.php`.
  - Contiene un **campo de texto** (`<input type="text">`) para ingresar la ciudad.
  - Se incluye un botón **"Buscar"** para enviar el formulario.

  ![isset](https://github.com/user-attachments/assets/d67408b5-0b03-4d76-86c3-4c2421786db3)
  - **Verifica si se ha enviado una ciudad** (`isset($_GET['city'])`).
  - Define la **clave API** de OpenWeatherMap para acceder a los datos.
  - Convierte el nombre de la ciudad en un formato seguro (`urlencode()`).
  - Construye la **URL de geolocalización** (`geocodeUrl`), que obtiene las coordenadas **latitud/longitud** de la ciudad ingresada.

  ![response](https://github.com/user-attachments/assets/2130a51b-1fae-4759-ae3c-490d39b91034)
  - **Realiza la solicitud a la API** con `file_get_contents()`.
  - Si la solicitud **falla**, muestra un mensaje de error y detiene la ejecución.
  - **Decodifica la respuesta JSON** para extraer los datos.
  - **Si la ciudad no existe**, muestra un mensaje de error.
  - Guarda la **latitud** (`$lat`) y **longitud** (`$lon`) de la ciudad.

  ![weather](https://github.com/user-attachments/assets/b4d600a7-6838-4d10-98c1-d3d53bd17fae)
  - **Construye la URL de consulta del clima** usando las coordenadas obtenidas.
  - **Obtiene la respuesta** de la API con `file_get_contents()`.
  - **Si la API no responde**, muestra un mensaje de error.
  - **Convierte la respuesta JSON** en un array PHP (`json_decode()`).

  ![informacion ciudad](https://github.com/user-attachments/assets/2580fa3a-0803-4a00-932c-f4acda42bde7)
  - **Muestra el nombre de la ciudad** (`{$data[0]['name']}`).
  - **Muestra los datos del clima**, como:
    - **Temperatura actual** (`{$weatherData['main']['temp']}°C`).
    - **Condición climática** (`{$weatherData['weather'][0]['description']}`).
    - **Humedad relativa** (`{$weatherData['main']['humidity']}%`).
    - **Velocidad del viento** (`{$weatherData['wind']['speed']} m/s`).

  ![enlaces](https://github.com/user-attachments/assets/de3b22c4-bcd8-418f-bca9-f58c67ae51cf)
  - **Crea enlaces dinámicos** para consultar el clima en `hourly.php` (por horas) y `weekly.php` (semanal).
  - Envía la **latitud y longitud** en la URL para que las otras páginas puedan usarla.

  ### Configuración del hourly
  ![Captura de pantalla 2025-03-04 231739](https://github.com/user-attachments/assets/9471e40b-3181-49c9-b572-bc8db7b063cc)
  - **HTML básico**: La estructura contiene un encabezado con el título de la página y un enlace a una hoja de estilos CSS para personalizar el diseño visual de la página.
  - **Chart.js**: Se incluye la librería externa Chart.js, que es utilizada para dibujar gráficos interactivos en el navegador.
  - **Estilos CSS**: Se definen los estilos para un botón de inicio (<a>), que redirige al usuario a otra página de la aplicación.







             
