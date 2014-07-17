<?php

namespace Env\Aplication\API;

use Env\Config\Configure,
	Env\Model\CouchDB;



class Categoria extends Controller_categoria
{

	protected $allowAllCallbacks = true;
	
	public function before_action()
	{
		parent::before_action();
	}

	public function add()
	{
		if( $this->request->request_method == "POST") {
			$this->db = new CouchDB('hc');
			
			$_POST['doc']['_id'] = $this->uuid();

			$data = json_encode( $_POST['doc'] );
			$this->data = $this->db->send('', 'POST', $data );
			header('Location: ' . $this->request->base .'/api/categoria');
		
		} else {
			$this->response->type = 'html';
			$this->layout='Layout/Theme_categoria';

			$this->addAlias('Content', 'Api/Categoria/Add');

		}
	}

	public function index() 
	{

		$couchdb = new CouchDB('hc');

		$this->response->type = 'html';
		$this->layout='Layout/Theme_categoria';
		$this->addAlias('Content', 'Api/Categoria/Index');

		try {
		    $result = $couchdb->send('/_design/application/_view/categoria');
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
			$this->db = new CouchDB('hc');

			$data = json_encode( $_POST['doc'] );
			$this->data = $this->db->send('', 'POST', $data );
			header('Location: ' . $this->request->base .'/api/categoria');
		
		} else {
			$this->db = new CouchDB('hc');
			
			$result = $this->db->send('/'. $id );
			$this->doc = $result->getBody(true);

			$this->response->type = 'html';
			$this->layout='Layout/Theme_categoria';

			$this->addAlias('Content', 'Api/Categoria/Add');

		}
	}

	public function delete( $a = 'd', $id = false, $rev = false )
	{
		$this->db = new CouchDB('hc');
		$this->db->send( $id .'?rev=' . $rev, 'DELETE' );
		header('Location: ' . $this->request->base .'/api/categoria');
		die;
	}	

	public function all()
	{
		$couchdb = new CouchDB('hc');

		$this->response->type = 'json';

		try {
		    $result = $couchdb->send('/_design/application/_view/categoria');
		} catch(CouchDBException $e) {
		    die($e->errorMessage()."\n");
		}


		
		$this->data = $result->getBody(true);
		$this->Results = $this->data;

		return $this->data;
	}
}