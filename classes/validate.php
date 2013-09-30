<?php 
	class Validate {
		
		public static function noUserRedirect($redirect) {
			session_start();
			if(!isset($_SESSION['userid'])){ //User not logged in
				exit("<script>document.location='$redirect'; //User not logged in </script>");
			}
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
			if(preg_match('/^[\w]{4,}$/i',$v)) return TRUE;
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