<?php

class db {
	private $conn;
	
	function __construct() {
		$user = "root";
		$pass = "katt";
		$name = "webshop";
		$host = "78.73.132.182";
		$this->conn = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	function get_products(){
		$stmt = $this->conn->prepare('SELECT * FROM products WHERE Visible=1');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function verify_user($user, $pass){
		$stmt = $this->conn->prepare('SELECT * FROM products WHERE User=:user');
		$stmt->bind(":user", $user);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	}
}
?>