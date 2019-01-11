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
        //dump($session);die();
        /*if($utilisateur->getRole()=='9bffcbfad2a9e744c85236db89d88773') { //

            return $this->redirectToRoute('journee_caisses_gerer');
        }*/
        switch ($utilisateur->getRole()){
            //guichetier
            case '9bffcbfad2a9e744c85236db89d88773' : return $this->redirectToRoute('journee_caisses_gerer');
            //comptable
            case '26927809602fed9d09fe8cf2f9daa402' : return $this->render('security/login.html.twig', ['last_username' => '', 'error' => 'COMPTABLE NON ENCORE IMPLEMENTE']);break;
            case '73acd9a5972130b75066c82595a1fae3' : return $this->render('security/login.html.twig', ['last_username' => '', 'error' => 'ADMINISTRATEUR NON ENCORE IMPLEMENTE']);break;
            default : return $this->render('security/login.html.twig', ['last_username' => '', 'error' => 'ROLE INCONNU']);
        }

        //$this->addFlash('error', "vous n'etes pas Caissier? munissez vous des droits necessaires puis reessayez");
        
        return $this->redirectToRoute('app_login');

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
