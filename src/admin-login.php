<?php
session_start();
if(isset($_SESSION['authorized'])) {
    header('Location: admin-view.php');
}

require 'globals.include.php';
require 'config.include.php';

$is_authorized = true;
$failed_attempt = false;
$ip = get_ipv4();

log_warn("Request for admin login from {$ip}");

if(isset($_POST['submit'])) {
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];

    if($uname === $config['admin']['username'] && $pass === $config['admin']['password']) {
        $_SESSION['authorized'] = true;
        log_info("Admin login from {$ip}");
        header('Location: admin-view.php');
    } else {
        log_warn("Failed admin login from {$ip}");
        $failed_attempt = true;
    }
}

?>
<!DOCTYPE html>
<html lang=en>
<head>
    <title>Admin login</title>
    <meta charset=utf-8>
    <meta http-equiv=x-ua-compatible content="ie=edge">
    <meta name=viewport content="width=device-width, initial-scale=1"/>
    <meta name=robots content="noindex, nofollow">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <?php include 'navbar.include.php'; ?>
    <div class="w3-container w3-padding-64">
    <?php if($failed_attempt) : ?>
        <div class="w3-panel w3-red">
            <p>Those credentials do not match. This failed admin login attempt has been logged.</p>
        </div>
    <?php endif; ?>
    <?php if($is_authorized) : ?>
        <form action="admin-login.php" method="post" class="w3-quarter">
            <div class="w3-section">
                <label><b>Username</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Enter Username" name="uname" required>
                <label><b>Password</b></label>
                <input class="w3-input w3-border" type="password" placeholder="Enter Password" name="pass" required>
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="submit">Login</button>
                <div class="w3-panel w3-pale-green">
                    <p><i class="fa-regular fa-camera-security"></i> Your IP (<?php echo get_ipv4(); ?>) is being logged.</p>
                </div>
            </div>
        </form>
    <?php else : ?>
        <div class="w3-panel w3-red">
            <p><i class="fa-regular fa-camera-security"></i> You are not authorized to see this page. Your IP (<?php echo get_ipv4(); ?>) has been logged.</p>
        </div>
    <?php endif; ?>
    </div>
    <?php include 'footer.include.php'; ?>
</body>