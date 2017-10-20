<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once '../database.php';
require_once '../security.php';
$db = new database();
$sec = new security();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['parent']) && isset($_POST['name']) &&
    isset($_POST['device_id']) && isset($_POST['device_name'])) {

    // receiving the post params
    $parent = $sec->input($_POST['parent']);
    $name = $sec->input($_POST['name']);
    $device_id = $sec->input($_POST['device_id']);
    $device_name = $sec->input($_POST['device_name']);

    // check if child is already existed with the same parent, name and device_id
    if ($db->isChildExisted($parent,$name,$device_id)) {
        // child already registered
        $response["error"] = TRUE;
        $response["error_msg"] = "Your child already existed with " . $name;
        echo json_encode($response);
    } else {

        if ($db->checkNumUserChilds($parent) > 0) {

            // check premium user
            if ($db->checkPremiumUser($parent)) {
                // store multiple new child for user
                $user = $db->storeChild($parent,$name,$device_id,$device_name);
                if ($user) {
                    // user stored successfully
                    $response["error"] = FALSE;
                    $response["child"]["parent"] = $user["parent"];
                    $response["child"]["name"] = $user["name"];
                    $response["child"]["device_id"] = $user["device_id"];
                    $response["child"]["device_name"] = $user["device_name"];
                    echo json_encode($response);
                } else {
                    // user failed to store
                    $response["error"] = TRUE;
                    $response["error_msg"] = "Error store child in registration!";
                    //$response["user"] = $user;
                    echo json_encode($response);
                }
            } else {
                // user failed to store
                $response["error"] = TRUE;
                $response["error_msg"] = "Only registered account can control multiple device!";
                //$response["user"] = $user;
                echo json_encode($response);
            }
        } else if ($db->checkNumUserChilds($parent) == 0) {
            // store new child for user
            $user = $db->storeChild($parent,$name,$device_id,$device_name);
            if ($user) {
                // user stored successfully
                $response["error"] = FALSE;
                $response["child"]["parent"] = $user["parent"];
                $response["child"]["name"] = $user["name"];
                $response["child"]["device_id"] = $user["device_id"];
                $response["child"]["device_name"] = $user["device_name"];
                echo json_encode($response);
            } else {
                // user failed to store
                $response["error"] = TRUE;
                $response["error_msg"] = "Error store child in registration!";
                //$response["user"] = $user;
                echo json_encode($response);
            }
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Error check user child in registration!";
            //$response["user"] = $user;
            echo json_encode($response);
        }

    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (parent, name, device_id and device_name) is missing!";
    echo json_encode($response);
}
?>
