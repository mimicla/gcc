<?php
/**
 * @PSR-0: Env\Controller\AliasLoader
 * =======================
 * @Filename AliasLoader.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 * 
 */

namespace Env\Controller;

class AliasLoader extends \Mustache_Loader_FilesystemLoader
{

    public static function getInstance()
    {

        static $instance = null;
        
        if (null === $instance) {
            $data = func_get_args();
            if( !empty( $data ) ) {
                $instance = new self( $data[0], $data[1] );
            }
        }

        return $instance;
    }

    /**
    * Almacena las reglas para el loader
    * @var array
    */
   
    private $aliases;

    /**
     * Setea el loader para mustache
     * 
     * @param string $baseDir Directorio de los templates para el mustache
     * @param array  $aliases Definicion de alias
     * @param array  $options opciones de FilesystemLoader {@see \Mustache_Loader_FilesystemLoader} 
     */
    
    public function __construct( $baseDir, array $aliases = array(), array $options = array() )
    {
        $this->setAliases($aliases);
        parent::__construct($baseDir, $options);
    }


    /**
     * Intercepcion del metodo load del Mustache Loader
     *
     * Comprueba si existe algun alias definido y devuelve el valor guardado
     *     
     * @param  sring $name nombre abstracto del partial
     * @return mixed template o nulo
     */
    
    public function load( $name )
    {
        if( $this->aliases === null ) {
            return parent::load($name);
        }
        if(array_key_exists($name, $this->aliases)) {
            $name = $this->aliases[$name];
        }
        return parent::load($name);
    }


    /**
     * Setea un valor a los aliases
     * @param sring   $name      nombre del alias
     * @param string  $value     path del partial
     * @param boolean $overwrite define si el valor se debe sobreescribir
     */
    
    public function set( $name,  $value, $overwrite = true)
    {
        if(!$overwrite && array_key_exists($name, $this->aliases)) {
            return;
        }

        $this->aliases[$name] = $value;
    }


    /**
     * Setea multiples aliases
     *
     * Si no se setea el segundo parametro se sobreescribira el array de aliases
     * @param array   $aliases array con los aliases a definir
     * @param boolean $merge   tipo de fusion, si no se define se sobreescribe
     */
    
    public function setAliases( array $aliases, $merge = false )
    {
        if( $merge ) {
            $aliases = array_merge( (array) $this->aliases, (array) $aliases ); 
        }

        $this->aliases = $aliases;
    }
}
