<?php

use Nhida\LevartTest\App\View;

require_once __DIR__ . '/../../config/config.php';

if (isset($_SESSION['userToken'])) {
    header("Location: /home");
} else {
    echo "<div class='container col-5 mx-auto d-flex flex-column justify-content-center align-items-center min-vh-100'><a class='btn btn-primary btn-lg' href='" . $client->createAuthUrl() . "'>Google Login</a></div>";
}
