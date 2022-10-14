<?php
session_start();

require 'globals.include.php';
require 'config.include.php';

$has_results = false;
$any_winners = false;

$winner_uname = "";
$winner_time = "";

$db_config = $config['database'];
$db = new MySQLi($db_config['host'], $db_config['user'], $db_config['password'], $db_config['name']);
if ($db->connect_errno != 0) {
    log_error("Error while trying to connect to the database: {$mysqli->error}");
    exit("Sorry, a database error occurred.");
} else {
    $_stmt_show_disqualifications = ''; // todo
    $_stmt_order_by_disqualifications = '';
    // Do not share the IPs or UUIDs
    // Only share what we need to share to the table
    $sql_stmt = "
        SELECT 
            `username`,
            `actor`,
            `terrain`, 
            `lap_time`,
            `disqualified`
        FROM
            race
        ORDER BY
            `lap_time`
    ";

    $table_data = $db->query($sql_stmt);
    if ($table_data === false) {
        log_error("Database error: {$db->error}");
        exit("Sorry, a database error occurred.");
    } else if ($table_data->num_rows == 0) {
        $has_results = false;
    } else {
        // Sort by lap time and set their positions
        $has_results = true;
        while($row = $table_data->fetch_assoc()) {
            if($row['disqualified'] == 0) {
                // This is our winner
                $any_winners = true;
                $winner_uname = $row['username'];
                $winner_time = $row['lap_time'];
                break;
            }
            continue;
        }
    }
}

// Do we have a race/event ongoing?
$is_ongoing = false;
$allow_submission = false;
$is_finished = false;

$allow_submission = $config['allow_race_submissions'];

$t = time();
if ($t >= $config['start_race_submissions'] && $t <= $config['end_race_submissions']) {
    $is_ongoing = true;
}

if ($t >= $config['end_race_submissions']) {
    $is_finished = true;
}

?>
<!DOCTYPE html>
<html lang=en>
<head>
    <title>Race Submissions</title>
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
        <div class="w3-panel w3-pale-blue w3-leftbar w3-rightbar w3-border-blue">
            <h4>You need to download and run the anti-cheat script <div style="color:red;display: inline-block;"><strong>BEFORE</strong></div> your race!!!</h4>
            <p>If you do not run the anti-cheat script your races will go nowhere.</p>
        </div>
        <?php if(!$allow_submission) : ?>
        <div class="w3-panel w3-red">
            <h3>Race submissions are currently <strong>CLOSED</strong>.</h3>
            <p>No race submissions are being taken at this time.</p>
        </div> 
        <?php elseif(!$is_ongoing && !$is_finished): ?>
        <div class="w3-panel w3-yellow">
            <h3>Race submissions will open at <?php echo date("F j, Y, g:i a", $config['start_race_submissions']); ?> and close at <?php echo date("F j, Y, g:i a", $config['end_race_submissions']); ?>.</h3>
            <p><strong>Time timezone is based on the server's timezone!!!</strong> No race submissions will be accepted before or after this time.</p>
        </div> 
        <?php elseif($is_finished && $has_results): ?>
        <div class="w3-panel w3-blue">
            <h3>The race finished on <?php echo date("F j, Y, g:i a", $config['end_race_submissions']); ?>.</h3>
            <p>See the results of the race below. <div style="background:yellow;display: inline-block;color:black;"><strong>No race submissions are being accepted at this time!</strong></div></p>
        </div>    
        <?php elseif(!$is_finished && $is_ongoing) : ?>
        <div class="w3-panel w3-green">
            <h3>Race submissions have started!! Go Go Go!!</h3>
            <p>Race submissions will close at <?php echo date("F j, Y, g:i a", $config['end_race_submissions']); ?>! Good luck!</p>
        </div>
        <?php else : ?>
        <div class="w3-panel w3-blue">
            <h3>The Admin has not scheduled any races yet.</h3>
            <p>Check back later.</p>
        </div>
        <?php endif; ?>

        <div class="w3-center">
            <?php if(isset($config['terrain'])) { ?>
                <h1>This race is using the <strong><?php echo $config['terrain']; ?></strong> terrain.</h1>
            <?php } ?>

            <?php if(!empty($config['track-marshals'])) { ?>
                <h4>The managers of this race are: 
                    <?php foreach($config['track-marshals'] as $val) { ?>
                        <?php echo $val; ?>
                    <?php } ?>
                </h4>
            <?php } ?>

            <?php if(!$has_results || !$allow_submission) : ?>
            <h3>The race has not yet started or the Admin has disabled race submissions.</h3>
            <?php elseif(!$is_finished && $is_ongoing && $any_winners) : ?>
            <h3><?php echo $winner_uname; ?> is currently in <i class="fa-solid fa-crown"></i> 1st place with a time of <?php echo $winner_time; ?>.</h3>
            <?php elseif($is_finished && $any_winners) : ?>
            <h3 style="background:#4CAF50;display: inline-block;color:white;"><?php echo $winner_uname; ?> won the race in <i class="fa-solid fa-crown"></i> 1st place with a time of <?php echo $winner_time; ?>.</h3>
            <?php elseif(!$any_winners) : ?>
                <h3 style="background:red;display: inline-block;">There are no declared winners.</h3>
            <?php endif; ?>
        </div>

        <?php if(!$has_results || !$allow_submission) : ?>
        <h4>No data, yet.</h4>
        <?php else : ?>
        <table class="w3-table-all w3-hoverable w3-striped w3-bordered">
            <tr class="w3-blue">
                <th>Rank/Position</th>
                <th>Player</th>
                <th>Time</th>
            </tr>
            <?php $position = 0; ?>
            <?php foreach($table_data as $row) { ?>
                <?php if ($row['disqualified'] != 0) { ?>
                <tr class="w3-red">
                    <td><i class="fa-solid fa-x"></i></td>
                    <td><?php echo $row['username']; ?></td>
                    <td>DISQUALIFIED</td>
                <?php } else { ?>
                <?php $position = $position + 1; ?>
                <tr>
                    <td><?php echo $position ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['lap_time']; ?></td>
                <?php } ?>
                </tr>
            <?php } ?>
            </table>
        </table>
        <a href="do-csv-download.php" class="w3-button w3-green">Download this race as a CSV</a>
        <?php endif; ?>
    </div>
    <?php include 'footer.include.php'; ?>
</body>
</html>