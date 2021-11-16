<?php

class DAO
{

	// Properties
	/* private static $dbHost = "mysql";
	private static $dbName = "challenge_accepted";
	private static $dbUser = "challenger";
	private static $dbPass = "challengepass";
	private static $sharedPDO;
	protected $pdo; */

	private static $dbHost = "ID309831_20192020.db.webhosting.be";
	private static $dbName = "ID309831_20192020";
	private static $dbUser = "ID309831_20192020";
	private static $dbPass = "challengepass2";
	private static $sharedPDO;
	protected $pdo;

	// Constructor
	function __construct()
	{

		if (empty(self::$sharedPDO)) {
			self::$sharedPDO = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName, self::$dbUser, self::$dbPass);
			self::$sharedPDO->exec("SET CHARACTER SET utf8");
			self::$sharedPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$sharedPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}

		$this->pdo = &self::$sharedPDO;
	}

	// Methods

}
