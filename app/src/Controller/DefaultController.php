<?php

namespace App\Controller;


use App\Entity\CurrentUrl;
use App\Message\UrlString;
use App\Repository\CurrentUrlRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends AbstractController
{
    
    public $current = 'wait';
    #[Route('/', name: 'main')]
    public function index(Request $request, MessageBusInterface $bus, string $downloadDir, EntityManagerInterface $entityManager ): Response
    {
        
        $info = [];
        $default = ['Urls' => ''];
        $form = $this->createFormBuilder($default)
            ->add('Urls', TextareaType::class)
            ->add('Submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $urls = $form->getData();
            $arr = explode(PHP_EOL, $urls['Urls']);
            $currentUrl = new CurrentUrl();
            $currentUrl->setUrl('Waiting for urls');
            $currentUrl->setTimeStamp(new DateTimeImmutable());
            $entityManager->persist($currentUrl);
            $entityManager->flush();
            
            foreach ($arr as $url) {
                $url=trim($url);
                
                $msg = new UrlString($url, $downloadDir);
                $bus->dispatch($msg);
            }
            return $this->redirectToRoute('confirm');
        }

        return $this->render('Downloader/index.html.twig', [
            'form'=> $form->createView()
        ]);
        
    }
    #[Route('/ajax', name: 'ajax1')]
    public function ajaxAction(Request $request, CurrentUrlRepository $currentUrlRepository) 
    { 
        $resu = $currentUrlRepository->findBy(array(),array('id'=>'DESC'),1,0);
        $currentUrl = $resu[0];
        $url = $currentUrl->getUrl();
 
        $jsonData = '{"url": "'.$url.'"}';
           return new JsonResponse($jsonData); 
        
        
     }
   
     #[Route('/confirm', name: 'confirm')]
     public function confirm(Request $request) 
     { 
        $test = 'wait';
        return $this->render('Downloader/confirm.html.twig', [
            'test'=> $test,

        ]); 
         
         
      }

     
}