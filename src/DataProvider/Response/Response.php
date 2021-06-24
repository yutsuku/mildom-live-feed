<?php
declare(strict_types=1);

namespace DataProvider\Response;

class Response
{
    public bool $is_live;
    public string $stream_description;
    public string $stream_image;
    public string $stream_url;
    public VideoLinks $videos;
    public int $stream_publish_date;
    public string $author_name;
    public string $channel_url;
}
