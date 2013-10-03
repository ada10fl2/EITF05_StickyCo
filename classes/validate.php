<?php 
	class Validate {
		const TOKEN1 = "mAOIyz5MPpm3RGg9fNrzHH6pKfWqa6LF";
		const TOKEN2 = "ls6FmlpeFPjuZitovdAOctGB6143JMa";
		
		public static function isLoggedIn($forceNewLogin) {
			if(session_status() != 2) { 
				session_start();
			}
		
			function logout(){
				$_SESSION = array();
				// If it's desired to kill the session, also delete the session cookie.
				if (ini_get("session.use_cookies")) {
					$params = session_get_cookie_params();
					setcookie(session_name(), '', time() - 42000,
						$params["path"], $params["domain"],
						$params["secure"], $params["httponly"]
					);
				}
				session_destroy();
				exit("<script>document.location='login.php'; //User not logged in </script>");
			}
			
			if(!isset($_SESSION['server_generated'])){
				session_regenerate_id();
				$_SESSION['server_generated'] = TRUE;
			}
			
			$uid = self::ifset($_SESSION['userid']);
			$hash = sha1($_SERVER['HTTP_USER_AGENT'].TOKEN1.$_SERVER['REMOTE_ADDR'].TOKEN2.$uid);
			
			if (!isset($_SESSION['HTTP_USER_AGENT'])) {
				$_SESSION['HTTP_USER_AGENT'] = $hash;
			} else if ($_SESSION['HTTP_USER_AGENT'] !== $hash && $forceNewLogin === TRUE) {
				logout();
			}
			
			if(!empty($uid)){ //User is logged in
				return TRUE;
			}
			
			if ($forceNewLogin === TRUE) {
				logout();
			}
			
			return FALSE;
		}
		
		
		public static function ifset(&$v) {
			return isset($v) ? $v : "";
		}
	
		public static function is_address($v) {
			if(preg_match("/^[\w \-,\.#()]{4,200}$/i",$v)) return TRUE;
			return FALSE;
		}
	
		public static function is_name($v){
			if(preg_match('/^[\w ]{3,45}$/i',$v)) return TRUE;
			return FALSE;
		}
		
		public static function is_username($v){ 
			if(preg_match('/^[\w]{4,45}$/i',$v)) return TRUE;
			return FALSE;
		}
		
		public static function is_creditcard($cc, $cv, $mm, $yy){ 
			$cc=preg_replace('/\D/', '', $cc);
			
			if(!preg_match("/^[\d]{3}$/", $cv)) return FALSE;
			if(!preg_match("/^[\d]{12,16}$/", $cc)) return FALSE;
			if(!preg_match("/^[\d]{2}$/", $yy)) return FALSE;
			if(!preg_match("/^([0][1-9])|([1][0-2])$/", $mm)) return FALSE;
			
			if(idate('y') > intval($yy)) return FALSE; 
			if(idate('m') > intval($mm) && idate('y') == intval($yy)) return FALSE;
			
			$ccLen=strlen($cc);
			$parity = $ccLen % 2;
			$sum = 0;
			for ($i = 0; $i < $ccLen; $i++) {
				$digit = $cc[$i];
				if ($i % 2 == $parity) { // for equal parities
					$digit *= 2;
					$digit -= $digit > 9 ? 9 : 0;
				}
				$sum += $digit;
			}
			return ($sum % 10 == 0) ? TRUE : FALSE;
		}
		
		public static function is_password($v){ 
			if(preg_match('/^[\w]{6,}$/i',$v)) return TRUE;
			return FALSE;
		}
		
		public static function is_POST(&$v){
			return $v['REQUEST_METHOD'] === "POST";
		}
	}
?>