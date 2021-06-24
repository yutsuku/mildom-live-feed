<?php
declare(strict_types=1);

use \FeedWriter\ATOM;
use \DataProvider\DataProvider;

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
    $protocol = (isset($_SERVER['HTTPS']) ? 'https' : 'http');
    $self_url = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $dpr = DataProvider::get($id);

    $feed = new ATOM();
    $feed->setTitle($dpr->author_name);
    $feed->setDate(time());
    $feed->setSelfLink($self_url);
    $feed->setLink($dpr->channel_url);

    $item = $feed->createNewItem();
    $item->setTitle($dpr->stream_description);
    $item->setAuthor($dpr->author_name);
    $item->setLink($dpr->stream_url);

    $content = sprintf('<img src="%s" alt="thumbnail" /><br /><br />', $dpr->stream_image);

    foreach ($dpr->videos as $video) {
        $content .= sprintf('<a href="%s">%s</a><br />', $video->url, $video->definition);
    }

    $item->setContent($content);
    $item->setDate($dpr->stream_publish_date);
    $feed->addItem($item);

    $feed->printFeed();
} catch (\Exception $e) {
    http_response_code(500);
    exit();
}
