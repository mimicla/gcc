<?php
/**
 * @PSR-0: Aplication\Dispatch
 * ===========================
 *
 * @Filename Dispatch.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Aplication;

class Dispatch extends \Env\Object
{
    public static $request;

    public static $response;

    private static $sent = false;

    public static function run( $request = false , $response = false, $dapp = false )
    {
       
        self::$request  = ( $request == false ) ? \Env\Network\Request::getInstance() : $request ;
        self::$response = ( $response == false ) ? \Env\Network\Response::getInstance() : $response;

        self::$request->match = array();
        
        $default = array(
            'namespace' => '\Env\Aplication\API\Examen',
            'action'    => 'index',
            'args'      => array()
        );

        $app = \Env\Route\Route::factory( self::$request);
        
        if( $app === false ) {
            $app = $default;
        } else {
            if( $app instanceof \Aura\Router\Route ) {
               
                $app = $app->params ;

                if( isset( $app['page'] ) ) {
                    $app['namespace'] .= ucfirst( strtolower( $app['page'] ) );
                }  


            }
            self::$request->match[] = $app;
            self::$request->origen  = $app; 
            $app = array_merge( $default, $app);
        }

        if( $dapp !== false ){
            $app = array_merge($app, $dapp);
        }


        if( isset( $app['format'] ) ) {
            self::$response->type = trim( $app['format'], '.');
        }

        if( self::call( $app['namespace'], $app['action'], $app['args'] ) === false ) {
            
            if( self::call( $default['namespace'], $default['action'], $default['args'] ) === false ) {
        	//echo '<pre>'.print_r($_SERVER,true)."\n\n".print_r(self::$request).'</pre>';        
		//die;	
		trigger_error( $app['namespace'] .' No se encuentra un controlador valido para el manejo de errores, por favor solucionar este problema crando un controlador de Error en la aplicacion', E_USER_ERROR);
            }
        
        }

        self::$response->render();

    }

    static private function _checkReflection( $namespace )
    {
        try {
            $r = new \ReflectionClass($namespace);
            return $r;
        } catch (\ReflectionException $e) {
            echo $e->getMessage();
            return false;
        }
        return false;
    }

    static public function call( $ns, $action, $args = array() ) 
    {
        $return = NULL;

        if ( ( $r = self::_checkReflection( $ns ) ) === false ) {
            echo 'error';
            return false;
        }

        self::$request->match[] = array('namespace' => $ns, 'action' => $action );

        if ( $r->isSubclassOf( 'Env\\Controller\\Controller') || $r->implementsInterface('Env\Data\Definition\Controller') ) {
            $instance = $r->newInstanceArgs( array( self::$request, self::$response ) );
        } else {
            $instance = $r->newInstance();
        }

        // Hook before action
        if( $r->hasMethod('before_action') ){
            $method = $r->getMethod( 'before_action' );
            $method->invoke($instance);
        }

        if( $r->hasMethod($action) ) {
            $method = $r->getMethod( $action );
            try {
                if( empty( $args ) ) {
                    $return = $method->invoke($instance);
                } else {
                    $return = $method->invokeArgs($instance, $args);
                }    
            } catch (\Exception $e) {
                throw new \Exception(' DIspatch Error:: ' . $e->getMessage() );
            }
            
        } else {
           if( $r->hasMethod('_global') ) {
                $global_method = $r->getMethod( '_global' );
                $return = empty( $args ) ? $global_method->invoke( $instance ) : $global_method->invokeArgs( $instance, $args);
           }  
        }

        // Hook before action
        if( $r->hasMethod('after_action') ){
            $method = $r->getMethod( 'after_action' );
            $method->invoke($instance);
        }

        return $return;
    }

    public static function sent()
    {
        self::$sent = true;
    }


    public static function shutdown()
    {
        
        Dispatch::run( \Env\Network\Request::getInstance(), new \Env\Network\Response() );
        //\App\Connection::close();
    }
}
