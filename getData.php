<?php
	require('./include/headers.php');
	require_once 'include/functions.php';
	
	global $json;
	
	$db = openSQLite();
	
	if (isset($_GET["action"])) {
		$action = $_GET["action"];
		
		switch ($action) {
			case "getProducts":
				$query = 'select * from PRODUCT';	//aina jos action=getProducts
				if (
					isset($_GET["categoriesToGet"]) ||
					isset($_GET["searchTerm"])
				) {
					$query = $query . ' WHERE (';
					
					if (isset($_GET["searchTerm"])) {
						$query = $query . "(productName LIKE '%" . $_GET["searchTerm"] . "%')";
					}
					
					if (isset($_GET["categoriesToGet"]) && (isset($_GET["searchTerm"]))) {
						$query = $query . " AND ";
					}
					if (isset($_GET["categoriesToGet"])) {
						$query = $query . "categoryId IN (" . $_GET["categoriesToGet"] . ")" ;
					}
					$query = $query . ' AND active = true';
					$query = $query . ')';
				} else if (isset($_GET["productIds"])) {	//käytetään esim ostoskorin tietojen hakemiseen
					$query = $query . ' WHERE (';
					$query = $query . "productId IN (" . $_GET["productIds"] . "))" ;
				} else {
					$query = $query . ' WHERE (active = true)';

				}
			break;
			
			case "getCategoryProducts":
				if (isset($_GET["category"])) {
					$query = 'select * from PRODUCT where categoryId in ' . $_GET["category"] . 'order by categoryId';     // = > in
				} else {
					http_response_code(400);
					echo "Missing argument";
					return;
				}
			break;
			
			case "getCategories":	//haetaan kaikki kategoriat, esimerkiksi tuotefiltterin näyttämiseksi
				$query = 'select * from CATEGORY';
			break;

			case "getProductDetails":
				if (isset($_GET["productId"])) {	//yksittäisen tuotteen tietojen hakeminen
					$query = 'select * from PRODUCT where productId = '. $_GET["productId"]; 
				} else {
					http_response_code(400);
					echo "Missing argument";
					return;
				}
			break;
			
		} try {
			$json = selectAsJson($db, $query);
			$json = json_encode($json);
			echo $json;
		} catch (PDOException $pdoex) {
			returnError($pdoex);
		}
	} else {
		http_response_code(400);
		echo "no action";
	}
?>