<?php
	require('./include/headers.php');
	require_once('./include/functions.php');

    $db = openSQLite();

    session_start();

    if (isset($_SESSION["username"])) {

        if (checkForFunnyStuff($_SESSION["username"])) {
            echo json_encode(["Special characters are not allowed", false, 'specialCharError']);
            http_response_code(400);
            return;
        }

        $getAdminLevel = "select level from ADMIN where (username = '" . $_SESSION["username"] . "')";
        $getAdminLevel = selectAsJson($db, $getAdminLevel);

        if (count($getAdminLevel) > 0) {
            //jos tarvitaan adminille eri tasoja, voidaan määrittää mitä voivat tehdä milläkin tasolla esim 1-3
            echo "Admin level: " . $getAdminLevel[0]["level"] . PHP_EOL;
            if (isset($_GET["action"])) {
                $action = $_GET["action"];
                switch ($action) {
                    case "addProduct":
                        $requiredInfo = array('categoryId','productName','price','description','active');
                        $err = 0;
                        
                        foreach ($requiredInfo as $key) {
                            if(!isset($_POST[$key])) {
                                $err++;
                            }
                        }

                        if ($err != 0) {
                            http_response_code(400);
                            echo "Missing information";
                            return;
                        } else {
                            $sql = "INSERT INTO PRODUCT(categoryId,productName,price,description,timeStamp,active) VALUES ('";
                            $sql .= $_POST["categoryId"] . "','";
                            $sql .= $_POST["productName"] . "','";
                            $sql .= $_POST["price"] . "','";
                            $sql .= $_POST["description"] . "',";
                            $sql .= "datetime(),'";
                            $sql .= $_POST["active"] . "')";
                            try {
                                executeInsert($db, $sql);
                                echo "Product added: " . $_POST["productName"];
                                http_response_code(200);
                            } catch (Exception $e) {
                                echo "Failed";
                                print_r($e);
                            }
                        }
                    break;

                    case "addCategory":
                        $requiredInfo = array('categoryName');
                        $err = 0;
                        
                        foreach ($requiredInfo as $key) {
                            if(!isset($_POST[$key])) {
                                $err++;
                            }
                        }

                        if ($err != 0) {
                            http_response_code(400);
                            echo "Missing information";
                            return;
                        } else {
                            $checkCategoryExists = "select * from CATEGORY where (categoryName = '" . $_POST["categoryName"] . "')";
                            $checkCategoryExists = selectAsJson($db, $checkCategoryExists);
                            if (count($checkCategoryExists) != 0) {
                                http_response_code(409);
                                echo "Category already exists as id " . $checkCategoryExists[0]["categoryId"];
                                return;
                            }
                            $sql = "INSERT INTO CATEGORY(categoryName) VALUES ('";
                            $sql .= $_POST["categoryName"] . "')";
                            try {
                                executeInsert($db, $sql);
                                echo "Category added";
                                http_response_code(200);
                            } catch (Exception $e) {
                                echo "Failed";
                                print_r($e);
                            }
                        }
                    break;
                }
            }
       } else {
            http_response_code(401);
            echo "Unauthorized. No permission to perform action.";
       }
    } else {
        http_response_code(401);
        echo "Unauthorized. No session data. Please login first.";
    }