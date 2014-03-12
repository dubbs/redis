<?php

require '../vendor/predis/predis/autoload.php';

$client = new Predis\Client();

# clear db
$client->flushdb();

// add articles
$client->hset('posts', 1, json_encode(array('id' => 1, 'author' => 'admin', 'title' => 'Article 1', 'content' => 'Lorem Ipsum Dolor Sit Amet')));
$client->hset('posts', 2, json_encode(array('id' => 2, 'author' => 'admin', 'title' => 'Article 2', 'content' => 'Lorem Ipsum Dolor Sit Amet')));
$client->hset('posts', 3, json_encode(array('id' => 3, 'author' => 'admin', 'title' => 'Article 3', 'content' => 'Lorem Ipsum Dolor Sit Amet')));
$client->hset('posts', 4, json_encode(array('id' => 4, 'author' => 'admin', 'title' => 'Article 4', 'content' => 'Lorem Ipsum Dolor Sit Amet')));

// add dates
$client->zadd('post:date', 20090304, 1);
$client->zadd('post:date', 20100304, 2);
$client->zadd('post:date', 20110304, 3);
$client->zadd('post:date', 20120304, 4);

// find posts within range
$postIds = $client->zrangebyscore('post:date', 20100101, 20111231);

// retrieve posts by id, eg. $client->hmget('posts', 1, 2);
$posts = call_user_func_array(
	array($client, "hmget"),
	array_merge(array('posts'), $postIds)
);

// render
foreach ($posts as $post) {
	$post = json_decode($post);
	echo "<h1>{$post->title}</h1>";
	echo "<p>{$post->content}</p>";
}

