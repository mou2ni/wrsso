<?php

namespace App\Controller;

use App\Entity\JourneeCaisses;
use App\Entity\Utilisateurs;
use App\Entity\Caisses;
use App\Form\LoginType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Tests\Extension\Core\Type\PasswordTypeTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/*
 * @Route("/Wrsso", name="connection")
 */
class ConnectionController extends Controller
{

    /**
     * @Route("/login1", name="app_login1", methods={"GET|POST"})
     */
    public function identifier(Request $request)
    {

        //dump($request);die();
        //if ($this->get('session')->get('journeeCaisse'))
        $erreur='';
        $user = new Utilisateurs();
		$form = $this->createForm(LoginType::class, $user );

        $form->handleRequest($request);

        //$pass=hash('SHA1', "".$user->getMdp());

        //$user->setMdp($pass);
			
        if ($form->isSubmitted() && $form->isValid()) {

			$user = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>$user->getLogin(),'mdp'=>$user->getMdp()]);
			if ($user)
            {
                $roles[] = 'ROLE_USER';
                $user->setRole($roles);
                dump($user);die();
                $user->setIsAuthaticate('true');
                //dump($this->se);die();

                //$this->get('session')->set('user', $user);
                /*$journeeCaisseEnCour=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findOneBy(['utilisateur'=>$user, 'statut'=>JourneeCaisses::OUVERT]);
                if(!$journeeCaisseEnCour){
                    $journeeCaisseEnCour=new JourneeCaisses();
                $journeeCaisseEnCour->setUtilisateur($user);
                }*/
                //$this->get('session')->remove('journeeCaisse');


                $this->get('session')->set('utilisateur', $user);
                return $this->redirectToRoute('journee_caisses_gerer');
            }
            else
                $erreur="Nom d'utilisateur et ou mot de passe incorect";


                //return $this->redirectToRoute('journee_caisses_ouverture');

            //return $this->redirectToRoute('billetages_index');
			/*if($this->get('security.token_storage')->getToken()){
				$this->get('security.token_storage')->getToken()->setAttribute('caisse', $form['caisse']->getData());
				dump($this->get('security.token_storage')->getToken());die();
			}*/
			
      }

        return $this->render('connection/index.html.twig', array(
            //'last_username' => $lastUsername,
            'error'         => $erreur,
            'form' => $form->createView()
        ));

    }
	



	/**
     * @Route("/logout", name="logout", methods="GET|POST")
     */
    public function logout()
    {
        
        return $this->redirectToRoute('login');
    }


}


