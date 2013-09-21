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
	
	function logout() {
		// Initialize the session.
		// If you are using session_name("something"), don't forget it now!
		session_start();
		// Unset all of the session variables.
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		// Finally, destroy the session.
		session_destroy();
	}
	function create_pass($pass, $salt){
		$cipher = mcrypt_module_open(MCRYPT_BLOWFISH,'',MCRYPT_MODE_CBC,'');
		mcrypt_generic_init($cipher, CODE_SALT, IV);
		$crsalt = mcrypt_generic($cipher, "$pass$salt");
		$hash = hash('sha512', $crsalt);
		return $hash;
	}
	function verify_user($user, $pass){
		if(empty($user) || empty($pass)){
			throw new Exception("Empty user or pass");
		}
		$stmt = $this->conn->prepare('SELECT * FROM users WHERE Username=:user');
		$stmt->bindParam(":user", $user);
		$stmt->execute();
		
		if($stmt->rowCount() === 0){
			return FALSE;
		} else {
			$real = $stmt->fetch(PDO::FETCH_ASSOC);
			$salt = $real['Salt'];
			
			
			$hash = $this->create_pass($pass, $salt);
			if($hash === $real['Password']) {
				//SUCCESS
				session_start();
				$_SESSION['user'] = $user;
				$_SESSION['userid'] = $real['ID'];
				$_SESSION['last_logon'] = date('y-M-d');
			
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	function cart_add($userid, $pid){
		if(is_numeric($userid)){
			if(is_numeric($pid)){
				$stmt = $this->conn->prepare('INSERT INTO cart (ProductID, UserID) VALUES (:pid,:uid)');
				$stmt->bindParam(":pid", $pid);
				$stmt->bindParam(":uid", $userid);
				$stmt->execute();
				return $this->cart_get($userid);			
			}//Invalid pid
		}//Invalid uid
		var_dump($userid, $pid);
		return FALSE;
	}
	
	function cart_clear($userid){
		if(!empty($userid) && is_numeric($userid)){
			$stmt = $this->conn->prepare('DELETE from cart WHERE cart.UserID=:uid');
			$stmt->bindParam(":uid", $userid);
			$stmt->execute();
			return $this->cart_get($userid);			
		}//Invalid uid
		return FALSE;
	}
	
	function cart_get($userid){
		if(!empty($userid) && is_numeric($userid)){
			$stmt = $this->conn->prepare('SELECT cart.ProductID, products.price as price, count(*) as count , products.Title from cart INNER JOIN products ON cart.ProductID=products.ID WHERE cart.UserID=:uid GROUP BY cart.ProductID');
			$stmt->bindParam(":uid", $userid);
			$stmt->execute();
			$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
			$totalcount = 0;
			$totalprice = 0;
			$prod = 0;
			foreach($results as $itm){
				$totalcount += $itm['count'];
				$totalprice += $itm['count'] * $itm['price']; // Price here!
				$results[$prod]['prodtotal'] = $itm['count'] * $itm['price'];
				$prod += 1;
			}
			return array("content" => $results, "count" => $totalcount, "price" => $totalprice);
		}//Invalid uid
		return FALSE;
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

		$h = $this->create_pass($pass, $salt);
		$stmt->bindParam(":hash", $h);
		$stmt->bindParam(":salt", $salt);
		$stmt->execute();
		//$stmt->fetch();
		
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['last_logon'] = date('y-M-d');
		
		return TRUE;
	}

	function create_prod($img, $title, $price, $desc ){
		$stmt = $this->conn->prepare('INSERT INTO products (Image, Title, Description, Price, Visible) VALUES (:img,:title,:des,:prc,1)');
		$stmt->bindParam(":img", $img);
		$stmt->bindParam(":title", $title);
		$stmt->bindParam(":des", $desc);
		$stmt->bindParam(":prc", $price);
		$stmt->execute();
	}
}
?>