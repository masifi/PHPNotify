<?php

require 'vendor/autoload.php';

$data = json_decode(file_get_contents('php://input'), true);

if(isset($data['title'])){
    $data['time'] = date('Y-m-d h:i p');

    // This is our new stuff
    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");

    $socket->send(json_encode($data));

    echo json_encode(['status'=>1, 'msg'=>'Blog post create and notification is sent']);
}