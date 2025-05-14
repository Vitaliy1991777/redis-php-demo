<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('mypassword');

$task = $redis->lPop('my_queue');
if ($task) {
    $data = json_decode($task, true);
    echo "Task: " . $data['task'] . ", To: " . $data['to'] . "\n";
} else {
    echo "Очередь пуста\n";
}
?>
