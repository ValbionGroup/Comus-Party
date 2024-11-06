<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/config/const.php';
require_once __DIR__ . '/config/db.php';

require_once __DIR__ . '/config/twig.php';

require_once __DIR__ . '/src/models/db.class.php';
require_once __DIR__ . '/src/models/player.class.php';
require_once __DIR__ . '/src/models/player.dao.php';

require_once __DIR__ . '/src/controllers/controllerFactory.class.php';
require_once __DIR__ . '/src/controllers/controller.class.php';
require_once __DIR__ . '/src/controllers/controllerProfile.class.php';
//require_once __DIR__ . '/src/controllers/controllerShop.class.php';

