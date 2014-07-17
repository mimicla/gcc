<?php

namespace Env\Model;

class CRUD extends Model
{
	
	protected $schema = array();

	protected $prefix = '';

	protected $model;

	public function __construct( $model = false , $schema = false , $prefix = false )
	{
		if( $model !== false )  $this->model = $model;
		if( $schema !== false ) $this->schema = $schema;
		if( $prefix !== false ) $this->prefix = $prefix;

	}

	private function before_create( $data = array() ) { }
	private function before_read( $data = array() ) { }
	private function before_update( $data = array() ) { }
	private function before_delete( $data = array() ) { }

	public function create( $data = array() )
	{
		if ( empty ( $data ) || empty ( $this->schema ) ) return false;

		$this->before_create( $data );

		$fields = $bind = array();

		$table = $this->prefix . $this->model;
		
		$keys = array_keys( $data );

		foreach( $keys AS $field ) {
			if ( isset ( $this->schema[$field] ) ) {
				$fields[$field] = $data[$field];
				$bind[':' . $field] = $data[$field];
			}
		}

		// Si no se seteo ningun campo no continua
		if( empty ( $fields ) ) return false;

		$keys = array_keys( $fields );

		$binds = array_keys( $bind );

		
		$sql = sprintf( 
				// CREATE SQL
				'INSERT INTO %s ( %s ) VALUES ( %s )',
				// %s(1) tabla
				$table,
				// %s(2) Campos separados por comas
				implode(',', $keys ),
				// %s(3) Valores separados por comas
				implode(',', $binds )
			);

		try {
			
			self::call($sql, $bind );
			$id = self::$dbh->lastInsertId();
			$this->after_create( $data, $id );
			return true;
		} catch (\Exception $e) {
			echo ' No se ha podido insertar ' . $sql;
			print_r($bind);
		}
		
		return false;
	}

	public function update( $data = array() )
	{
		if ( empty ( $data ) || empty ( $this->schema ) ) return false;

		$this->before_update( $data );

		$fields = $bind = array();

		$table = $this->prefix . $this->model;
		
		$keys = array_keys( $data );

		foreach( $keys AS $field ) {
			if ( isset ( $this->schema[$field] ) ) {
				$fields[$field] = $data[$field];
				$bind[':' . $field] = $data[$field];
			}
		}

		// Si no se seteo ningun campo no continua
		if( empty ( $fields ) ) return false;

		$keys = array_keys( $fields );

		$binds = array_keys( $bind );

		
		$sql = sprintf( 
				// CREATE SQL
				'UPDATE TABLE %s ( %s ) VALUES ( %s )',
				// %s(1) tabla
				$table,
				// %s(2) Campos separados por comas
				implode(',', $keys ),
				// %s(3) Valores separados por comas
				implode(',', $binds )
			);

		try {
			
			self::call($sql, $bind );
			$id = self::$dbh->lastInsertId();
			$this->after_create( $data, $id );
			return true;
		} catch (\Exception $e) {
			echo ' No se ha podido insertar ' . $sql;
			print_r($bind);
		}
		
		return false;
	}

	public function read( $params = array(), $bind = array() )
	{
		$this->before_read( $params );

		

		$sql = "SELECT %s FROM %s %s %s %s";
		$def = array(
			'select' => '*',
			'from'   =>  $this->prefix . $this->model ,
			'where'  => '',
			'limit'  => '',
			'order'  => ''
		);

		$list = array();
		
			if( isset( $params['sql'] ) ) {
				$sql = $params['sql'];
			} else {
				foreach( array('select', 'from', 'where', 'limit', 'order') AS $stmt ) {
					if( isset( $params[$stmt] ) ) {
						if( $stmt == 'where') {
							if( strpos($params[$stmt], 'where') === false ) {
								$params[$stmt] = 'WHERE '. $params[$stmt];
							}
						}
						$list[] = $params[$stmt];
					} else {
					
						
						$list[] = $def[$stmt];
					}
				}
			}
		
		$sql = vsprintf( $sql, $list);


		try {
			return self::all($sql, $bind);
		} catch (\Exception $e) {
			echo ' No se ha podido insertar ' . $sql;
			
		}
		
		return false;
	}

	private function after_create( $data = array(), $id = NULL ) { }
	private function after_update( $data = array(), $id = NULL ) { }
	private function after_read( $data = array(), $id = NULL ) { }
	private function after_delete( $data = array(), $id = NULL ) { }

}