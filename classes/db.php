<?php

class db {
	const CODE_SALT = "939760F3ACEE1D02785A4CE834A98E0301FE92E4E77F7C48E0A7206B797D0F8C";
	
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
		$stmt = $this->conn->prepare('SELECT * FROM users WHERE Username=:user');
		$stmt->bind(":user", $user);
		$stmt->execute();
		$real = $stmt->fetch(PDO::FETCH_ASSOC);
		$salt = $real['salt'];
		$crsalt = mcrypt_ecb(MCRYPT_DES, CODE_SALT, $pass.$salt, MCRYPT_ENCRYPT);
		$hash = hash('sha512', $crsalt);
		if($hash === $real['pass']) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function create_user($user, $pass, $adr){
		$stmt = $this->conn->prepare('INSERT INTO users (Username, Password, Salt, Address) VALUES (:user,:pass,:salt,:adr)');
		$stmt->bind(":user", $user);
		$stmt->bind(":adr", $adr);
		
		$p0 = uniqid(mt_rand(), true); // 240 bit entropy each
		$p1 = uniqid(mt_rand(), true); // http://stackoverflow.com/questions/4099333/how-to-generate-a-good-salt-is-my-function-secure-enough
		$salt = $p0.$p1;
		
		$crsalt = mcrypt_ecb(MCRYPT_DES, CODE_SALT, $pass.$salt, MCRYPT_ENCRYPT);
		$hash = hash('sha512', $crsalt);
		
		$stmt->bind(":salt", $salt);
		$stmt->bind(":pass", $hash);
		
		$stmt->execute();
		$real = $stmt->fetch(PDO::FETCH_ASSOC);
		$salt = $real['salt'];
		$hash = hash('sha512', $pass.$salt);
		if($hash === $real['pass']) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>