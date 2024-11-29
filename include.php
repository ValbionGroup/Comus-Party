<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/config/const.php';
require_once __DIR__ . '/config/db.php';

require_once __DIR__ . '/src/Models/Router.php';

require_once __DIR__ . '/src/Models/Exceptions/ErrorHandler.php';
require_once __DIR__ . '/src/Models/Exceptions/NotFoundException.php';
require_once __DIR__ . '/src/Models/Exceptions/MalformedRequestException.php';
require_once __DIR__ . '/src/Models/Exceptions/AuthentificationException.php';
require_once __DIR__ . '/src/Models/Exceptions/ControllerNotFoundException.php';
require_once __DIR__ . '/src/Models/Exceptions/MethodNotFoundException.php';
require_once __DIR__ . '/src/Models/Exceptions/RouteNotFoundException.php';
require_once __DIR__ . '/src/Models/Exceptions/UnauthorizedAccessException.php';
require_once __DIR__ . '/src/Models/Exceptions/PaymentException.php';

require_once __DIR__ . '/src/Controllers/controllerFactory.class.php';
require_once __DIR__ . '/src/Controllers/controller.class.php';
require_once __DIR__ . '/src/Controllers/controllerProfile.class.php';
require_once __DIR__ . '/src/Controllers/controllerGame.class.php';
require_once __DIR__ . '/src/Controllers/controllerShop.class.php';
require_once __DIR__ . '/src/Controllers/controllerAuth.class.php';
require_once __DIR__ . '/src/controllers/controllerBasket.class.php';

require_once __DIR__ . '/src/Models/db.class.php';
require_once __DIR__ . '/src/Models/player.class.php';
require_once __DIR__ . '/src/Models/player.dao.php';
require_once __DIR__ . '/src/Models/user.class.php';
require_once __DIR__ . '/src/Models/user.dao.php';
require_once __DIR__ . '/src/Models/game.class.php';
require_once __DIR__ . '/src/Models/game.dao.php';
require_once __DIR__ . '/src/Models/article.class.php';
require_once __DIR__ . '/src/Models/article.dao.php';
require_once __DIR__ . '/src/Models/statistics.class.php';

session_start();
require_once __DIR__ . '/config/twig.php';