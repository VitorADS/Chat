#!/usr/bin/env php
<?php

declare(strict_types=1);

use Swoole\WebSocket\Request;
use Swoole\WebSocket\Response;
use Swoole\WebSocket\Server;
use Swoole\WebSocket\Frame;

$http = new Server('0.0.0.0', 9501);

//$http->on(
//    'start',
//    function (Server $http) {
//        echo "Swoole HTTP server is started.\n";
//    }
//);
// $http->on(
//    'request',
//    function (Request $request, Response $response) {
//        $response->end('xablau');
//    }
// );

$http->on(
    'message',
    function (Server $http, Frame $message) {
        $conexoes = $http->connections;
        $origem = $message->fd;

        foreach ($conexoes as $conexao) {
            if($conexao === $origem) continue;
            $http->push(
                $conexao,
                json_encode(['type' => 'chat', 'text' => $message->data])
            );
        }
    }
);

$http->start();
