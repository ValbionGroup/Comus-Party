<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/config/const.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/twig.php';

require_once __DIR__ . '/src/models/Router.php';
require_once __DIR__ . '/src/models/CustomExceptions.php';