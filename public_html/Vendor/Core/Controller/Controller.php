<?php
/**
 * @PSR-0: Env\Controller\Controller
 * ======================
 * @Filename 
 *
 * @author Pablo Adrian Samudia <p.a.samu@gmail.com>
 * 
 */

namespace Env\Controller;

/**
 * Capa de logica para comunicar la peticion con el modelo y la vista
 * estructura inspirada en el patron MVC, pero de manera simple y liviana
 * para aplicaciones rapidas
 *
 * @todo  documentar metodos
 */

class Controller extends \Env\Object
{
    public $layout = 'Layout/Theme';

    public $data = array();

    protected $allow = '*';

    public function __construct(\Env\Network\Request $request, \Env\Network\Response $response )
    {
        $this->request = $request;
        $this->response = $response;
        AliasLoader::getInstance( \Env\Config\Configure::getInstance()->get('path.templates') , array() );

        $this->before_construct();
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
           $this->layout ='Layout/Ajax';
        }
    }

    public function render()
    {
        $this->before_render();

        switch( $this->response->type ) {
            case 'json' : 
                $this->response->body = $this->render_json();
                break;
            default:
                $this->response->body = $this->render_html();
                break;
        }

        $this->after_render();
        
    }

    public function addAlias( $name , $value = false )
    {
        if( is_array( $name ) ) {
            AliasLoader::getInstance()->setAliases( $name, $value );
            return;
        }

        AliasLoader::getInstance()->set($name, $value);
    }

    public function set( $key, $object )
    {
        \Env\Data\Collection::getInstance()->engine->addHelper( $key, $object );
    }
    
    public function render_html()
    {
        // Set Global Data transport
        $engine = \Env\Data\Collection::getInstance()->engine;

        return $engine->render( $this->layout, $this );
    }

    public function render_json()
    {
        return json_encode( $this->data );
    }

    public function __destruct()
    {
        $this->after_construct();
    }


    // -------------------------- Hooks ------------------------ //
        // hooks construct / destruct
        public function before_construct() {}
        public function after_construct() {}
        // hooks actions
        public function before_action() {}
        public function after_action() {}
        // hooks render
        public function before_render() {}
        public function after_render() {}
    // --------------------------------------------------------- //
}
