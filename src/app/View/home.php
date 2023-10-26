<?php

use Nhida\LevartTest\App\View;

require_once __DIR__ . '/../../config/config.php';

if (isset($_GET['code'])) {
    if (!isset($_SESSION['userToken'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);
        $_SESSION['userToken'] = $token;
    }
} else {
    if (!isset($_SESSION['userToken'])) {
        header("Location: /");
        die();
    }
}

?>

<div class="container col-5 mx-auto d-flex flex-column justify-content-center align-items-center min-vh-100">
    <h1>Send Mail</h1>
    <form action="#" method="post">
        <input class="form-control" type="text" name="name" placeholder="Name" autocomplete="off">
        <input class="form-control my-3" type="email" name="email" placeholder="Email" autocomplete="off">
        <input class="form-control my-3" type="text" name="subject" placeholder="Subject" autocomplete="off">
        <button type="submit" class="btn btn-primary w-100">Send</button>
    </form>
    <form action="/logout" method="get">
        <button type="submit" class="btn btn-success my-3 w-100">Logout</button>
    </form>
</div>
