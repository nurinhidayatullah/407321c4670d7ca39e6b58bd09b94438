<?php

require __DIR__ . '/vendor/autoload.php';

use Nhida\LevartTest\App\Router;
use Nhida\LevartTest\Controller\MailController;
use Nhida\LevartTest\Controller\UserController;

Router::add('POST', '/authorize', UserController::class, 'login');
Router::add('POST', '/send', MailController::class, 'send');
Router::run();
