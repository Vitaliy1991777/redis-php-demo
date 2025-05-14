<?php

date_default_timezone_set('Europe/Moscow');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('mypassword');

$queueKey = 'delayed_queue';

// Задача с задержкой 10 секунд
$delay = 10;

// Случайный тип задачи и действие
$types = ['daily', 'weekly', 'monthly', 'yearly'];
$taskNames = ['generate_report', 'send_email', 'clean_temp'];

$type = $types[array_rand($types)];
$taskName = $taskNames[array_rand($taskNames)];

// Формируем задачу
$task = json_encode([
    'task' => $taskName,
    'params' => ['type' => $type],
    'created_at' => time(),
    'id' => uniqid()
]);

// Время запуска задачи
$runAt = time() + $delay;
$redis->zAdd($queueKey, $runAt, $task);

echo "Task added, will be available at " . date('H:i:s', $runAt) . PHP_EOL;

// Обработка всех задач, которые уже "созрели"
$now = time();
$tasks = $redis->zRangeByScore($queueKey, 0, $now);

foreach ($tasks as $taskJson) {
    $data = json_decode($taskJson, true);

    if (!is_array($data) || !isset($data['task'])) {
        echo "Пропущена невалидная задача: $taskJson\n";
        $redis->zRem($queueKey, $taskJson);
        continue;
    }

    echo "Задача: " . $data['task'] . "\n";
    echo "Тип: " . $data['params']['type'] . "\n";
    echo "Время: " . date('H:i:s', $data['created_at']) . "\n";
    echo "-----\n";

    // Удаляем выполненную задачу
    $redis->zRem($queueKey, $taskJson);
}
?>
