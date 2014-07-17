<?php
/**
 * @PSR-0: Env\Object
 * ===================
 *
 * @Filename Object.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env;

class Object
{

    /**
     * Instancia unica de objetos
     *
     * Todas las clases deriban de esta clase, y todo objeto tiene la propiedad de ser unico
     * la instancia unica permite mantener un objeto en tiempo de ejecucion y modificarlo en cualquier
     * momento del scope.
     * 
     * @return Env\Object Instancia del objeto
     */
    public static function getInstance()
    {
        static $instance = null;
        
        if (null === $instance) {
            $instance = new static();
            if( method_exists( $instance, 'on_construct' ) ) {
                $data = func_get_args();
                call_user_func_array( array( $instance, 'on_construct'), $data );
            }
        }

        return $instance;
    }

    /**
     * Invoka un metodo propio, todas las clases de la aplicacion extienden de esta clase
     * por lo cual cualquier objeto de la aplicacion puede ser invocado de esta manera
     *         !#php
     *         <?php
     *             namespace Env;
     *             class Object {
     *                 public function invoke ...
     *
     *                 public function test ( $param, $param2 )
     *                 {
     *                     echo $param . ' ' . $param2;
     *                 }
     *             }
     *             
     *             $obj = new Env\Object();
     *             $obj->invoke( 'metodo', array( 'arg_0' => 'hola', 'arg_1' => 'mundo' ) );
     *             // esperado
     *             // => hola mundo
     *             
     * @param  string $method Nombre del metodo a ejecutar
     * @param  array  $params argumentos, opcionales
     * @return mixed  [Si no es ejecutable genera una excepcion para ser tratada por le manjador de errores | si se puede ejecutar ejecuta dentro de un bloque try, 
     *                  ei el callback genera una excepcion, el invoke genera otra excepcion adjuntando el mensaje original del callback ]
     */
    public function invoke( $name, $params = array() )
    {
        
        try {
            $method = new \ReflectionMethod(get_called_class(), $name);
            $method->setAccessible(true);
            $method->invokeArgs($this, $params);
        } catch ( \ReflectionException $e ) {
            
            throw new Exception\Object("No se puede invocar el metodo " . $name, 1);

        } catch ( \Env\Exception\Object $e ) {
            
            echo "No se puede invocar el metodo {$name} \n" . $e->getMessage();
        
        } catch ( \Exception $e ) {
        
            echo $e->getMessage();
        
        }

    }


    /**
     * Funcion interna que heradan todas las clases de la aplicacion
     * @return null
     */
    public function debug()
    {
        ob_start();
        debug_backtrace();
        $trace = ob_get_clean();
       
        $data = '<pre>';
        $data .= print_r($this,true) ."\n\n" ;
        $data .= $trace;
        $data .= '</pre>';
        return $data;
    }
}
