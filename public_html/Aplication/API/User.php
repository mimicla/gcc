<?php

namespace Env\Aplication\API;

use Env\Config\Configure,
	Env\Model\CouchDB;



class User extends Controller
{

	protected $allowAllCallbacks = true;
	
	public function before_action()
	{
		parent::before_action();

	}

	public function add()
	{
		if( $this->request->request_method == "POST") {
			
			$this->save();
		
			header('Location: ' . $this->request->base .'/api/User');
		
		} else {
			$this->db = new CouchDB('hc');

			$this->response->type = 'html';
			$this->layout='Layout/Theme';
			$this->addAlias('Content', 'Api/User/Add');

		}
	}

	public function index() 
	{

		$couchdb = new CouchDB('hc');

		$this->response->type = 'html';
		$this->layout='Layout/Theme';
		$this->addAlias('Content', 'Api/User/Index');

		try {
		    $result = $couchdb->send('/_design/user/_view/users');
		} catch(CouchDBException $e) {
		    die($e->errorMessage()."\n");
		}
		
		$this->data = $result->getBody(true);
		$this->Results = $this->data;

		return $this->data;

	}

	public function edit( $action = 'edit', $id=false )
	{
		if( $this->request->request_method == "POST") {
			$this->save();
		
			header('Location: ' . $this->request->base .'/api/User');
		
		} else {

			$this->db = new CouchDB('hc');
			
			$result = $this->db->send('/'. $id );
			$this->doc = $result->getBody(true);


			$this->response->type = 'html';
			$this->layout='Layout/Theme';

			$this->addAlias('Content', 'Api/User/Add');

		}
	}


	private function save()
	{
		$edit = isset( $_POST['doc']['_rev'], $_POST['doc']['_id'] );
		$doc  = array();
		
		$this->db = new CouchDB('hc');

		$rol_type = $_POST['doc']['rol'];

		$_POST['doc']['rol'] = array(
			'type' => $rol_type,
			$rol_type => true
		);


		$update = array_merge( $doc, $_POST['doc']);

		$data = json_encode( $update );

		$this->data = $this->db->send('', 'POST', $data );
		
	}

	public function delete( $a = 'd', $id = false, $rev = false )
	{
		$this->db = new CouchDB('hc');
		$this->db->send( $id .'?rev=' . $rev, 'DELETE' );
		header('Location: ' . $this->request->base .'/api/User');
		die;
	}

	public function generar ( $a = 'a', $id = false )
	{
		$this->response->type = 'html';
			$this->layout='Layout/Theme';

			$this->addAlias('Content', 'Api/User/Add');
	}	
}