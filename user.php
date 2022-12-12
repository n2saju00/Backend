<?php
	require('./include/headers.php');
	require_once('./include/functions.php');

    $db = openSQLite();

	foreach ($_GET as $key) {
		if (checkForFunnyStuff($key)) {
			echo $key;
			echo json_encode(["Special characters are not allowed", false, 'specialCharError']);
			http_response_code(400);
			return;
		}
	}

	foreach ($_POST as $key) {
		if (
			checkForFunnyStuff($key) &&
			$key != $_POST["password"]	//salasanassa saa olla erikoismerkkejä, enkoodataan myöhemmin
			) {
			echo json_encode(["Special characters are not allowed", false, 'specialCharError']);
			http_response_code(400);
			return;
		}
	}

	if (isset($_GET["action"])) {
		$action = $_GET["action"];
		
		switch ($action) {
			case "getUser":
            	if (isset($_GET["userName"])) {
					session_start();
					if ($_GET["userName"] == $_SESSION["username"]) {
						$query = "SELECT username, password, fname, lname, address, postalcode, city, email, phone FROM USER";
						$query = $query . " WHERE username = '" . urlencode($_SESSION["username"]) . "'";
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
					echo "Logged in through session: " . $_SESSION['username'];
					return;
				} else {
					echo "Not logged in";
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
						if (password_verify(urlencode($_POST['password']), $hash)) {
							session_start();
							$_SESSION['username'] = $_POST["username"];
							$data["username"] = $_POST["username"];
							$data["loginSuccess"] = true;
							$data = json_encode($data);
							echo $data;
							http_response_code(200);
						} else {
							http_response_code(401);
							echo "Invalid username or password";
							return;
						}
					} else {
						http_response_code(401);
						echo "Invalid username or password";
						return;
					}
				} else {
					http_response_code(400);
					echo "Login failed, missing information";
					return;
				}
				break;
			case "logout":
				session_start();
				unset($_SESSION["username"]);
				http_response_code(200);
				echo "Logged out";
        } 
    } else {
		http_response_code(200);
		echo "no action";
		return;
	}
?>