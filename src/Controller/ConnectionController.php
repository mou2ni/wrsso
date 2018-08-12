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
     * @Route("/login", name="login", methods="GET|POST")
     */
    public function identifier(Request $request)
    {
        $erreur='';
        $user = new Utilisateurs();
		$form = $this->createForm(LoginType::class, $user );
		$form->handleRequest($request);
		$pass=hash('SHA1', "".$user->getMdp());
		$user->setMdp($pass);
			
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getRepository(Utilisateurs::class)->findOneBy(['login'=>$user->getLogin(),'mdp'=>$user->getMdp()]);
            if ($user)
            {
                $user->setIsAuthaticate('true');
                $jc=$this->getDoctrine()->getRepository(JourneeCaisses::class)->findOneBy(['utilisateur'=>$user, 'statut'=>JourneeCaisses::OUVERT]);
                $jc ?: $jc = new JourneeCaisses();
                $this->get('session')->set('user', $user);
                $this->get('session')->set('journeeCaisse', $jc);
                return $this->redirectToRoute('journee_caisses_index');
            }
            else
                $erreur="Nom d'utilisateur et ou mot de passe incorect";
			
      }

        return $this->render('connection/index.html.twig', array(
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


