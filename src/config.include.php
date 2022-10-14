<?php

$config = [
    'database' => [
        "host"     => "localhost",
        "name"     => "dbname",
        "user"     => "dbuser",
        "password" => "dbpassword"
    ],
    'admin' => [
        "username" => "admin",
        "password" => "password",
        "limit-ip" => NULL, // Restrict the login to your IP, set null to disable
    ],
    'track-marshals' => [
        'Admin',
        'Admin2',
        'Admin3'
    ],
    'allowed-actors' => [ // Leave empty if you do not want to check actor hashes
        // It is highly recommended to verify that your participants are using unmodified
        // versions of mods by comparing the actual hash vs the participants hash
        "PandorasActor.truck" => 'dNWAadkJWBabdhjwabd18dbajdwbajdb'
    ],
    'terrain' => 'NeoQueretaro', // set to NULL for none
    'supported_ror_versions' => '2022.04', // Must be exact, NULL for none
    'player-whitelist' => [], // Leave empty for none
    'ip-blacklist' => [], // Leave empty for none
    'rules' => [ // Leave empty for none
        'A very simple rule',
        'Another very simple rule',
        'Perhaps another very simple rule'
    ],
    'allow_race_submissions' => true, // If false, nothing can be submitted
    // Use https://www.unixtimestamp.com/ and convert to Unix UTC
    // Keep in mind the timezone of your server or PC!!!!
    'start_race_submissions' => 1665701771,
    'end_race_submissions' => 1665701771,
    'logging' => [
        "filename" => "output.log", // Set null to disable
        "verbosity" => LOG_LEVEL_DEBUG
    ]
];