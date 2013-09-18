<?php
const CODE_SALT = "939760F3ACEE1D02785A4CE834A98E0301FE92E4E77F7C48E0A7206B";
const IV = "84983107";

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
		if(empty($user) || empty($pass)){
			throw new Exception("Empty user or pass");
		}
		$stmt = $this->conn->prepare('SELECT * FROM users WHERE Username=:user');
		$stmt->bindParam(":user", $user);
		$stmt->execute();
		$real = $stmt->fetch(PDO::FETCH_ASSOC);
		$salt = $real['salt'];
		
		$cipher = mcrypt_module_open(MCRYPT_BLOWFISH,'',MCRYPT_MODE_CBC,'');
		mcrypt_generic_init($cipher, CODE_SALT, IV);
		$crsalt = mdecrypt_generic($cipher, "$pass$salt");
		
		$hash = hash('sha512', $crsalt);
		if($hash === $real['pass']) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function create_user($user, $pass, $first, $last, $adr){
		$stmt = $this->conn->prepare('INSERT INTO users (Username, Password, FirstName, LastName, Salt, Address) VALUES (:user,:pass,:first,:last,:salt,:adr)');
		$stmt->bindParam(":user", $user);
		$stmt->bindParam(":first", $first);
		$stmt->bindParam(":last", $last);
		$stmt->bindParam(":adr", $adr);
		
		$p0 = uniqid(mt_rand(), true); // 240 bit entropy each
		$p1 = uniqid(mt_rand(), true); // http://stackoverflow.com/questions/4099333/how-to-generate-a-good-salt-is-my-function-secure-enough
		$salt = $p0.$p1;
		
		$cipher = mcrypt_module_open(MCRYPT_BLOWFISH,'',MCRYPT_MODE_CBC,'');
		mcrypt_generic_init($cipher, CODE_SALT, IV);
		$crsalt = mcrypt_generic($cipher, "$pass$salt");
		
		$hash = hash('sha512', $crsalt);
		$stmt->bindParam(":salt", $salt);
		
		$stmt->bindParam(":pass", $hash);
		$stmt->execute();
		$stmt->fetch();
		return TRUE;
	}
}
?>