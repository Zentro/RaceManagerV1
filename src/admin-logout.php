<?php
session_start();
if(!isset($_SESSION['authorized'])) {
    header('Location: admin-login.php');
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
// Leave no trace
header("refresh:3;url=admin-login.php"); 
?>
<!DOCTYPE html>
<html lang=en>
<head>
    <title>Please wait...</title>
    <meta charset=utf-8>
    <meta http-equiv=x-ua-compatible content="ie=edge">
    <meta name=viewport content="width=device-width, initial-scale=1"/>
    <meta name=robots content="noindex, nofollow">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="w3-container w3-center">
    <h1>Please wait, you're being logged out.</h1><br>
    <img src="spinner.gif">
</div>
</body>
</html>