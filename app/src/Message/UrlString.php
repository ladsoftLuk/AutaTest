<?php

namespace App\Message;

class UrlString
{
    private $content;
    private $dir;

    public function __construct(string $content, string $dir)
    {
        $this->content = $content;
        $this->dir = $dir;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getDir(): string
    {
        return $this->dir;
    }
}