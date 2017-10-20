<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once '../database.php';
require_once '../security.php';
$db = new database();
$sec = new security();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {

    // receiving the post params
    $name = $sec->input($_POST['name']);
    $email = $sec->input($_POST['email']);
    $password = $sec->input($_POST['password']);

    // check if user is already existed with the same email
    if ($db->isUserExisted($email)) {
        // user already existed
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $email;
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storeUser($name, $email, $password);
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["parent"]["name"] = $user["name"];
            $response["parent"]["email"] = $user["email"];
            $response["parent"]["phone"] = $user["phone"];
            $response["parent"]["created"] = $user["created"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}
?>
