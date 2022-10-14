<?php 

require 'globals.include.php';
require 'config.include.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $filename = "output_" . date('Y-m-d') . ".csv";
    $csv_file = fopen('php://memory', 'w');
    fputcsv($csv_file, 
        array('ID', 'Username', 'Country', 'Terrain', 'Terrain Filename', 'Script', 
            'Script Hash', 'Actor', 'Actor Filename', 'Actor Hash', 'Average FPS', 'RoR version', 
            'Split times', 'Lap time', 'Date', 'Race Name', 'Race version'), ",");
    
    $db = connect_mysqli_or_die($config);
    $sql_stmt = "SELECT * FROM race";
    $result = $db->query($sql_stmt);
    if ($result === false) {
        header("HTTP/1.1 500 Internal Server Error");
        log_error("Database error: {$db->error}");
        log_error("Couldn't complete request for GUID: {$guid}, IP: {$ipv4}, with method: {$_SERVER['REQUEST_METHOD']} due to SQL error");
        die("Server error, cannot read database");
    }

    $rows = array();
    while($row = $result->fetch_assoc()) {
        fputcsv($csv_file, array($row["id"], $row["username"], $row["country"], $row["terrain"], $row["terrain_filename"],
            $row["script"], $row["script_hash"], $row["actor"], $row["actor_filename"], $row["actor_hash"], $row["average_fps"],
            $row["ror_version"], $row["split_times"], $row["lap_time"], $row["date"], $row["race_name"], $row["race_version"]), ",");
    }

    fseek($csv_file, 0);

    log_info("Sending {$filename} to IP: {$ipv4}, GUID: {$guid}");

    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 

    fpassthru($csv_file);
}