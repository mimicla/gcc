<?php

namespace Env\Model\Mysql;

use Env\Config\Configure AS Config;

class Connection
{

	// Singleton
		private static $_instance = null;

		public static function getInstance()
		{
			if( is_null ( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

	public $conn;

	private $config;

	private function __construct()
	{
		$this->config = Config::getInstance();

		try {
			
			$options = array(
              \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
              \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
              // \PDO::ATTR_STATEMENT_CLASS => array('\\System\\PDOStatement', array()),
            );

            $dsn = ( $cfg_dsn = $this->config->get('database.dsn') ) !== false  
            		? $cfg_dsn 
            		: 
            		'mysql:dbname=' .  $this->config->get('database.name') .';host='. $this->config->get('database.host');

			$this->conn = new  \PDO( $dsn , $this->config->get('database.user'), $this->config->get('database.password'), $options );
			$this->conn->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (\PDOException $e) 
        {
            $this->conn = null;
            die('Error en la conexion');
            // die($e->getMessage());
        }
	}


}