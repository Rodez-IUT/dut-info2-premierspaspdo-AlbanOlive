<?php

spl_autoload_extensions(".php");
spl_autoload_register();

use yasmf\DataSource;
use yasmf\Router;

// have to create a dummy "my_activities" database...
$dataSource = new DataSource(
    $host = 'localhost',
    $port = '3306',
    $db = 'my_activities',
    $user = 'root',
    $pass = 'root',
    $charset = 'utf8mb4'
);

$router = new Router() ;
$router->route($dataSource);
