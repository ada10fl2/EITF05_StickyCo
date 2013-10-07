<?php
	session_start();
	header('Content-Type: application/json');
	$script = "";
	
	require_once('/classes/validate.php');
	$pid = Validate::ifset($_GET['pid']);
	$action = Validate::ifset($_GET['action']);
	
	require_once('/classes/db.php');
	$db = new db();
	
	if($action === "add"){
		if(is_numeric($pid)) {
			if(isset($_SESSION['userid'])){ //User is logged in
				$cart = $db->cart_add($_SESSION['userid'], $pid);
			} else {  //User is anonymous
				if(!isset($_SESSION['cart'])){
					$cart = $db->cart_get(null, $pid);
					$_SESSION['cart'] = $cart;
				}
				$cart = $_SESSION['cart'];
				$p = $db->get_product($pid);
				$found = FALSE;
				$price = 0;
				$count = 0;
				foreach($cart['content'] as &$item) { //Already added
					if($item['ProductID'] === $pid){
						$found = TRUE;
						$item['count'] = "".(intval($item['count']) + 1);
						$item['prodtotal'] = intval($item['prodtotal']) + intval($item['price']);
					}
					$price = $price + $item['prodtotal'];
					$count = $count + $item['count'];
					if($found === TRUE) break;
				}
				if($found === FALSE) { //Not added
					$cart['content'][]= array(
						"ProductID" => $pid,
						"Image" => $p['Image'],
						"price" => $p['Price'],
						"Title" => $p['Title'],
						"count" => "1",
						"prodtotal" => $p['Price']
					);
					$price = $p['Price'];
					$count = "1";
				}
				$cart['count'] = $count;
				$cart['price'] = $price;
				$_SESSION['cart'] = $cart;
			}
			echo json_encode($cart);
		}
	}
	
	if($action === "clear"){
		if(isset($_SESSION['userid'])){ //User is logged in
			$cart = $db->cart_clear($_SESSION['userid']);
		} else {  //User is anonymous
			$cart = $db->cart_get(null, $pid);
			$_SESSION['cart'] = $cart;
		}
		echo json_encode($cart);
	}
	
	if($action === "show"){
		if(isset($_SESSION['userid'])){
			$cart = $db->cart_get($_SESSION['userid'], $pid);
		} else {
			if(!isset($_SESSION['cart'])){
				$cart = $db->cart_get(null, $pid);
				$_SESSION['cart'] = $cart;
			}
			$cart = $_SESSION['cart'];
		}
		echo json_encode($cart);
	}
	
	if($action === "remove"){
		if(isset($_SESSION['userid'])){
			$cart = $db->remove_from_cart($_SESSION['userid'], $pid, FALSE);
		} else {
			if(!isset($_SESSION['cart'])){
				$cart = $db->cart_get(null, $pid);
				$_SESSION['cart'] = $cart;
			}
			$cart = $_SESSION['cart'];
			$p = $db->get_product($pid);
			$found = FALSE;
			$empty = FALSE;
			$price = 0;
			$count = 0;
			$idx = 0;
			foreach($cart['content'] as &$item) { //Already added
				if($item['ProductID'] === $pid){
					$found = TRUE;
					$item['count'] = intval($item['count']) - 1;
					$item['prodtotal'] = intval($item['prodtotal']) - intval($item['price']);
					if ($item['count'] <= 0) {
						unset($cart['content'][$idx]);
					}
				}
				$price = $price + $item['prodtotal'];
				$count = $count + $item['count'];
				$idx++;
				if ($found === TRUE) {
					break;
				}
			}
			$cart['count'] = $count;
			$cart['price'] = $price;
			$_SESSION['cart'] = $cart;
		}
		echo json_encode($cart);
	}
	
	if($action === "removeall"){
		$json = $db->remove_from_cart($_SESSION['userid'], $pid, TRUE);
		echo json_encode($json);
	}
?>
