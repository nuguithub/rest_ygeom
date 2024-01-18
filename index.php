<?php
    require_once "db_connection.php";
    require_once "router.php";
    require_once "eventController.php";
    require_once "login.php";


    header("Content-Type: application/json");

    $router = new Router();

    // events endpoint
    $router->get('/', 'eventController@test');
    $router->get('/events', 'eventController@index');
    $router->post('/events', 'eventController@store');
    $router->get('/events/{event_id}', 'eventController@show');
    $router->put('/events/{event_id}', 'eventController@update');
    $router->delete('/events/{event_id}', 'eventController@destroy');
    $router->post('/login', 'authController@login');

    $router->handleRequest();
?>