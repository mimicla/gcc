<?php

namespace Env\Aplication\Api;

use Env\Model\CouchDB;

class Controller implements \Env\Data\Definition\Controller
{
	private $_has_render = false;

	public $data;

	public $response;

	public $request;

	public $layout ='Layout/Ajax';

	protected $callbacks = array();

	protected $allowAllCallbacks = false;

	public function __construct( $request, $response)
	{
		$this->before_construct();

		$this->response =  $response;
		$this->request  = $request;
	}

	public function render()
	{
		$this->before_render();
        
        switch( $this->response->type ) {
            case 'json' : 
                $this->response->body = $this->render_json();
                break;
            case 'html' : 
                $this->response->body = $this->render_html();
                break;
            case 'plain' : 
            	$this->response->body = $this->data;
        		break;
        }

        $this->after_render();	
	}

	public function render_html()
    {
        // Set Global Data transport
        $engine = \Env\Data\Collection::getInstance()->engine;

        return $engine->render( $this->layout, $this );
    }

    public function render_json()
    {
    	$callback = $this->request->get('callback');
    	$before = $after = '';

    	if( false !== $callback ) { 
    		if( $this->allowAllCallbacks || in_array( $callback, $this->callbacks ) ) {

		    	$before = $callback . '(' ;
		    	$after  = ')' ;
    		}
    	}

        return $before . json_encode( $this->data ) . $after;
    }


    public function addAlias( $name , $value = false )
    {
        if( is_array( $name ) ) {
            \Env\Controller\AliasLoader::getInstance()->setAliases( $name, $value );
            return;
        }

        \Env\Controller\AliasLoader::getInstance()->set($name, $value);
    }

    public function set( $key, $object )
    {
        \Env\Data\Collection::getInstance()->engine->addHelper( $key, $object );
    }

    public function __destruct()
    {
    	
    }

    // -------------------------- Hooks ------------------------ //
        // hooks construct / destruct
        public function before_construct() {}
        public function after_construct() {}
        // hooks actions
        public function before_action() {
            
            $this->Page = \Env\View\Page::getInstance();
            
            $this->Page 
                    -> setMetaData( array( 'title' => 'Generador universal de casos clinicos') )
                    
                    -> addStylesheet( $this->request->base . '/assets/css/theme.css')
                    -> addStylesheet( $this->request->base . '/assets/css/kendo/web/kendo.common-bootstrap.min.css')
                    -> addStylesheet( $this->request->base . '/assets/css/kendo/web/kendo.flat.min.css')
                    
                    -> addScript ( $this->request->base .'/assets/js/jquery.min.js')
                    -> addScript ( $this->request->base .'/assets/js/jquery-ui.min.js')
                    
                    -> addScript ( $this->request->base .'/assets/js/upload/jquery.fileupload.js')
                    -> addScript ( $this->request->base .'/assets/js/upload/jquery.iframe-transport.js')
                    
                    -> addScript ( $this->request->base .'/assets/js/mustache.min.js')
                    -> addScript ( $this->request->base .'/assets/js/kendo.all.min.js')
                    -> addScript ( $this->request->base .'/assets/js/bootstrap.min.js')
                    -> addScript ( $this->request->base .'/assets/js/Plugins/Forms/jquery.switchButton.js')
                    -> addScript ( $this->request->base .'/assets/js/env/ui.js')
            ;

        }
        public function after_action() {
        	if( $this->_has_render !== true )
    			$this->render();
        }
        // hooks render
        public function before_render() {}
        public function after_render() {}
    // --------------------------------------------------------- //
    

    public function uuid()
    {
        $db = new CouchDB('_uuids');
        $data = $db->send();
        $uuid = $data->getBody(true);
        return $uuid->uuids[0];
    }
}