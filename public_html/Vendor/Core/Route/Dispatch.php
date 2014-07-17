<?php
/**
 * @PSR-0: Env\Route\Dispatch
 * =============================
 *
 * @Filename Dispatch.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Route;

use Env\Object;

class Dispatch extends Object
{
    public static $request;

    public static $response;

    public static function run( \Env\Network\Request $request, \Env\Network\Response $response )
    {
       
    }

    static private function _checkReflection( $namespace )
    {
        try {
            $r = new \ReflectionClass($namespace);
            return $r;
        } catch (\ReflectionException $e) {
            return false;
        }
        return false;
    }

    static public function call( $ns, $action, $args = array() ) 
    {
        $return = NULL;

        if ( ( $r = self::_checkReflection( $ns ) ) === false ) {
            echo $ns;
            return false;
        }

        
        
        if ( $r->isSubclassOf( 'Env\\Controller\\Controller' ) ) {
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
            if( empty( $args ) ) {
                $return = $method->invoke($instance);
            } else {
                $return = $method->invokeArgs($instance, $args);
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

    public static function shutdown()
    {
        Dispatch::run( \Env\Network\Request::getInstance(), new \Env\Network\Response() );
    }
}
