## Introduction

This is provided as-is and will likely only get minor improvements. It's very sloppy PHP code, but it remains lightweight because of that fact. There are no additional vendors needed or any PHP extensions. You can expect some performance drawback if you need to scale up beyond a couple thousand event entries. But otherwise it will do what it's expected to do.

Bits of the source code is based on the [legacy Rigs of Rods multiplayer API](https://github.com/RigsOfRods/multiplayer.rigsofrods.org/)

## Requirements

- PHP 7.4+

## Installation

You can run this on basically anything that is running the minimum required PHP version coupled with an Apache or nginx webserver (basically, your traditional LAMP or WAMP stack). You may need to configure a few things with the webserver of your choice to allow the htaccess rewrite to work.

1. Copy the .php files from the `src` directory to your webserver
2. Run `eventmanager.sql` script to create and populate the `eventmanager` database
3. Make your changes to the `config.include.php` file as neccessary
