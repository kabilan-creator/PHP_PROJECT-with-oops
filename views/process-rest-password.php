<?php
require "../config/database.php";
require '../config/helper.php';




$token=$_POST["token"];
$password=$_POST["password"];



// if (isset($_GET['token'])) {
//     echo "Token: " . htmlspecialchars($_GET['token']);
// } else {
//     echo "Token not provided.";
// }

$token_hash=hash("sha256", $token);



$sql = "SELECT*FROM admin
        WHERE reset_token_hash=?";

$stmt = $db->prepare($sql);

if ($stmt === false) {
    // Display the error if statement preparation fails
    die("SQL error: " . $db->error);
}
$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result=$stmt->get_result();

$user = $result->fetch_assoc();

if ($user== null) {
    die("token not found");
}
if (strtotime($user["reset_token_expires"]) <= time()) {
    die("token has expired");
}
// if(strlen($_POST["password"])<8){
//     die("password must be at least 8 char");
// }
// if(! preg_match("/[a-z]/i", $_POST["password"])){
//     die("password must contain at least one letter");
// }

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE admin
        SET APASS = ?,
            reset_token_hash = NULL,
            reset_token_expires = NULL
        WHERE AID =? ";  

$stmt = mysqli_prepare($db,$sql);

if ($stmt === false) {
    echo mysqli_error($db);
}
$stmt->bind_param("ss", $password_hash,$user["AID"]);

$stmt->execute();
header('Location: ../views/auth/login.php');// Redirect to dashboard
                exit();


