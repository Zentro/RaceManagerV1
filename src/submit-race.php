<?php
/**
 * PHP version 8.2
 */

require 'globals.include.php';
require 'config.include.php';

error_reporting(-1); // For debugging this horror

function generate_guid() { // Thanks to https://www.php.net/manual/en/function.com-create-guid.php
    if (function_exists('com_create_guid') === true) { // Windows
        return com_create_guid();
    }

    else if (function_exists('openssl_random_pseudo_bytes') === true) { // Linux
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // Fallback if neither functions exist in PHP
    mt_srand((double)microtime() * 10000);
    $charid = strtolower(md5(uniqid(rand(), true)));
    $hyphen = chr(45);                  // "-"
    $lbrace = $trim ? "" : chr(123);    // "{"
    $rbrace = $trim ? "" : chr(125);    // "}"
    $guidv4 = $lbrace.
              substr($charid,  0,  8).$hyphen.
              substr($charid,  8,  4).$hyphen.
              substr($charid, 12,  4).$hyphen.
              substr($charid, 16,  4).$hyphen.
              substr($charid, 20, 12).
              $rbrace;
    return $guidv4;    
}

function is_player_whitelisted($username) {
    if (!isset($config['player-whitelist'])) { // No whitelist
        return true;
    }
    if (in_array($username, $config['player-whitelist'])) {
        return true;
    }
    return false;
}

// The GUID can be used to associate a race submission with a specific HTTP request
$guid = generate_guid();
// Get the IP used in the request and associate it with a race submission
$ipv4 = get_ipv4();

log_info("New request, method: {$_SERVER['REQUEST_METHOD']}, GUID: {$guid}, IP: {$ipv4}");

// Check IP blacklist
if (in_array($ipv4, $config['ip-blacklist'])) {
    log_warn("Blacklisted IP: {$ipv4} tried to submit a race");
    die_json(403, 'This IP is blacklisted.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('content-type: application/json; charset: utf-8');

    // Outright block anyone from trying to submit anything even if it is within the specified time of race submissions
    if ($config['allow_race_submissions'] === false) {
        log_info("Couldn't complete request for GUID: {$guid}, IP: {$ipv4} due to the race submissions not being enabled");
        die_json(403, "The server isn't accepting race submissions at this time, check back later");
    }
    
    $now = time();
    // Don't let anyone try to submit before or after the specified time of race submissions
    if (!($now >= $config['start_race_submissions'] && $now <= $config['end_race_submissions'])) {
        log_info("Couldn't complete request for GUID: {$guid}, IP: {$ipv4} as the race submissions either did not start or has already finished");
        die_json(403, "The server isn't accepting race submissions at this time because it either hasn't started yet or has already finished");
    }

    $_args = get_json_input();

    check_args_or_die($_args, array(
        'user-name',
        'user-country',
        'terrain-name',
        'terrain-filename',
        'script-name',
        'script-hash',
        'actor-name',
        'actor-filename',
        'actor-hash',
        'avg-fps',
        'ror-version',
        'split-times',
        'lap-time',
        'race-name',
        'race-name',
        'race-version'
    ));

    $db = connect_mysqli_or_die($config);

    function fetch_and_escape_arg($arg_name, $default_val)
    {
        global $_args, $db;
        
        if (isset($_args[$arg_name])) {
            return $db->real_escape_string($_args[$arg_name]);
        } else {
            return $default_val;
        }
    }

    $user_name = fetch_and_escape_arg('user-name', null);
    $user_country = fetch_and_escape_arg('user-country', null);
    $terrain_name = fetch_and_escape_arg('terrain-name', null);
    $terrain_filename = fetch_and_escape_arg('terrain-filename', null);
    $script_name = fetch_and_escape_arg('script-name', null);
    $script_hash = fetch_and_escape_arg('script-hash', null);
    $actor_name = fetch_and_escape_arg('actor-name', null);
    $actor_filename = fetch_and_escape_arg('actor-filename', null);
    $actor_hash = fetch_and_escape_arg('actor-hash', null);
    $avg_fps = fetch_and_escape_arg('avg-fps', null);
    $ror_version = fetch_and_escape_arg('ror-version', null);
    $split_times = fetch_and_escape_arg('split-times', null);
    $lap_time = fetch_and_escape_arg('lap-time', null);
    $race_name = fetch_and_escape_arg('race-name', null);
    $race_version = fetch_and_escape_arg('race-version', null);
    $disqualified = 0;

    // Determine if the player matches our records of usernames, if it is set
    // Also no point in marking them as disqualified as they weren't supposed to be able to submit a race anyways
    if (is_player_whitelisted($user_name) === false) {
        log_info("Couldn't complete request for GUID: {$guid}, IP: {$ipv4}, Username: {$user_name} as they were not on the whitelist");
        die_json(403, "You are not on the whitelist");
    }

    // Determine if the actor hash matches our hashes match, if they exist
    // From here on out, we give a different disqualified score from 1 to 4
    if (!empty($config['allowed-actors'])) {
        if (isset($config['allowed-actors'][$actor_filename])) { // Except here
            if($config['allowed-actors'][$actor_filename] != $actor_hash) {
                $disqualified = EVENT_DISQUALIFIED_TRUCK_HASH_ERROR;
            }
        } else {
            log_info("Couldn't complete request for GUID: {$guid}, IP: {$ipv4}, Username: {$user_name} as they were using an unrecognized Actor: {$actor_filename}");
            die_json(403, "You used an unrecognized actor");
        }
    }

    // Determine if the RoR version matches our RoR version
    if (isset($config['ror_version'])) {
        if ($ror_version != $config['ror_version']) {
            $disqualified = EVENT_DISQUALIFIED_VERSION_ERROR;
        }
    }

    // Determine if the terrain matches our terrain, if it is set
    if (isset($config['terrain'])) {
        if ($terrain_name != $config['terrain']) {
            $disqualified = EVENT_DISQUALIFIED_TERRAIN_ERROR;
        }
    }

    // Get exact server time and date
    $t          = time();
    $sql_stmt   = "
        INSERT INTO race (
            `username`,
            `country`,
            `terrain`,
            `terrain_filename`,
            `script`,
            `script_hash`,
            `actor`,
            `actor_filename`,
            `actor_hash`,
            `average_fps`,
            `ror_version`,
            `split_times`,
            `lap_time`,
            `date`,
            `race_name`,
            `race_version`,
            `request_tracking_guid`,
            `ipv4`,
            `disqualified`
        ) VALUES (
            '$user_name',
            '$user_country',
            '$terrain_name',
            '$terrain_filename',
            '$script_name',
            '$script_hash',
            '$actor_name',
            '$actor_filename',
            '$actor_hash',
            '$avg_fps',
            '$ror_version',
            '$split_times',
            '$lap_time',
            '$t',
            '$race_name',
            '$race_version',
            '$guid',
            '$ipv4',
            '$disqualified');";
    $result = $db->query($sql_stmt);
    if ($result === false) {
        log_error("Database error: {$db->error}");
        die_json(500, 'Server error, cannot write to database');
    }

    log_info("Request complete for GUID: {$guid}");
    $answer = array(
        'result' => true,
        'message' => 'Your event was successfully submitted'
    );
    http_response_code(200);
    log_info("Response: HTTP 200, message: {$answer['message']}");
    log_detail("JSON output:\n" . json_encode($answer, JSON_PRETTY_PRINT));
    die(json_encode($answer));
}