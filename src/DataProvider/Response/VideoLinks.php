<?php
declare(strict_types=1);

namespace DataProvider\Response;

/**
 * array of VideoLink
 */
class VideoLinks implements \Iterator
{
    private $elements = [];
    private $position = 0;

    public function __construct()
    {
        $this->position = 0;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current(): VideoLink
    {
        return $this->elements[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->elements[$this->position]);
    }

    public function add(VideoLink $videoLink)
    {
        $this->elements[] = $videoLink;
    }
}
