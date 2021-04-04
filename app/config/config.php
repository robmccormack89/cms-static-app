<?php
define('APP_PROTOCOL', 'http');
define('CHARSET', 'UTF-8');
define('AUTHOR_IP', '127.0.0.1');
define('REMOTE_ADDR', $_SERVER['REMOTE_ADDR']);

$root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
define('BASE_URL', $root);