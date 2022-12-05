<?php
require('./include/headers.php');
require_once('./include/functions.php');

$db = openSQLite();

if (isset($_GET["action"]) && isset($_SESSION['username'])) {
    $action = $_GET["action"];

    switch ($action) {
        case "create":
            $requiredInfo = array('orderId','customerId','orderItems');
            $err = 0;

            foreach ($requiredInfo as $key) {
                if(!isset($_POST[$key])) {
                    $err++;                 //loopataan tarvittavien tietojen läpi ja tarkistetaan että ne on asetettu
                }
            }

            if ($err == 0) {

                $checkOrderId = "select * from ORDERS where (orderId = '" . $_POST['orderId'] . "')";
                $checkOrderId = selectAsJson($db, $checkOrderId);
                
                if (count($checkOrderId) != 0) {
                    http_response_code(409);
                    echo "This order has already been placed";
                    return;
                }

                $sql = "INSERT INTO ORDERS(orderId,customerId,orderStatus,orderDate) VALUES('";
                $sql .= $_POST['orderId'] . "','";
                $sql .= $_POST['customerId'] . "','";
                $sql .= "created',";
                $sql .= "datetime())";

                $orderItems = explode(',', $_POST["orderItems"]);
                foreach ($orderItems as $key) {
                    $sql_details = "";
                    $productId = str_replace(',', '', $key);
                    $sql_details .= "INSERT INTO ORDER_ITEMS(orderId,productId) VALUES('";
                    $sql_details .= $_POST['orderId'] . "','";
                    $sql_details .= $productId . "')";   
                    try {
                        executeInsert($db, $sql_details);
                    } catch (Exception $e) {
                        echo "Failed";
                        print_r($e);
                        return;
                    }  
                }
                
                try {
                    executeInsert($db, $sql);
                    echo "Order placed";
                    http_response_code(200);
                } catch (Exception $e) {
                    echo "Failed";
                    print_r($e);
                    return;
                }
            } else {
                http_response_code(400);
                echo "Missing information";
                return;
            }
        break;

        case "cancel":
            if (isset($_POST['orderId'])) {
                $getOrderCustomerId = "select customerId from ORDERS where (orderId = '" . $_POST['orderId'] . "')";
                $getOrderCustomerId = selectAsJson($db, $getOrderCustomerId);

                $getOrderCustomerName = "select username from USER where (customerId = '" . $getOrderCustomerId[0]["customerId"] . "')";
                $getOrderCustomerName = selectAsJson($db, $getOrderCustomerName);

                session_start();
                if ($getOrderCustomerName[0]["username"] == $_SESSION['username']) {

                    $getOrderStatus = "select orderStatus from ORDERS where (orderId = '" . $_POST['orderId'] . "')";
                    $getOrderStatus = selectAsJson($db, $getOrderStatus);

                    if ($getOrderStatus[0]["orderStatus"] != 'shipped') {
                        $sql = "UPDATE ORDERS SET orderStatus = 'cancelled' WHERE orderId = '" . $_POST['orderId'] . "'";
                        try {
                            executeInsert($db, $sql);
                            echo "Order cancelled";
                        } catch (Exception $e) {
                            echo "Failed";
                            print_r($e);
                            return;
                        }
                    } else {
                        http_response_code(409);
                        echo "Can't cancel because the order has already been shipped";
                        return;
                    }
                }
            } else {
                http_response_code(400);
                echo "No order id";
                return;
            }
        break;
    }
} else {
    http_response_code(400);
    echo "No action or missing session data.";
    return;
}