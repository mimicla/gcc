<?php
/**
 * @PSR-0: Config\Bootstrap
 * ========================
 *
 * @Filename Bootstrap.php
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 */

namespace Env\Config;


class Bootstrap extends \Env\Object
{
    public static function load()
    {
        $instance = self::getInstance();
        $instance->config = \Env\Config\Configure::getInstance();

        try {
            $instance->invoke( 'define_scope' );
        } catch ( \Env\Exception\Object $e ) {
            echo $e;
        } catch ( \Exception $e ) {
            echo $e->getMessage();
        }
        

        
    }

    private function define_scope()
    {
       $this->config->scope = 'Config\\Bootstrap';
       $this->config->path = array(
            'templates' => APP . DS . 'Aplication' . DS . 'Templates'
       );

       $this->set_schema();
       $this->load_routes();
       $this->set_request();
       $this->set_engine();
    }

    public function set_schema()
    {

        $this->config->database = array(
            'dsn' => 'mysql:dbname=env;host=localhost',
            'user' => 'root',
            'password' => 'sunga'
        );
    

    }

    public function load_routes()
    {
            
            $router_factory = new \Aura\Router\RouterFactory;
            
            $collection     = \Env\Data\Collection::getInstance();

            $collection->route = $router_factory->newInstance();

            
            $collection->route  ->add('Ajax.Temaplate', '/template/load')
                                ->setValues( array(
                                    'namespace' => '\\Env\\Aplication\\API\\Template',
                                    'action'    => 'load'
                                ) )
                                ->setWildcard('args');

            $collection->route  ->add('Api.Pages', '/api')
                                ->setValues( array(
                                    'namespace' => '\\Env\\Aplication\\API\\Dispatcher',
                                    'action'    => 'run'
                                ) )
                                ->setWildcard('args');


            $collection->route  ->add('App.login', '/login')
                                ->setValues( array(
                                    'namespace' => '\\Env\\Aplication\\Page\\User',
                                    'action'    => 'login'
                                ) );
            $collection->route  ->add('App.logout', '/logout')
                                ->setValues( array(
                                    'namespace' => '\\Env\\Aplication\\Page\\User',
                                    'action'    => 'logout'
                                ) );


            \Env\Route\Route::setRoute($collection->route);

    }

    public function set_request()
    {

        $base = '/~miricla';
        

        $request = \Env\Network\Request::getInstance( $base );
    }

    public function set_engine()
    {
        $collection         = \Env\Data\Collection::getInstance();
        
        $collection->engine =   new \Mustache_Engine(
                                    array(
                                        'loader'            =>  new \Mustache_Loader_FilesystemLoader( 
                                                                    \Env\Config\Configure::getInstance()->get('path.templates') 
                                                                ),
                                        // Definir la carga del partial loader
                                        // en este caso uso un alias loader que permite cambiar el valor de forma dinamica desde el controlador
                                        'partials_loader'   =>  \Env\Controller\AliasLoader::getInstance(
                                                                    \Env\Config\Configure::getInstance()->get('path.templates'), 
                                                                    array()
                                                                )
                                    )
                                );
    }
}
