# Aplicación PHP Tiempo Atmosférico

## Índice

1. [Introducción](#introducción)  
2. [Requisitos Previos](#requisitos-previos)  
3. [Infraestructura y Direccionamiento IP](#infraestructura-y-direccionamiento-ip)
4. [Estructura](#estructura)  
5. [Configuración](#configuración)
    - [Configuración del index](#configuración-del-index)  
    - [Configuración del Servidor de Base de Datos](#configuración-del-servidor-de-base-de-datos)  
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
  
  
             
