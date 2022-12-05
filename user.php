<?php
	require('./include/headers.php');
	require_once('./include/functions.php');

    $db = openSQLite();

	if (isset($_GET["action"])) {
		$action = $_GET["action"];
		
		switch ($action) {
			case "getUser":
            	if (isset($_GET["userName"])) {
					session_start();
					if ($_GET["userName"] == $_SESSION["username"]) {
           			$query = "SELECT username, password, fname, lname, address, postalcode, city, email, phone FROM USER";
                  	$query = $query . " WHERE username = '" . $_SESSION["username"] . "'";
					} else {
						http_response_code(403);
						echo "Forbidden, provided username doesn't match session data";
						return;
					}
                } else {
					http_response_code(400);
					echo "Missing argument";
					return;
				} try {
					$json = selectAsJson($db, $query);
					$json = json_encode($json);
					echo $json;
				} catch (PDOException $pdoex) {
					returnError($pdoex);
				}
            	break;

			case "loginSession":
				session_start();
				if (isset($_SESSION['username'])) {
					http_response_code(200);
					echo $_SESSION['username'];
					return;
				}
				break;
                  
			case "login":
				if (
					isset($_POST["username"]) &&
					isset($_POST['password'])
				) {
					$password = urlencode($_POST['password']);
					$username = urlencode($_POST['username']);
					
					$query = "SELECT password from USER where username = '" . $username ."'";
					$hash = selectAsJson($db, $query);
					if (count($hash) == 1) {
						$hash = $hash[0]["password"];
						if (password_verify($_POST['password'], $hash)) {
							session_start();
							$_SESSION['username'] = $_POST["username"];
							$data["username"] = $_POST["username"];
							$data["loginSuccess"] = true;
							$data = json_encode($data);
							echo $data;
							http_response_code(200);
						} else {
							echo header('HTTP/1.1 500 Internal server Error');
							echo "Internal server error";
						}
					} else {
						echo "Invalid username or password";
					}
				} else {
					echo "Login failed, missing information";
				}
				break;
			case "logout":
				session_start();
				unset($_SESSION["username"]);
				http_response_code(200);
				echo "Logged out";
        } 
    } else {
		echo "no action";
	}
?>