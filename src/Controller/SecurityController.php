<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/")
 */
class SecurityController extends Controller
{
    /**
     * @Route("/", name="app_main", methods="POST|GET")
     */
    public function accueil(): Response
    {
        $session=$this->get('session');
        $session->start();
        $utilisateur=$this->get('security.token_storage')->getToken()->getUser();
        if (!$utilisateur) return $this->redirectToRoute('app_login');
        $session->set('utilisateur',$utilisateur);
        //dump($session);die();
        return $this->redirectToRoute('journee_caisses_gerer');

    }
    
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
