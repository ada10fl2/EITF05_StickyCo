<?php

class db {
	private $conn;
	
	function __construct() {
		$user = "root";
		$pass = "I{BawnWW.@\Nz2U#uwX+{V&`eTVVp4k0N/x`RE,8^z(5ut+'Nob2a1!C;JbFe]_";
		$name = "webshop";
		$host = "78.73.132.182";
		$this->conn = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if(!defined("CODE_SALT")) 
			define("CODE_SALT", "939760F3ACEE1D02785A4CE834A98E0301FE92E4E77F7C48E0A7206B");
		if(!defined("PASSWORD_COST")) 
			define("PASSWORD_COST", 12 * pow(1.2, idate('y') - 13) ); 
			//Hard to tell right now but it look fessible: http://www.wolframalpha.com/input/?i=12+*+pow%281.2%2C+x+-+13%29
	}
	
	function get_products(){
		$stmt = $this->conn->prepare('SELECT * FROM products WHERE Visible=1');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function get_product($pid){
		if(is_numeric($pid)){	
			$stmt = $this->conn->prepare('SELECT * FROM products WHERE ID=:id');
			$stmt->bindParam(":id", $pid);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		return FALSE;
	}
	
	function logout() {
		if(session_status() != 2) { session_start(); }
		
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
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
	
	function create_pass($pass){
		return password_hash($pass.CODE_SALT, PASSWORD_BCRYPT, array('cost' => PASSWORD_COST));
	}
	
	function verify_pass($user_obj, $trypass){
		if (!password_verify($trypass.CODE_SALT, $user_obj['Password'])) { //Validate
			return FALSE;
		}
		if(password_needs_rehash($user_obj['Password'], PASSWORD_COST)){ // Ensure hash is update-to-date
			$new = $this->create_pass($trypass);
			$stmt = $this->conn->prepare('UPDATE users SET Password=:pass WHERE ID=:userid');
			$stmt->bindParam(":userid", $user_obj['ID']);
			$stmt->bindParam(":pass", $new);
			$stmt->execute();
		}
		return TRUE;
	}
	
	function verify_user($user, $trypass){
		$stmt = $this->conn->prepare('SELECT * FROM users WHERE Username=:user');
		$stmt->bindParam(":user", $user);
		$stmt->execute();
		if($stmt->rowCount() === 0){
			return FALSE;
		} else {
			$user_obj = $stmt->fetch(PDO::FETCH_ASSOC);
			if($this->verify_pass($user_obj, $trypass) === TRUE) {
				//SUCCESS
				if(session_status() != 2) { 
					session_start(); 
				}
				$_SESSION['user'] = $user;
				$_SESSION['userid'] = $user_obj['ID'];
				$_SESSION['time'] = date('c');
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
	
	function create_user($user, $pass, $first, $last, $adr){
		$exist = $this->conn->prepare('SELECT * from users WHERE Username=:user');
		$exist->bindParam(":user", $user);
		$exist->execute();
		
		if(count($exist->fetchAll()) > 0){ //Prevent duplicate user
			return FALSE;
		}
		
		$stmt = $this->conn->prepare('INSERT INTO users (Username, Password, FirstName, LastName, Address) VALUES (:user,:pass,:first,:last,:adr)');
		$stmt->bindParam(":user", $user);
		$stmt->bindParam(":first", $first);
		$stmt->bindParam(":last", $last);
		$stmt->bindParam(":adr", $adr);
		
		$h = $this->create_pass($pass);
		$stmt->bindParam(":pass", $h);
		$stmt->execute();
		//$stmt->fetch();
		
		if(session_status() != 2) { 
			session_start(); 
		}
		$_SESSION['user'] = $user;
		$_SESSION['last_logon'] = date('y-M-d');
		
		return TRUE;
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
			$stmt = $this->conn->prepare('SELECT cart.ProductID as ID, products.Image as Image,  products.price as price, count(*) as count , products.Title from cart INNER JOIN products ON cart.ProductID=products.ID WHERE cart.UserID=:uid GROUP BY cart.ProductID');
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
		return array("content" => array(), "count" => 0, "price" => 0);
	}
	
	function create_order($uid, $order, $first, $last, $adr){
		$stmt = $this->conn->prepare('INSERT INTO Orders (UserID, OrderContent, Firstname, Lastname, Address) VALUES (:user,:order,:first,:last,:adr)');
		$stmt->bindParam(":user", $uid);
		$stmt->bindParam(":first", $first);
		$stmt->bindParam(":last", $last);
		$stmt->bindParam(":adr", $adr);
		$stmt->bindParam(":order", $order);
		$stmt->execute();
		return TRUE;
	}

	function create_prod($img, $title, $price, $desc ){
		$stmt = $this->conn->prepare('INSERT INTO products (Image, Title, Description, Price, Visible) VALUES (:img,:title,:des,:prc,1)');
		$stmt->bindParam(":img", $img);
		$stmt->bindParam(":title", $title);
		$stmt->bindParam(":des", $desc);
		$stmt->bindParam(":prc", $price);
		$stmt->execute();
		return TRUE;
	}

	function remove_from_cart( $uid, $pid, $all){
		if (!empty($pid) && is_numeric($pid) && 
			!empty($uid) && is_numeric($uid)){
			
			if($all == FALSE){
				$stmt = $this->conn->prepare('DELETE from cart WHERE cart.UserID=:uid and cart.ProductID=:pid limit 1');
			}else{
				$stmt = $this->conn->prepare('DELETE from cart WHERE cart.UserID=:uid and cart.ProductID=:pid');
			}
			$stmt->bindParam(":uid", $uid);
			$stmt->bindParam(":pid", $pid);
			$stmt->execute();
			return $this->cart_get($uid);			
		}
		
		//Invalid uid or pid
		return FALSE;

	}

	function get_user($uid){
		if(!empty($uid) && is_numeric($uid)){
			$stmt = $this->conn->prepare('SELECT * FROM users WHERE ID=:uid');
			$stmt->bindParam(":uid", $uid);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		//Invalid uid
		return FALSE;
	}
}
?>