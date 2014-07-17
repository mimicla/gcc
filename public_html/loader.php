<?php

# Directorios para la aplicacion    

    defined ( 'DS'     )  ||  define ( 'DS'     ,  DIRECTORY_SEPARATOR   );             # Abreviacion de la constante DIRECTORY_SEPARATOR
    
    defined ( 'APP'    )  ||  define ( 'APP'    ,  __DIR__ );                           # Raiz del Scope
    defined ( 'ROOT'   )  ||  define ( 'ROOT'   ,  __DIR__ );     # Raiz del proyecto
    
    defined ( 'VENDOR' )  ||  define ( 'VENDOR' ,  APP . DS . 'Vendor'  );              # Carpeta de librerias complementarias
    defined ( 'CORE'   )  ||  define ( 'CORE'   ,  VENDOR . DS . 'Core' );               # Libreria base del proyecto

# Autoloaders
    require_once VENDOR . DS . 'autoload.php';
    require_once VENDOR . DS . 'Php-fig'  . DS . 'Psr4' . DS . 'Autoloader.php';                    # Autoloader segun las especificaciones de PSR-4
    require_once VENDOR . DS . 'Mustache' . DS . 'src'  . DS . 'Mustache' . DS . 'Autoloader.php';  # Autoloader segun las especificaciones de PSR-0 para {{ Mustache }}

# Definicion base del entorno
    Mustache_Autoloader::register();
    
    $loader = new Psr4\Autoloader;

    $loader->addNamespace ( 'Env'             ,   APP    . DS . 'Aplication'                         ); # Aplicacion global
    $loader->addNamespace ( 'Env'             ,   CORE                                                ); # Libreria principal para el entorno
    $loader->addNamespace ( 'Env\\Aplication' ,   APP    . DS . 'Aplication'                         ); # Aplicacion global
    $loader->addNamespace ( 'Env\\Config'     ,   APP    . DS . 'Config'                             ); # Configuracion global
    $loader->addNamespace ( 'Env\\Aplication' ,   CORE                                                ); # Extencion de Aplicacion al Core
    $loader->addNamespace ( 'Aura\\Router'    ,   VENDOR  . DS . 'aura' . DS . 'router' . DS . 'src'  ); # libreria para el manejo de expresiones y ruteo
    $loader->addNamespace ( 'Blueimp\\fileuploader'    ,   VENDOR  . DS . 'blueimp' . DS . 'fileuploader' . DS . 'src'  ); # libreria para el manejo de expresiones y ruteo
   

    $loader->register();

    // Asigar el loader para el scope
    Env\Data\Collection::getInstance()->loader = $loader;

# Inicializar la configuracion global    
    Env\Config\Bootstrap::load();
