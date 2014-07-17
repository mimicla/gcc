```
#!Env
    
    --- Documentación ---

```



Abstracto
=========

Env es un concepto abstracto, significa el contexto en tiempo de ejecución, el mismo puede ir sobre escribiéndose,
uniendo, o haciendo operaciones dependiendo de en que contexto se use la información.



Relevamiento técnico
====================


## Convenciones

Se establecen a continuación los grupos de convenciones que se implementaran en el desarrollo


- Estándar de documentación

    
    - Los contenidos de la documentación se expresaran en español


    - Se utilizara markdown para la generación de documentación ya sea en archivos específicos, como en 
    bloques de documentos que puedan llegar a ser parseados, por ejemplo los DockBlock para la generación
    de documentación de desarrollo.


    - para documentar un directorio se utilizaran los archivos README.md en dicha base relativa.


    - se podrá crear cualquier estructura que siga las convenciones de directorios y nombres de archivos.


- Referencias
       
        #!ref
        { contenido }  => representación abstracta
        [ contenido ]  => representación conceptual
        ( contenido )  => agrupación de contenido
        *              => cualquier carácter
        |              => Or lógico 
        ( * ) ? | *?   => opcional
    

- Directorios
    

    - Definición:
        
            #! ( {Tipo} ) ?
            ( --- {namespace} --- ) ?
        
            {propiedad} : {valor/abstracto} ( #? descripción ) ?
           

    - Interno: 
   
            #!tree
            --- [Entorno] ---
            nombre : [Palabra] con mayúscula
                     [PalabraCompuesta] CamelCase       
    
        
    - Publico:

            #!tree
            --- [Entorno] ---
            nombre: [palabra] en minúscula
            # ya que serán accesibles mediante url
        


- Estándar de Programación

    - PSR-0 para la nomenclatura

    - Se aplicará gran parte de las convenciones del PSR-1 y PSR-2


- Intersecciones
    
    - Cada nivel de directorio podrá decidir la generación del contexto, configuración, y cualquier parámetro que 
    necesite para crear la abstraccion necesaria.



## Estructura

```
#!tree
    --- Interno ---
    Aplicacion
        Page
        Module
            Rest
            Widget
        Template
            Layout
            Component
        Config
    Public
        --- Publico ---
        asset
            css
            img
            js
        templates
            component           # componentes compartidos para métodos asincronicos 
    Vendor                      # Librerías complementarias
        Mustache                # Gestor de templates http://mustache.io/
        Php-fig                 # Librerías para implementar los estándares de http://www.php-fig.org/
        Core                    # Entorno genérico para el procesamiento de la aplicación
```



### Sub Estructuras

```
#!tree
    --- Aplicacion ---
    Page
        - Object ( <= extiende de Core/Page )
        - Index ( <= extiende de Aplication/Page/Object )
        Config
            - Route ( <= extiende de Aplication/Config/Route )
```   

```
#!tree
    --- Interno ---
    Aplication
        Page
            Config  ( => /Aplication/Page/Config interseccion de /Aplication/Config )
        Config  ( => /Aplication/Config interseccion de /Config )
    Config ( => /Config interseccion de /Core/Config )
``` 
