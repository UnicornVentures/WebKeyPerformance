<?php
require_once 'config.php';

final class DataBase {

	private static $_dns = config::DB_DNS;
	private static $_userName = config::DB_USERNAME;
	private static $_password = config::DB_PASSWORD;
	private static $_instance;

	private function __construct() { }

    /**
     * Crea una instancia de la clase PDO
     *
     * @access public static
     * @return object de la clase PDO
	 */
	public static function getInstance() {
		if (! isset(self::$_instance)) {
			self::$_instance = new PDO(self::$_dns, self::$_userName, self::$_password);
			self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$_instance;
	}

	/**
	 * Impide que la clase sea clonada
  	 *
     * @access public
     * @return string trigger_error
     */
	public function __clone() {
    	trigger_error('Clone is not allowed.', E_USER_ERROR);
  	}
}
