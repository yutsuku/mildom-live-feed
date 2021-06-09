<?php
declare(strict_types=1);

use \FeedWriter\ATOM;

$id = $_GET['id'] ?? 0;
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    exit();
}

try {
    $dpr = DataProvider::get($id);

    $feed = new ATOM();
    $feed->setTitle($dpr->author_name);

    $item = $feed->createNewItem();
    $item->setTitle($dpr->stream_description);
    $item->setAuthor($dpr->author_name);
    $item->setLink($dpr->channel_url);
    $item->setContent(sprintf('<img src="%s" alt="thumbnail" />', $dpr->stream_image));
    $item->setDate(time());
    $feed->addItem($item);

    $feed->printFeed();
} catch (\Exception $e) {
    http_response_code(500);
    exit();
}

