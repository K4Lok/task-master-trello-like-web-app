<?php

require __DIR__ . '/Core/Router.php';

$route = new Router();

$route->get('/', function () {
    echo "home page";
});

$route->run();