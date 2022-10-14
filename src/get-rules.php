<?php
session_start();

require 'globals.include.php';
require 'config.include.php';

$published_rules = $config['rules'];

?>
<!DOCTYPE html>
<html lang=en>
<head>
    <title>Rules</title>
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
        <h3>These rules have been laid out by the Admin:</h3>
        <ol>
        <?php foreach($published_rules as $rule) { ?>
            <li><?=$rule?></li>
        <?php } ?>
        </ol>
        <p style="background:red;color:white;"><strong>
        <?php if(isset($config['terrain'])) { ?>
            You MUST USE the <?php echo $config['terrain']; ?> terrain none other. 
        <?php } if(!empty($config['allowed-actors'])) { ?>
            You CANNOT modify your truck file
        <?php } if(isset($config['supported_ror_versions'])) { ?>
            You MUST be using Rigs of Rods <?php echo $config['supported_ror_versions']; ?>, any previous version WILL NOT BE ALLOWED. 
        <?php } if(!empty($config['player-whitelist'])) { ?>
            You MUST be WHITELISTED for your race to be submitted.
        <?php } ?>
        </strong></p>

        <?php if(!empty($config['allowed-actors'])) { ?>
        <h3>The following vehicles will be allowed in a race</h3>
        <ul>
            <?php foreach($config['allowed-actors'] as $key => $val) { ?>
            <li><?php echo $key; ?></li>
            <?php } ?>
        </ul>
        <?php } ?>

    </div>
    <?php include 'footer.include.php'; ?>
</body>