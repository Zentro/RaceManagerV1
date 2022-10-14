## Introduction

This is provided as-is and will likely only get minor improvements. It's very sloppy PHP code, but it remains lightweight because of that fact. There are no additional vendors needed or any PHP extensions. You can expect some performance drawback if you need to scale up beyond a couple thousand event entries. But otherwise it will do what it's expected to do.

## Requirements

- PHP 7.4+

## Installation

You can run this on basically anything that is running the minimum required PHP version coupled with an Apache or nginx webserver (basically, your traditional LAMP or WAMP stack). You may need to configure a few things with the webserver of your choice to allow the htaccess rewrite to work.