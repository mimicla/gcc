<?php

namespace Env\Aplication\API;

use Env\Config\Configure;

class Template extends \Env\Controller\Controller
{
	public function before_action() {
		$this->response->type = 'json';
	}

	public function load()
	{
		$this->data = array('status' => 404);
		$request = func_get_args();
		
		if( ! empty ( $request ) ) {
		
			$path = implode('/', $request);
			
			if( $this->get( $path ) ) {
				$this->data['status'] = 200;
			}

		}
		
		
	}

	public function after_action() {
		$this->render();
	}

	private function get( $path )
	{
		$config = \Env\Config\Configure::getInstance();
		$base = $config->get('path.templates');
		
		if( $base !== false ) {
			$filename = $base . '/' . $path . '.mustache';
			$this->data['path'] = $filename;
			if( file_exists( $filename ) ) {
				$this->data['template'] = $this->partial( $path );
				return true;
			} 
		}
		return false;

	}

	public function partial( $name, $recursive = true )
    {

        $tpl = \Env\Data\Collection::getInstance()->engine->getLoader()->load($name);
        $tpl = preg_replace_callback('#{{>\s?([\w\\\\/]+)\s?}}#', array($this, 'replace_callback'), $tpl);
        return $tpl;

    }

    public function replace_callback( $p )
    {
        try {
            $tpl = \Env\Data\Collection::getInstance()->engine->getLoader()->load($p[1]);
            $tpl = preg_replace_callback('#{{>\s?([\w\\\\/]+)\s?}}#', array($this, 'replace_callback'), $tpl);

        }catch (\Exception $e) {
            return 'Error al cargar el template ( ' . $p[0] . ')';
        }
        
        return $tpl;
    }


    public static function getFile( $filename )
    {
        if( file_exists( $filename ) ) {
            return file_get_contents( $filename );
        }
    }


}