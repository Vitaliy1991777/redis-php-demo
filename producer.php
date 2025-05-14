<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('mypassword');

$task = [
    'task' => 'send_email',
    'to' => 'user@example.com',
    'timestamp' => date('Y-m-d H:i:s')
];

$redis->rPush('my_queue', json_encode($task));
echo "Задача добавлена: " . $task['to'] . "\n";
?>
