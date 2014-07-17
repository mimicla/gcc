<?php

namespace Env\Aplication\API;

use Env\Config\Configure,
	Env\Model\CouchDB;



class Examen extends Controller
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
		
			header('Location: ' . $this->request->base .'/api/examen');
		
		} else {

			$this->db = new CouchDB('hc');
			// Categorias
			$results = $this->db->send('/_design/application/_view/categoria');
			$data = $results->getBody(true);	
			$this->categorias = $data->rows;

			$this->response->type = 'html';
			$this->layout='Layout/Theme';
			$this->addAlias('Content', 'Api/Examen/Add');

		}
	}

	public function index() 
	{
		$couchdb = new CouchDB('hc');

		$this->response->type = 'html';
		$this->layout='Layout/Theme';
		$this->addAlias('Content', 'Api/Examen/Index');

		try {
		    $result = $couchdb->send('/_design/application/_view/examen');
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
		
			header('Location: ' . $this->request->base .'/api/examen');
		
		} else {

			$this->db = new CouchDB('hc');
			
			$result = $this->db->send('/'. $id );
			$this->doc = $result->getBody(true);


			// Categorias
			$results = $this->db->send('/_design/application/_view/categoria');
			$data = $results->getBody(true);	
			
			foreach( $data->rows as $row ) {
					if( $this->doc->categoria->id == $row->id ) {
						$row->value->is_selected = true;
					} 
				$this->categorias[] = $row; 
			}

			$this->response->type = 'html';
			$this->layout='Layout/Theme';

			$this->addAlias('Content', 'Api/Examen/Add');

		}
	}


	private function save()
	{
		$edit = isset( $_POST['doc']['_rev'], $_POST['doc']['_id'] );
		$doc  = array();
		
		$this->db = new CouchDB('hc');

		if( ! $edit ) {
			// Setear el autor del examen
			// @todo Obtener los datos del loguin para determinar el
			// profesor
			$profesor = array('id'=>'001','value'=>'Pablo');

			$doc['profesor'] = $profesor;
			$doc['fecha'] = date('Y/m/d H:i:s');
		}

		
		$this->parse_nivel_1();
		$this->parse_nivel_2();
		$this->parse_nivel_alt();
		$this->parse_nivel_fin();
		
		$categoria_array = explode(',', $_POST['doc']['categoria'][0]);
		
		$save_category = array(
			'id' => $categoria_array[0],
			'value' => $categoria_array[1]
		);
		
		$_POST['doc']['categoria'] = $save_category;


		$update = array_merge( $doc, $_POST['doc']);

		if( empty( $update['titulo'] ) ) {
			$update['titulo'] = 'Draft:: '. $update['categoria']['value'];
		}

		// Testing
			// echo '<pre>';
			// print_r($update);
			// die;


		$data = json_encode( $update );


		$this->data = $this->db->send('', 'POST', $data );
	}

	private function parse_nivel_1()
	{
		if( ! isset( $_POST['doc']['nivel_1'] ) ) return;

		$nivel = $_POST['doc']['nivel_1'];
		

		if( isset( $nivel['inspeccion_visual'] ) ) {
			$depth = count( $nivel['inspeccion_visual'] ) ;
			$items = array_values($nivel['inspeccion_visual']);
			
			foreach( $items as $n => $item ) {
				$item['depth'] = $n;
				$items[$n] = $item;
			}

			$nivel['inspeccion_visual'] = array(
				'depth' => $depth,
				'items' => $items
			);

		}

		// entrevista
		if( isset( $nivel['entrevista'] ) ) {
			$depth = count( $nivel['entrevista'] ) ;
			$items = array_values($nivel['entrevista']);

			foreach( $items as $n => $item ) {
				$item['depth'] = $n;
				$items[$n] = $item;
			}

			$nivel['entrevista'] = array(
				'depth' => $depth,
				'items' => $items
			);
		}

		$_POST['doc']['nivel_1'] = $nivel;

		return true;
	}
	private function parse_nivel_2()
	{
		if( ! isset( $_POST['doc']['nivel_2'] ) ) return false;

		$nivel = $_POST['doc']['nivel_2'];
		

		if( isset( $nivel['inspeccion_visual'] ) ) {
			$depth = count( $nivel['inspeccion_visual'] ) ;
			$items = array_values($nivel['inspeccion_visual']);
			
			foreach( $items as $n => $item ) {
				$item['depth'] = $n;
				$items[$n] = $item;
			}

			$nivel['inspeccion_visual'] = array(
				'depth' => $depth,
				'items' => $items
			);

		}

		// entrevista
		if( isset( $nivel['exploracion_palpacion_tests'] ) ) {
			$depth = count( $nivel['exploracion_palpacion_tests'] ) ;
			$items = array_values($nivel['exploracion_palpacion_tests']);

			foreach( $items as $n => $item ) {
				$item['depth'] = $n;
				$items[$n] = $item;
			}

			$nivel['exploracion_palpacion_tests'] = array(
				'depth' => $depth,
				'items' => $items
			);
		}

		$_POST['doc']['nivel_2'] = $nivel;

		return true;
	}

	private function parse_nivel_alt()
	{
		if( ! isset( $_POST['doc']['nivel_alternativo'] ) ) return false;

		$nivel = $_POST['doc']['nivel_alternativo'];
		

		if( isset( $nivel['tratamiento_alternativo'] ) ) {
			$depth = count( $nivel['tratamiento_alternativo'] ) ;
			$items = array_values($nivel['tratamiento_alternativo']);
			
			foreach( $items as $n => $item ) {
				$item['depth'] = $n;
				$items[$n] = $item;
			}

			$nivel['tratamiento_alternativo'] = array(
				'depth' => $depth,
				'items' => $items
			);

		}

		$_POST['doc']['nivel_alternativo'] = $nivel;

		return true;
	}

	private function parse_nivel_fin()
	{
		if( ! isset( $_POST['doc']['nivel_fin'] ) ) return false;

		$nivel = $_POST['doc']['nivel_fin'];
		

		if( isset( $nivel['tratamiento_no_instrumental'] ) ) {
			$depth = count( $nivel['tratamiento_no_instrumental'] ) ;
			$items = array_values($nivel['tratamiento_no_instrumental']);
			
			foreach( $items as $n => $item ) {
				$item['depth'] = $n;
				$items[$n] = $item;
			}

			$nivel['tratamiento_no_instrumental'] = array(
				'depth' => $depth,
				'items' => $items
			);

		}

		// entrevista
		if( isset( $nivel['tratamiento_instrumental'] ) ) {
			$depth = count( $nivel['tratamiento_instrumental'] ) ;
			$items = array_values($nivel['tratamiento_instrumental']);

			foreach( $items as $n => $item ) {
				$item['depth'] = $n;
				$items[$n] = $item;
			}

			$nivel['tratamiento_instrumental'] = array(
				'depth' => $depth,
				'items' => $items
			);
		}

		// entrevista
		if( isset( $nivel['ejercicios_para_casa'] ) ) {
			$depth = count( $nivel['ejercicios_para_casa'] ) ;
			$items = array_values($nivel['ejercicios_para_casa']);

			foreach( $items as $n => $item ) {
				$item['depth'] = $n;
				$items[$n] = $item;
			}

			$nivel['ejercicios_para_casa'] = array(
				'depth' => $depth,
				'items' => $items
			);
		}

		$_POST['doc']['nivel_fin'] = $nivel;

		return true;
	}




	public function delete( $a = 'd', $id = false, $rev = false )
	{
		$this->db = new CouchDB('hc');
		$this->db->send( $id .'?rev=' . $rev, 'DELETE' );
		header('Location: ' . $this->request->base .'/api/examen');
		die;
	}

	public function generar ( $a = 'a', $id = false )
	{
		$this->response->type = 'html';
			$this->layout='Layout/Theme';

			$this->addAlias('Content', 'Api/Examen/Add');
	}	
}