<?php

namespace Env\Aplication\API;



class Dispatcher
{
	public function run()
	{
		$args = func_get_args();
		$nargs = count( $args );

		$namespace = 'Env\Aplication\API';
		$action = 'index';

		switch( true ) {
			case ( $nargs == 0) : 
				$namespace .= '\Index' ;
				$args = array();
				break;
			case ( $nargs == 1 ) : 
				$namespace .= '\\' . ucfirst($args[0]);
				$args = array();
				break;
			case ( $nargs > 1 ) :
				$namespace .= '\\' . ucfirst($args[0]);
				$action = $args[1];
				$args = array_slice($args, 1); 
			break;
		}



		$request  = \Env\Network\Request::getInstance();
		$response = \Env\Network\Response::getInstance();


		try {

			\Env\Aplication\Dispatch::$request  = $request;
			\Env\Aplication\Dispatch::$response = $response;
			
			$result = \Env\Aplication\Dispatch::call( $namespace, $action, $args );

		} catch ( \Exception $e ) {
			$result = $e->getMessage();
			echo 'error al cargar la aplicacion';
		}

	}
}