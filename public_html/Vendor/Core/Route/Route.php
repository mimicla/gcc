<?php
/**
 * @PSR-0: Env\Route\Route
 * =======================
 *
 * @Filename Route.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Route;

class Route extends \Env\Object
{

    private static $routes;

    public static function setRoutes( array $routes )
    {
        self::$routes = $routes;
    }

    public static function setRoute( $setting ) 
    {
        self::$routes[] = $setting;
    }

    public static function factory( \Env\Network\Request $request )
    {
        // evaluar si el request coincide con alguna expresion generada 
        // al router
        foreach( self::$routes AS $route )
        {
            if( ( $data = self::evaluate( $route, $request ) ) !== false ) {
                return $data;
            }
        }
        return false;
    }

    public static function evaluate( $route, \Env\Network\Request $request )
    {
       
        if( $route instanceof \Aura\Router\Router ) {
            $app = $route->match( $request->RELATIVE_URI, $request->getHeader() );
           
            return $app;
        }
        

        if( preg_match('#'. $route['exp'].'#is', $request->RELATIVE_URI, $match ) ) {
            if( isset( $route['_set'] ) ) {
                self::setParams( $route['_set'], $match );
                return false;
            }
            return $route;
        } else {
            if( __DEBUG__ ) {
                // echo $route['exp'].'#is' . ' ==> ' . $request->RELATIVE_URI . ' <br/>'; 
            }
        }
        return false;
    }

    private static function setParams( $route, $match)
    {
        $request = Request::getInstance();
        if( !isset( $route['params'] ) ) {
            return false;
        }
        $params = array();
        foreach( $route['params'] AS $key => $value )
        {
            if( isset( $match[$value] ) ) {
                $params[$key] = $match[$value];
            }
        }

        $request->set( $params, 'route' );
    }

}

