<?php
class dbbrgy {
	public $server = 	/* SERVER:	 */ "localhost";//"192.168.3.38";
	public $username =	/* USERNAME: */	"root";
	public $password =	/* PASSWORD: */	"";//"beer";
	public $database =	/* DATABASE: */	"brgy";
	public $connection = '';
	function __construct() {
		$this->connection = new mysqli($this->server, $this->username, $this->password, $this->database);
	}
}
?>