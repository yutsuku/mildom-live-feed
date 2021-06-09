<?php
declare(strict_types=1);

use \FeedWriter\ATOM;

$id = $_GET['id'] ?? 0;
$id = filter_var($id, FILTER_VALIDATE_INT, [
    'options' => [
        'min_range' => 1
    ]
]);

if (!$id) {
    http_response_code(400);

    $html = <<<'EOF'
        <form name="main" method="GET">
        ID: <input type="text" name="id"> <input type="submit">
        </form>
        <div>Feed URL: <a id="url" target="_blank" href="#"></a></div>

        <script>
            let form = document.querySelector('form');
            form.addEventListener('submit', function(event) {
                let id = document.querySelector('form [name="id"]');
                let anchor = document.querySelector('#url');
                let url = window.location.origin + window.location.pathname + '?id=' + id.value;
                anchor.setAttribute('href', url);
                anchor.innerHTML = url;

                event.preventDefault();
            }, true);
        </script>
    EOF;

    echo $html;
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

