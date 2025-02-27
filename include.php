<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/config/const.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/mail.php';

require_once __DIR__ . '/src/App/Db.php';
require_once __DIR__ . '/src/App/Cookie.php';
require_once __DIR__ . '/src/App/Router.php';
require_once __DIR__ . '/src/App/Mailer.php';
require_once __DIR__ . '/src/App/Validator.php';
require_once __DIR__ . '/src/App/EloCalculator.php';
require_once __DIR__ . '/src/App/MessageHandler.php';

require_once __DIR__ . '/src/App/Exceptions/NotFoundException.php';
require_once __DIR__ . '/src/App/Exceptions/MalformedRequestException.php';
require_once __DIR__ . '/src/App/Exceptions/AuthenticationException.php';
require_once __DIR__ . '/src/App/Exceptions/ControllerNotFoundException.php';
require_once __DIR__ . '/src/App/Exceptions/MethodNotFoundException.php';
require_once __DIR__ . '/src/App/Exceptions/RouteNotFoundException.php';
require_once __DIR__ . '/src/App/Exceptions/UnauthorizedAccessException.php';
require_once __DIR__ . '/src/App/Exceptions/PaymentException.php';
require_once __DIR__ . '/src/App/Exceptions/GameUnavailableException.php';
require_once __DIR__ . '/src/App/Exceptions/GameSettingsException.php';

require_once __DIR__ . '/src/App/Sockets/Chat.php';
require_once __DIR__ . '/src/App/Sockets/Game.php';
require_once __DIR__ . '/src/App/Sockets/Dashboard.php';

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
require_once __DIR__ . '/src/Controllers/controllerRanking.class.php';

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
require_once __DIR__ . '/src/Models/rememberToken.class.php';
require_once __DIR__ . '/src/Models/rememberToken.dao.php';
require_once __DIR__ . '/src/Models/gameRecord.class.php';
require_once __DIR__ . '/src/Models/gameRecord.dao.php';
require_once __DIR__ . '/src/Models/suggestion.class.php';
require_once __DIR__ . '/src/Models/suggestion.dao.php';
require_once __DIR__ . '/src/Models/moderator.class.php';
require_once __DIR__ . '/src/Models/moderator.dao.php';
require_once __DIR__ . '/src/Models/report.class.php';
require_once __DIR__ . '/src/Models/report.dao.php';

session_start();
require_once __DIR__ . '/config/twig.php';