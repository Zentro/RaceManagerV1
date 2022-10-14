<?php
session_start();
if(!isset($_SESSION['authorized'])) {
    header('Location: admin-login.php');
}

require 'globals.include.php';
require 'config.include.php';

?>
<!DOCTYPE html>
<html lang=en>
<head>
    <title>Admin dashboard</title>
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
        <h3>Hello <?php echo $config['admin']['username']; ?>!</h3>
        <h4>For the event between <?php echo date("F j, Y, g:i a", $config['start_race_submissions']); ?> and <?php echo date("F j, Y, g:i a", $config['end_race_submissions']); ?></h4>
        <p>If you see nothing, this just means there's no data yet.</p>
        <div class="w3-row">
            <div class="w3-col m4 l3">
                <h5>A total of 1 disqualification(s)</h5>
            </div>
            <div class="w3-col m4 l3">
                <h5>A total of 6 submissions (including those disqualified)</h5>
            </div>
            <div class="w3-col m4 l3">
                <h5>The average lap time was 12.1231 s</h5>
            </div>
        </div>
        <h3>Below you will find the data for the event with the associated admin action</h3>
    </div>
    <?php include 'footer.include.php'; ?>
</div>