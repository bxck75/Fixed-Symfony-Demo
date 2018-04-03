<?php

namespace App\Controller;

use App\Events;
use Symfony\Component\Form\Forms;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Terminal;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Cocur\BackgroundProcess\BackgroundProcess;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Form\NmapScanType;
class GroundZeroController extends AbstractController
{
    public $Output;
    public $RunnableCommand;
    /**
     * @Route("/ground/zero", name="ground_zero")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(NmapScanType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
           $formdata = $form->getData();
           echo  $this->RunnableCommand = $formdata["tool"].' '.$formdata["ip"].' '.$formdata["ports"].' '.$formdata["params"].' '.$formdata["outputfile"];

            
           }
           return $this->render('ground_zero/index.html.twig', [
                'controller_name' => 'GroundZeroController',
                'sh_com' => $this->Processie(),
                'form' => $form->createView(),
                'command' => $this->RunnableCommand,
            ]);


    }
    public function Processie() {

        $ErrOutput = array();
        $Output = array();
        $process = new Process($this->RunnableCommand);
        //$process = new Process('ls -lsa');
        $process->start();

        foreach ($process as $type => $data) {

            if ($process::OUT === $type) {
                $Output[] = $data;
            } else { // $process::ERR === $type
                $ErrOutput[] = "\n ".$data;
            }

        } 
    return array($Output,$ErrOutput);
    return array(explode('\n',$Output[0]),$ErrOutput);
    }
    
}
