<?php

namespace Nhida\LevartTest\Controller;

use GuzzleHttp\Psr7\Stream;
use League\OAuth2\Server\Exception\OAuthServerException;
ini_set('display_errors', 0);
class UserController
{

    public function login()
    {
        include_once __DIR__ . '/../Config/Oauth2.php';
        try {
            $response = $middleware(
                $request,
                $response,
                function (\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response) {
                    return $response->withStatus(200);
                }
            );
            $data = json_decode($response->getBody(), true);
            http_response_code(200);
            echo $response->withStatus(200)->getBody();
        } catch (OAuthServerException $exception) {
            $response = $exception->generateHttpResponse($response);
        } catch (Exception $exception) {
            $body = new Stream('php://temp', 'r+');
            $body->write($exception->getMessage());
            http_response_code(500);
            echo $response->withStatus(500)->withBody($body);
        }
    }
}
