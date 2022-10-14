<div class="w3-top">
    <div class="w3-bar w3-black w3-top w3-left-align w3-large">
        <a href="index.php" class="w3-bar-item w3-button">Home</a>
        <a href="get-rules.php" class="w3-bar-item w3-button">Rules</a>
        <?php if (isset($_SESSION['authorized'])) { ?>
            <a href="admin-view.php" class="w3-bar-item w3-button">Admin dashboard</a>
            <a href="admin-logout.php" class="w3-bar-item w3-button">Admin logout</a>
        <?php } else { ?>
            <a href="admin-login.php" class="w3-bar-item w3-button">Admin login</a>
        <?php } ?>
    </div>
</div>