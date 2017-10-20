<?php

require_once '../database.php';
require_once '../security.php';
$db = new database();
$sec = new security();

$jsonpost = file_get_contents("php://input");

if ($jsonpost) {
    //get the data
    $request = json_decode($jsonpost);
    $email = $sec->input($request->email);
    $name = $sec->input($request->name);
    $phone = $sec->input($request->phone);
    $password = $sec->input($request->password);


        // check if user is already existed with the same email
        if ($db->isUserExisted($email)) {
            // user already existed
            echo "0";
        } else {
            // create a new user
            $user = $db->storeUser($name, $email, $phone, $password);
            if ($user) {
                // user stored successfully
                echo "1";
            } else {
                // user failed to store
                echo "-1";
            }
        }
}
?>
