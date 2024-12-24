<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/config/const.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/mail.php';

require_once __DIR__ . '/src/App/Router.php';
require_once __DIR__ . '/src/App/Validator.php';
require_once __DIR__ . '/src/App/MessageHandler.php';

require_once __DIR__ . '/src/App/Exception/NotFoundException.php';
require_once __DIR__ . '/src/App/Exception/MalformedRequestException.php';
require_once __DIR__ . '/src/App/Exception/AuthenticationException.php';
require_once __DIR__ . '/src/App/Exception/ControllerNotFoundException.php';
require_once __DIR__ . '/src/App/Exception/MethodNotFoundException.php';
require_once __DIR__ . '/src/App/Exception/RouteNotFoundException.php';
require_once __DIR__ . '/src/App/Exception/UnauthorizedAccessException.php';
require_once __DIR__ . '/src/App/Exception/PaymentException.php';
require_once __DIR__ . '/src/App/Exception/GameUnavailableException.php';
require_once __DIR__ . '/src/App/Exception/GameSettingsException.php';

require_once __DIR__ . '/src/App/Socket/Chat.php';
require_once __DIR__ . '/src/App/Socket/Game.php';

require_once __DIR__ . '/src/Controllers/controllerFactory.class.php';
require_once __DIR__ . '/src/Controllers/controller.class.php';
require_once __DIR__ . '/src/Controllers/controllerProfile.class.php';
require_once __DIR__ . '/src/Controllers/controllerGame.class.php';
require_once __DIR__ . '/src/Controllers/controllerShop.class.php';
require_once __DIR__ . '/src/Controllers/controllerAuth.class.php';
require_once __DIR__ . '/src/Controllers/controllerBasket.class.php';
require_once __DIR__ . '/src/Controllers/controllerSuggestion.class.php';
require_once __DIR__ . '/src/Controllers/controllerDashboard.class.php';
require_once __DIR__ . '/src/Controllers/controllerPolicy.class.php';

require_once __DIR__ . '/src/Models/db.class.php';
require_once __DIR__ . '/src/Models/player.class.php';
require_once __DIR__ . '/src/Models/player.dao.php';
require_once __DIR__ . '/src/Models/user.class.php';
require_once __DIR__ . '/src/Models/user.dao.php';
require_once __DIR__ . '/src/Models/game.class.php';
require_once __DIR__ . '/src/Models/game.dao.php';
require_once __DIR__ . '/src/Models/article.class.php';
require_once __DIR__ . '/src/Models/article.dao.php';
require_once __DIR__ . '/src/Models/invoice.class.php';
require_once __DIR__ . '/src/Models/invoice.dao.php';
require_once __DIR__ . '/src/Models/statistics.class.php';
require_once __DIR__ . '/src/Models/passwordResetToken.class.php';
require_once __DIR__ . '/src/Models/passwordResetToken.dao.php';
require_once __DIR__ . '/src/Models/gameRecord.class.php';
require_once __DIR__ . '/src/Models/gameRecord.dao.php';
require_once __DIR__ . '/src/Models/suggestion.class.php';
require_once __DIR__ . '/src/Models/suggestion.dao.php';
require_once __DIR__ . '/src/Models/moderator.class.php';
require_once __DIR__ . '/src/Models/moderator.dao.php';

session_start();
require_once __DIR__ . '/config/twig.php';