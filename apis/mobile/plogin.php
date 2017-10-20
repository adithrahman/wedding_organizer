<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once '../database.php';
require_once '../security.php';
$db = new database();
$sec = new security();
$mcrypt = new MCrypt();

// json response array
$response = array("error" => FALSE);

if (isset($_POST['email']) && isset($_POST['password'])) {

    // receiving the post params
    $email = $sec->input($_POST['email']);
    $password = $mcrypt->decrypt($sec->input($_POST['password']));

    // get the user by email and password
    $user = $db->getUserByEmailAndPassword($email, $password);

    if ($user != false) {
        // use is found
        $response["error"] = FALSE;
        $response["parent"]["email"] = $user["email"];
        $response["parent"]["name"] = $user["name"];
        $response["parent"]["phone"] = $user["phone"];
        $response["parent"]["created"] = $user["created"];
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>
