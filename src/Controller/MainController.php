<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        $videogames = $this->getDoctrine()->getRepository('App:Videogame')->findAll();
        if($this->isGranted("ROLE_USER")){
            $user = $this->getUser();
            $userAdmin = false;

            foreach($user->getRoles() as $roles){
                if($roles == "ROLE_ADMIN"){
                    $userAdmin = true;
                }
            }

        }else{
            $user = NULL;
            $userAdmin = false;
        }
            
        
        return $this->renderForm('main/index.html.twig', [
            'controller_name' => 'MainController',
            'videogames' => $videogames,
            'user' => $user,
            'userAdmin' => $userAdmin
        ]);
    }
}
