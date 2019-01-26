<?php

namespace App\Controller;

use App\Utils\SessionUtilisateur;
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
    public function accueil(SessionUtilisateur $sessionUtilisateur): Response
    {
         //$utilisateur=$this->get('security.token_storage')->getToken()->getUser();

        $utilisateur=$sessionUtilisateur->getUtilisateur();
        
        if (!$utilisateur) {
            $this->addFlash('error','Session utilisateur expirée. Merci de vous reconnecter');
            return $this->redirectToRoute('app_login');
        }

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            //page d'accueil admin
            //return $this->redirectToRoute('admin_main');
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_COMPTABLE')) {
            //page d'accueil admin
            return $this->redirectToRoute('compta_main');
        }
        if ($this->get('security.authorization_checker')->isGranted('ROLE_GUICHETIER')) {
            return $this->redirectToRoute('journee_caisses_gerer');
        }
        //dump($session);die();
        /*if($utilisateur->getRole()=='9bffcbfad2a9e744c85236db89d88773') { //

            return $this->redirectToRoute('journee_caisses_gerer');
        }
        switch ($utilisateur->getRole()){
            //guichetier
            case 'ROLE_9bffcbfad2a9e744c85236db89d88773' : return $this->redirectToRoute('journee_caisses_gerer');
            //comptable
            case 'ROLE_26927809602fed9d09fe8cf2f9daa402' : return $this->render('security/login.html.twig', ['last_username' => '', 'error' => 'COMPTABLE NON ENCORE IMPLEMENTE']);break;
            case 'ROLE_73acd9a5972130b75066c82595a1fae3' : return $this->render('security/login.html.twig', ['last_username' => '', 'error' => 'ADMINISTRATEUR NON ENCORE IMPLEMENTE']);break;
            default : return $this->render('security/login.html.twig', ['last_username' => '', 'error' => 'ROLE INCONNU']);
        }

        //$this->addFlash('error', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");

        return $this->redirectToRoute('app_login');*/

    }
    
    /**
     * @Route("/login", name="app_login")
     */
    public function loginAction(Request $request): Response
    {
       

        $authenticationUtils=$this->get('security.authentication_utils');
        return $this->render('security/login.html.twig', ['last_username' => $authenticationUtils->getLastUsername(), 'error' => $authenticationUtils->getLastAuthenticationError()]);

        // get the login error if there is one
        //$error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        //$lastUsername = $authenticationUtils->getLastUsername();

    }
}
