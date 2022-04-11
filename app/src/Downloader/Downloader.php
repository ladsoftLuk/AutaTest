<?php

namespace App\Downloader;

class Downloader
{
    private $url;
    private $e;

    public function __construct(string $url )
    {
        $this->url = $url;
    }

    public function download(string $downloadDir){
        $file = file_get_contents($this->url); // to get file
        $info = getimagesize($this->url);
        $type = $info['mime'];
        $ext = substr($type,6);
        $this->e = $ext;
        $name = date('Y_m_d_').random_int(1,1000);
        file_put_contents($downloadDir.'/'.$name.".".$ext, $file);
    }

    public function getExt(){
        return $this->e;
    }
}