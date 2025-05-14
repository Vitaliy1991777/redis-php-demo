<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('mypassword');

$redis->set("key1", "Привет, Redis!");
echo "key1: " . $redis->get("key1") . "\n";

$redis->set("int", 123);
$redis->lPush("list", "a", "b", "c");
$redis->sAdd("set", "x", "y", "z");
$redis->hSet("hash", "field1", "value1");

echo "int: " . $redis->get("int") . "\n";
print_r($redis->lRange("list", 0, -1));
print_r($redis->sMembers("set"));
print_r($redis->hGetAll("hash"));
?>
