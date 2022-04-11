<?php

namespace App\Downloader;

class Validator
{
    private $url;

    public function __construct(string $url)
    {
        $this->url = trim($url);
        //echo ">>$url<<";
    }

    public function validateUrl(): bool
    {
        $result = filter_var($this->url, FILTER_VALIDATE_URL);
        return $result;
    }

    public function isImage():bool
    {
        if(is_array(getimagesize($this->url))){
            return true;
        }else{
            return false;
        }
    }

}