<?php

namespace Nhida\LevartTest\Config;

require __DIR__ . '/../../vendor/autoload.php';

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use League\OAuth2\Server\AuthorizationServer;
use Nhida\LevartTest\Repositories\ScopeRepository;
use Nhida\LevartTest\Repositories\ClientRepository;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use Nhida\LevartTest\Repositories\AccessTokenRepository;
use League\OAuth2\Server\Middleware\AuthorizationServerMiddleware;

$clientRepository = new ClientRepository();
$scopeRepository = new ScopeRepository();
$accessTokenRepository = new AccessTokenRepository();
$privateKey = 'file://' . __DIR__ . '/../../private.key';
$encryptionKey = 't0XQ8dcmfhPhVQL8kHajhzk3XKoyEN+NO0cccK+hrv8=';

$server = new AuthorizationServer(
    $clientRepository,
    $accessTokenRepository,
    $scopeRepository,
    $privateKey,
    $encryptionKey
);


$server->enableGrantType(
    new ClientCredentialsGrant(),
    new \DateInterval('PT1H')
);

$middleware = new AuthorizationServerMiddleware($server);

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$request = $creator->fromGlobals();
$response = new \GuzzleHttp\Psr7\Response();
