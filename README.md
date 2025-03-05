# Aplicación PHP Tiempo Atmosférico

## Índice

1. [Introducción](#introducción)  
2. [Requisitos Previos](#requisitos-previos)  
3. [Infraestructura y Direccionamiento IP](#infraestructura-y-direccionamiento-ip)
4. [Estructura](#estructura)  
5. [Configuración](#configuración)
    - [Configuración del index](#configuración-del-index)  
    - [Configuración del hourly](#configuración-del-hourly)  
    - [Configuración del weekly](#configuración-del-weekly)  
    - [Configuración del css](#configuración-del-css)
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
  Este archivo PHP se utiliza para mostrar una previsión meteorológica por horas, donde se obtiene información sobre las temperaturas y las lluvias usando la API de OpenWeatherMap. Esta información se visualiza de manera gráfica mediante 
  Chart.js, combinando gráficos de líneas para las temperaturas y gráficos de barras para las precipitaciones. Además, se incluye un botón que redirige al usuario a la página principal de la aplicación.
  
  ![Captura de pantalla 2025-03-04 231739](https://github.com/user-attachments/assets/9471e40b-3181-49c9-b572-bc8db7b063cc)
  - **HTML básico**: La estructura contiene un encabezado con el título de la página y un enlace a una hoja de estilos CSS para personalizar el diseño visual de la página.
  - **Chart.js**: Se incluye la librería externa `Chart.js`, que es utilizada para dibujar gráficos interactivos en el navegador.
  - **Estilos CSS**: Se definen los estilos para un botón de inicio (`<a>`), que redirige al usuario a otra página de la aplicación.

  ![php](https://github.com/user-attachments/assets/9512d64e-0be2-4e26-97a5-24b7f04156e6)
  - **Obtención de parámetros de latitud y longitud**: Se reciben los parámetros `lat` y `lon` mediante `$_GET` para saber la ubicación geográfica de la cual obtener los datos meteorológicos.
  - **Llamada a la API de OpenWeatherMap**: Se construye una URL que consulta la API de OpenWeatherMap para obtener la previsión del tiempo por horas, utilizando la clave API y los parámetros de latitud y longitud proporcionados. La respuesta 
    de la API se obtiene con `file_get_contents`.
  - **Procesamiento de la respuesta JSON**: La respuesta se decodifica de formato JSON a un array asociativo de PHP. Luego, se extraen los datos de cada hora (temperatura y lluvia) y se almacenan en los arrays `$labels`, `$temperatures`, y 
    `$rainfall`.

  ![script1](https://github.com/user-attachments/assets/b414681f-2447-48a5-9f13-5fa53fc56a59)
  ![script2](https://github.com/user-attachments/assets/cd092dbf-86bb-4e94-a7ba-72f7d115abb4)
  - **Conversión de datos PHP a JavaScript**: Los arrays de PHP (`$labels`, `$temperatures`, y `$rainfall`) se convierten a formato JavaScript mediante `json_encode`, y se asignan a las variables JavaScript correspondientes.
  - **Configuración de Chart.js**: Se configura el gráfico utilizando la librería Chart.js. El gráfico es de tipo "barra" para la lluvia y "línea" para la temperatura, con dos ejes Y: uno para la temperatura y otro para la lluvia.
  - **Opciones de visualización**: Se establece el comportamiento del gráfico, incluyendo el título, las etiquetas de los ejes X e Y, y la disposición de los ejes.

  ### Configuración del weekly
  
  Este archivo weekly.php utiliza una combinación de PHP, JavaScript y la librería Chart.js para mostrar una previsión meteorológica semanal en formato gráfico. El código está estructurado en diferentes bloques para gestionar la obtención de 
  datos, su preparación y la visualización del gráfico, así como los iconos representativos del clima.
  ![html](https://github.com/user-attachments/assets/b4470970-2954-46ad-a180-b4bdff4a48ba)
  - **Título de la página**: El título de la página es "Previsión Semanal".
  - **Librerías externas**:
    - Se enlaza una hoja de estilos llamada `styles.css`.
    - Se carga la librería `Chart.js` desde un CDN para crear el gráfico.
  - **Botón de inicio**: Un botón que redirige al usuario a la página principal (`weather.php`).
  - **Gráfico de previsión**: Un <`canvas`> donde se dibujará el gráfico de temperaturas y lluvia semanal.
   
  ![php1](https://github.com/user-attachments/assets/c09c8c50-9147-4ab8-bde3-e6df99e564fa)
  ![php2](https://github.com/user-attachments/assets/4cd46d5f-13f2-4fdf-9068-4e6f0d13d42d)
  - **API OpenWeatherMap**:
    - Se define la clave API para acceder a OpenWeatherMap.
    - Se extraen las coordenadas de latitud y longitud desde la solicitud GET.
    - Se construye la URL para consultar la previsión del clima.
    - La respuesta se obtiene mediante `file_get_contents` y se verifica si la solicitud fue exitosa.
 - **Agrupación de datos**:
    - Los datos meteorológicos se agrupan por fecha ($dailyData).
    - Se extraen las temperaturas mínimas y máximas, la precipitación total (si existe) y el icono del clima para cada día.

  ![labels](https://github.com/user-attachments/assets/dca66c65-e842-46b8-a05e-48a87b214eab)
  - Se preparan los datos para el gráfico:
    - **Etiquetas de los días**: Se formatean las fechas de los días en un formato corto (por ejemplo, "Mar, Sep 2").
    - **Temperaturas**: Se almacenan las temperaturas mínimas y máximas de cada día.
    - **Precipitación**: Se almacenan las cantidades de precipitación diaria.
    - **Iconos**: Se almacenan los iconos del clima para cada día.
   
  ![script1](https://github.com/user-attachments/assets/0d0fed03-025a-4d50-9654-53a29df64300)
  ![script2](https://github.com/user-attachments/assets/fa009913-1980-4d39-a3c3-f142215df134)
  - **Convertir datos PHP a JavaScript**: Los datos generados en PHP se convierten a formato JavaScript utilizando `json_encode`.
  - **Configuración del gráfico**:
    - Se crea un gráfico de barras utilizando `Chart.js`, donde:
       - Las temperaturas mínimas y máximas se representan como barras.
       - La precipitación se muestra como una línea.
  - Se configuran las etiquetas de los ejes, el título y la disposición de las escalas (eje Y para temperaturas a la izquierda, y eje Y para lluvia a la derecha).

  ![estilo](https://github.com/user-attachments/assets/844d51d1-9085-4df8-960c-a4a97b3b4233)
  - Se definen los estilos para los iconos del clima que se mostrarán en la página.
  - Se utiliza un contenedor con `display: flex` para alinear los iconos de forma horizontal y se les asigna un tamaño de 50x50 píxeles.

 ### Configuración del css
 Este CSS define estilos visuales para una página de clima, asegurando una presentación clara y moderna. En el `body`, se establece una fuente legible y un fondo gris claro con un diseño centrado usando **flexbox**. El título (`h1`) tiene un 
 color azul oscuro y un tamaño grande, destacándose sobre el contenido. El formulario de búsqueda tiene un fondo blanco, bordes redondeados, y una sombra suave, con botones de búsqueda azules que cambian de tono al pasar el cursor. Los bloques 
 de información del clima actual y de previsión tienen un fondo blanco, bordes redondeados y sombras para darles profundidad visual, además de utilizar colores específicos como verde para los enlaces y azul para las horas. La previsión diaria 
 se presenta en una lista con detalles alineados de forma **flex** (hora, temperatura y descripción), y el último ítem no tiene borde inferior. Los mensajes de error se muestran en rojo para destacarse, mientras que los enlaces de navegación 
 tienen un fondo azul que cambia a un tono más oscuro cuando se pasa el cursor. Finalmente, los iconos del clima se alinean horizontalmente con un tamaño fijo de 50px para garantizar que sean fácilmente visibles. El diseño utiliza márgenes y 
 espaciados adecuados para una presentación limpia y centrada en la experiencia del usuario.

 ## Despliegue
El proceso de despliegue de la aplicación se realizará en una máquina EC2 (Elastic Compute Cloud) de AWS que incluye varios pasos. A continuación te explico cómo crear una máquina EC2, configurar grupos de seguridad para SSH y HTTP, y asignarle una IP elástica.

**1. Crear una máquina EC2 (Instancia)**
- **Accede a AWS Management Console**: Ingresa a tu cuenta de AWS y accede a la consola de administración.
- **Selecciona EC2**: En el panel de servicios, selecciona "EC2" bajo la categoría "Compute".
- **Lanza una nueva instancia**: Haz clic en “Launch Instance” para crear una nueva máquina virtual EC2.
- **Selecciona una AMI (Amazon Machine Image)**: AWS ofrece varias imágenes preconfiguradas. Elige una AMI adecuada para tu aplicación (para este proyecto se ha usado Ubuntu).
- **Elige un tipo de instancia**: Selecciona el tipo de instancia según los requisitos de tu aplicación (por ejemplo, `t2.micro` para pruebas o una instancia más potente si es necesario).
- **Configura la clave SSH**: Es importante que crees o selecciones un par de claves SSH para poder acceder a tu instancia de manera remota. Si no tienes uno, selecciona "Create a new key pair", descárgalo y guárdalo de forma segura.
![instancia 1](https://github.com/user-attachments/assets/90545918-0342-4d47-ae13-285c3f360cd6)
![instancia 2](https://github.com/user-attachments/assets/bb1e508d-f9dd-400c-a290-fee6793292e2)

**2. Configurar Grupos de Seguridad**

Los grupos de seguridad actúan como un firewall virtual que controla el tráfico entrante y saliente de tu instancia EC2. Vamos a configurar los grupos de seguridad para permitir el acceso SSH y HTTP.
- **Accede a los Grupos de Seguridad**: Durante el proceso de creación de la instancia EC2, se te pedirá configurar los grupos de seguridad. Si ya tienes un grupo de seguridad, puedes asignarlo; si no, crea uno nuevo.
  
**Configura las reglas de acceso:**
- **SSH (Puerto 22)**: Permite el acceso remoto a tu instancia EC2 mediante SSH. Añade una regla para el puerto 22:
 - **Tipo**: SSH
 - **Protocolo**: TCP
 - **Puerto**: 22

- **HTTP (Puerto 80)**: Para acceder a tu aplicación web (si está corriendo un servidor HTTP como Apache o Nginx), debes habilitar el puerto 80:
 - **Tipo**: HTTP
 - **Protocolo**: TCP
 - **Puerto**: 80
![grupo seguridad](https://github.com/user-attachments/assets/611a11ef-f748-4fd3-9ba0-936aaad4f83f)

**3. Asignar una IP Elástica**

Las IPs elásticas son direcciones IP estáticas asociadas a tu cuenta de AWS, lo que garantiza que la dirección IP de tu servidor no cambiará incluso si la instancia EC2 se detiene y reinicia. Aquí están los pasos para asignar una IP elástica:

- **Accede a la sección de IP Elásticas**: En la consola de AWS, selecciona "Elastic IPs" en la barra lateral de EC2.
- **Asigna una nueva IP Elástica**: Haz clic en “Allocate new address” para crear una nueva IP elástica. Elige la región en la que se encuentra tu instancia EC2.
- **Asocia la IP Elástica a tu Instancia EC2**: Una vez que la IP elástica sea asignada, selecciona “Actions” y luego “Associate address”.
- **Selecciona la Instancia**: En el campo "Instance", selecciona la instancia EC2 a la que deseas asociar la IP elástica y haz clic en "Associate".

**4. Acceso a tu instacia**

Una vez que hayas completado estos pasos, podrás acceder a tu instancia EC2 mediante SSH (si estás utilizando Linux/macOS, o utilizando un cliente SSH en Windows):
```console
ssh -i /path/to/your-key.pem ec2-user@your-elastic-ip
```
Aquí, `/path/to/your-key.pem` es la ruta donde guardaste tu archivo de clave privada, y `your-elastic-ip` es la dirección IP elástica asignada a tu instancia EC2.
Si configuraste correctamente el grupo de seguridad y el acceso SSH, deberías poder acceder a tu instancia EC2 de forma segura.

**5. Configurar máquina**

Dentro de la máquina tendremos que instalar **Apache** junto con los módulos **php** necesarios.
```console
sudo apt install -y apache2 php libapache2-mod-php php-cli php-mysql php-gd php-curl php-mbstring php-xml
```
Tras la instalación, crearemos y la estructura anteriormente descrita en el directorio `/var/www/html`
![estructura en maquina ](https://github.com/user-attachments/assets/82e24942-3cd9-4797-a07e-087bb66d52fd)

Por último, tendremos que modificar el fichero `000-default.conf` situado en `/etc/apache2/sites-available` y cambiar la ruta del document root a la carpeta donde está nuestro `index.php`. Recomendable cambiar 000-default.conf poerque al crear y habilitar un sitio nuevo puede dar conflictos con el css.
![000-default](https://github.com/user-attachments/assets/d3c78181-d021-4ad8-afb4-03f19dc32ce0)

Reiniciamos el serrvicio de **Apache**
```console
sudo systemctl restart apache2
```

**6. Verificación**

Para verificar que nuestra página está en funcionamiento, tendremos que poner el nombre de nuestro dominio si tenemos uno asignado a nuestra ip elástica o la ip pública que tenga en el momento nuestra instancia.
Tendremos acceso a la página principal desde la que podremos ir a las demás y volver de estas a la principal.
![pagina 1](https://github.com/user-attachments/assets/2dd3e451-b3fc-4792-ba61-8412c1c8b5b3)
![pagina 2](https://github.com/user-attachments/assets/a638219b-9f51-46a1-ba56-480acb5a899d)
![prevision semanal](https://github.com/user-attachments/assets/6234b19b-ab54-4e3b-adc5-ad7bd3faec3c)
En caso de que no encuentre la ciudad nos sladrá un error.
![ciudad no encontrada](https://github.com/user-attachments/assets/0ea59561-31ed-4620-8a38-689f7958f6d2)

## Conclusión
En este proyecto se desarrolló una aplicación en PHP que permite visualizar el tiempo actual, tanto para el día como para la semana, utilizando gráficos interactivos y datos en tiempo real proporcionados por la API de OpenWeather. La estructura principal del sistema consiste en un archivo index.php que obtiene la información meteorológica de una ciudad especificada por el usuario. A partir de esta información, el usuario puede acceder a dos vistas diferenciadas: una para el clima del día y otra para el clima de la semana.

La implementación de gráficos, que permiten un análisis visual de los datos, mejora la experiencia del usuario al ofrecer una forma clara y dinámica de entender las variaciones del clima a lo largo del día y la semana. El proyecto se encuentra desplegado en una instancia de Ubuntu en AWS EC2, lo que asegura alta disponibilidad y accesibilidad global a través de un dominio asociado a una dirección IP elástica.

Este proyecto no solo permite el acceso rápido a información meteorológica precisa y actualizada, sino que también demuestra la integración eficaz de APIs externas, el uso de gráficos interactivos en PHP, y el despliegue en la nube, proporcionando una solución robusta y escalable para la consulta de pronósticos del tiempo. La elección de AWS EC2 como plataforma de hosting garantiza que la aplicación pueda manejar tráfico variable sin comprometer el rendimiento, mientras que la integración con OpenWeather permite acceder a datos precisos y actualizados constantemente.





















             
