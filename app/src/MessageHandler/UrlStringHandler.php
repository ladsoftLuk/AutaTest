<?php
namespace App\MessageHandler;

use App\Downloader\Downloader;
use App\Downloader\Validator;
use App\Entity\CurrentUrl;
use App\Message\UrlString;
use App\Repository\CurrentUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UrlStringHandler
{
    private $repo;
    private $em;

    public function __construct( CurrentUrlRepository $cur, EntityManagerInterface $em)
    {
        $this->repo = $cur;
        $this->em = $em;
    }
    public function __invoke(UrlString $message)
    {
        echo 'test';
        $info = [];
        $url = $message->getContent();
        $validator = new Validator($url);
                $isUrl = $validator->validateUrl();
                if (!$isUrl){
                    $info[] = array('url'=>$url, 'txt'=>"Not valid URL."); 
                }else{
                    $isImage = $validator->isImage();
                    if (!$isImage){
                        $info[] = array('url'=>$url, 'txt'=>"Not an image file."); 
                    }else{
                        $info[] = array('url'=>$url, 'txt'=>"Pobieram."); 
                        $resu = $this->repo->findBy(array(),array('id'=>'DESC'),1,0);
                        $urlData = $resu[0];
                        $urlData->setUrl($url." - Processing");
                        sleep(1);
                        $this->em->flush();
                        $downloader = new Downloader($url);
                        $downloader->download($message->getDir());
                        $urlData->setUrl($url." - DONE");
                        $this->em->flush();
                        sleep(1);
                    }
                }
        print_r ($info);
    }
}