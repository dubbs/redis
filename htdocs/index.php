<?php

require '../vendor/predis/predis/autoload.php';

$client = new Predis\Client();

# clear db
$client->flushdb();

// Articles
$client->hset('post:1', 'author', 'admin');
$client->hset('post:1', 'title', 'Article 1');
$client->hset('post:1', 'content', 'Lorem Ipsum Dolor Sit Amet');
$client->hset('post:1', 'date', 20090304);
$client->zadd('post:date', 20090304, 1); // add date to sorted set

$client->hset('post:2', 'author', 'admin');
$client->hset('post:2', 'title', 'Article 2');
$client->hset('post:2', 'content', 'Lorem Ipsum Dolor Sit Amet');
$client->hset('post:2', 'date', 20100304);
$client->zadd('post:date', 20100304, 2);

$client->hset('post:3', 'author', 'admin');
$client->hset('post:3', 'title', 'Article 3');
$client->hset('post:3', 'content', 'Lorem Ipsum Dolor Sit Amet');
$client->hset('post:3', 'date', 20110304);
$client->zadd('post:date', 20110304, 3);

$client->hset('post:4', 'author', 'admin');
$client->hset('post:4', 'title', 'Article 4');
$client->hset('post:4', 'content', 'Lorem Ipsum Dolor Sit Amet');
$client->hset('post:4', 'date', 20120304);
$client->zadd('post:date', 20120304, 4);

// find posts within range
$postIds = $client->zrangebyscore('post:date', 20100101, 20111231);

// retrieve posts
$pipeline = $client->pipeline();
foreach ($postIds as $postId) {
	$pipeline->hgetall("post:{$postId}");
}
$posts = $pipeline->execute();

// render
foreach ($posts as $post) {
	$post = (object) $post;
	echo "<h1>{$post->title}</h1>";
	echo "<p>{$post->content}</p>";
}

